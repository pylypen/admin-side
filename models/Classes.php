<?php namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "classes".
 *
 * @property integer $class_id
 * @property string $class_name
 * @property string $class_desc
 * @property integer $class_stream
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $class_activity
 * @property string $class_level
 * @property integer $class_trainer_id
 * @property time $class_stime
 * @property time $class_etime
 * @property float $class_price
 * @property string $class_video_url
 * @property date $class_date
 * @property integer $class_period_time
 */
class Classes extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%classes}}';
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

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();
        $fields['class_trainer_name'] = function () {
            $trainer = $this->getTrainer()->toArray();
            return $trainer['user_type'] === "studio" ? $trainer['payment']['payment_bussines_name'] : $trainer['payment']['payment_first_name'] . " " . $trainer['payment']['payment_last_name'];
        };
        $fields['pay_count'] = function () {
            return (int)$this->getStudentCount();
        };
        $fields['activity_name'] = function () {
            return $this->getActivity();
        };
        $fields['dispute_count'] = function () {
            return $this->getDisputeCount();
        };
        
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'class_name', 'class_level', 'class_activity',
                    'class_stime', 'class_etime', 'class_date'
                ],
                'required'
            ],
            [
                [
                    'class_stream', 'class_trainer_id', 'class_activity', 'class_studio_trainer_id'
                ],
                'integer'
            ],
            [
                [
                    'class_name', 'class_level',
                    'class_video_url', 'class_period_time'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'class_name', 'class_desc', 'class_stream', 'class_activity',
                    'class_level', 'class_trainer_id', 'class_stime',
                    'class_etime', 'class_price',
                    'class_period_time', 'class_video_url', 'class_studio_trainer_id'
                ],
                'filter',
                'filter' => function ($value) {
                    return trim(strip_tags($value));
                }
            ]
        ];
    }
    
    public function getTrainer()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'class_trainer_id'])->one();
    }
    
    private function getStudentCount()
    {
        return $this->hasOne(TransactionHistory::className(), ['class_id' => 'class_id'])
            ->where(['class_id' => $this->class_id])
            ->andWhere("`transation_id` NOT IN (SELECT `transation_id` FROM `transaction_history` WHERE `class_id` = {$this->class_id} AND `status` = 'REFUNDED')")
            ->count('user_id');
    }

    
    private function getActivity()
    {
        return $this->hasOne(Actives::className(), ['actives_id' => 'class_activity'])
            ->select('actives_name')
            ->where(['actives_id' => $this->class_activity])
            ->one();
    }
    
    private function getDisputeCount()
    {
        return $this->hasOne(TransactionHistory::className(), ['class_id' => 'class_id'])
            ->where(['transaction_dispute_status' => 'open'])
            ->count();
    }


    public function toArray(array $fields = array(), array $expand = array(), $recursive = true)
    {
        $array = parent::toArray($fields, $expand, $recursive);
        return $array;
    }
    
    public static function getDisputeList()
    {
        return Classes::find()
            ->where("`class_id` IN (
                SELECT `class_id` FROM `transaction_history`
                WHERE `transaction_dispute_status` = 'open'
            )")
            ->orderBy('`class_stime` DESC');
    }
}
