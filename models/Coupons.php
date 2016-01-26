<?php namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

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
class Coupons extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupons}}';
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
            [['coupon_token', 'coupon_percent', 'coupon_expire_time'], 'required'],
            [['coupon_percent', 'coupon_expire_time'], 'integer'],
            [['coupon_token'], 'string'],
            [
                [
                    'coupon_token',
                    'coupon_percent',
                    'coupon_expire_time'
                ],
                'filter', 'filter' => function ($value) {
                    return trim(strip_tags($value));
                }
            ]
        ];
    }
    
    public static function setNewCoupon($data)
    {
        $data['exp_date'] = strtotime($data['exp_date']);
        $data['set_percent'] = (int)$data['set_percent'];
        
        if ($data['exp_date'] > time() && $data['set_percent'] <= 100) {
            $model = new Coupons();
            $model->coupon_token = md5($data['exp_date'] . $data['set_percent'] . time());
            $model->coupon_expire_time = $data['exp_date'];
            $model->coupon_percent = $data['set_percent'];
            $model->insert();
        }
    }
}
