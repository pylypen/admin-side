<?php namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\Response;
use Yii;
use yii\web\ForbiddenHttpException;
use app\helpers\WorldpayHelper;
use Exception;

/**
 * This is the model class for table "transaction_history".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $transation_id
 * @property string $description
 * @property string $status
 * @property float $amount
 * @property string $currency
 * @property integer $created_at
 * @property integer $updated_at
 */
class TransactionHistory extends ActiveRecord
{
    private static $worldpayKey = "T_S_7d0fb15b-fbae-491a-868c-f25d7fe7a584";
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_history}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'transation_id', 'status', 'amount'], 'required'],
            [['user_id', 'class_id', 'transaction_dispute_time', 'transaction_refund'], 'integer'],
            [['transation_id', 'description', 'status', 'currency'], 'string'],
            ['amount', 'number'],
            ['transaction_dispute_status', 'default', 'value' => 'close'],
            ['transaction_dispute_status', 'in', 'range' => ['close', 'open', 'approved', 'declined']]
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();
        $fields['student_name'] = function () {
            $student = $this->getStudentName();
            return $student->payment_first_name . ' ' . $student->payment_last_name;
        };
        $fields['dispute_type'] = function () {
            return $this->getDisputeType();
        };
        
        return $fields;
    }
    
    private function getDisputeType()
    {
        return $this->hasOne(DisputeTypes::className(), ['type_id' => 'transaction_dispute_type'])
            ->select('type_name')
            ->one()->type_name;
    }
    
    private function getStudentName()
    {
        return $this->hasOne(PaymentInformations::className(), ['payment_user_id' => 'user_id'])
            ->select('`payment_first_name`, `payment_last_name`')
            ->one();
    }
    
    public static function getRevenue()
    {
        $gross = 0;
        $net = 0;
        $users = Users::find()->all();
    
        foreach ($users as $val) {
            $transac = (int)TransactionHistory::find()
                ->where("`class_id` IN (SELECT `class_id` FROM `classes` WHERE `class_trainer_id`=:user_id)", ['user_id' => (int)$val['user_id']])
                ->sum('amount');

            if ($transac) {
                $net += $transac * (int)$val['user_fees'] / 100;
                $gross += $transac - $transac * (int)$val['user_fees'] / 100;
            }
        }
        return ['gross'=>$gross,'net'=>$net];
    }
    
    public static function getTrainersRevenue()
    {
        $gross = 0;
        $gusers = [];
        $users_ids = [];
        $date_start = date('j M Y', strtotime('- ' . (date('N') + 7) . ' days'));
        $date_end = date('j M Y', strtotime('- ' . date('N') . ' days'));
        $period_start = strtotime($date_start) + 180 * 60;
        $period_end = strtotime($date_end) + 180 * 60;
        
        $users = Users::find()->all();
    
        foreach ($users as $val) {
            $uid = (int)$val['user_id'];
            $subquery = (new \yii\db\Query)->from('classes')
                        ->select('class_id')
                        ->where(['class_trainer_id' => $uid])
                        ->andWhere('`class_etime` >= :start AND `class_etime` <= :end', [":start" => $period_start, ":end" => $period_end])
                        ->one()['class_id'];

            if (!$subquery) {
                $transac = (int)TransactionHistory::find()
                            ->where("`transaction_dispute_status` = 'declined' AND `transaction_dispute_time` >= :start AND `transaction_dispute_time` <= :end", [":start" => $period_start, ":end" => $period_end,])
                            ->sum('amount');
            } else {
                $transac = (int)TransactionHistory::find()
                            ->where("`class_id` IN ($subquery)")
                            ->andWhere(['transaction_dispute_status'=>'close'])
                            ->orWhere("`transaction_dispute_status` = 'declined' AND `transaction_dispute_time` >= :start AND `transaction_dispute_time` <= :end", [":start" => $period_start, ":end" => $period_end,])
                            ->sum('amount');
            }

            if ($transac) {
                $gross += $transac - $transac * (int)$val['user_fees'] / 100;
                @$users_ids[] = $uid;
                @$gusers[$uid] = $transac - $transac * (int)$val['user_fees'] / 100;
            }
        }
        
        $users = Users::find()->where(['in', 'user_id',$users_ids]);

        return [
            'gusers' => $gusers,
            'gross' => $gross,
            'users' => $users,
            'date_start' => $date_start,
            'date_end' => $date_end
            ];
    }
    
    public static function sendEmailStudentList($class)
    {
        $user_list =  Users::find()
            ->where("`user_id` IN (
                SELECT `user_id` FROM `transaction_history`
                WHERE `class_id` = $class->class_id AND `transaction_dispute_status` = 'open'
            )")->all();
        
        if ($user_list) {
            foreach ($user_list as $val) {
                $body = "Your dispute for the '$class->class_name' was declined. For more info please contact example@email.com";
                Yii::$app->mailer->compose()
                    ->setTo($val->user_email)
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setSubject("Your dispute was for the '$class->class_name' declined")
                    ->setTextBody($body)
                    ->send();
                
                Notification::addNotify($body, $val->user_id);
            }
        }
    }
    
    public static function setRefundDispute($class)
    {
        if (empty($class)) {
            throw new Exception("You already refunded for this class", 400);
        }
        
        $trans = self::find()->where(['class_id' => $class->class_id, 'transaction_dispute_status' => 'open'])->all();
        
        if ($trans) {
            foreach ($trans as $val) {
                $worldpay = new WorldpayHelper(self::$worldpayKey);

                // Sometimes your SSL doesnt validate locally
                // DONT USE IN PRODUCTION
                $worldpay->disableSSLCheck(true);

                try {
                    // Refund the order using the Worldpay order code
                    $worldpay->refundOrder($val->transation_id);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
                
                $transactionData = array(
                    "user_id" => $val->user_id,
                    "description" => "Refunded class $class->class_name",
                    "transation_id" => $val->transation_id,
                    "status" => 'REFUNDED',
                    "amount" => ($val->amount * (-1)),
                    "currency" => 'USD',
                    "class_id" => $class->class_id,
                    "transaction_refund" => 1,
                );

                $transactionHistoty = new TransactionHistory();
                $transactionHistoty->load($transactionData, '');
                if (!$transactionHistoty->save()) {
                    throw new Exception($e->getMessage());
                }
            }
        }
    }
}
