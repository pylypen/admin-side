<?php namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\Response;
use Yii;
use yii\web\ForbiddenHttpException;
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
class AdminData extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_data}}';
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
            [['admin_data_param', 'admin_data_value'], 'required'],
            [['admin_data_id'], 'integer'],
            [['admin_data_value', 'admin_data_param'], 'string'],
            [
                [
                    'admin_data_param',
                    'admin_data_value'
                ],
                'filter', 'filter' => function ($value) {
                    return trim(strip_tags($value));
                }
            ]
        ];
    }
    
    public static function getSumStatus()
    {
        return self::find()->where(['admin_data_param' => 'max_sum_for_silver'])
            ->orWhere(['admin_data_param' => 'max_sum_for_gold'])->all();
    }
}
