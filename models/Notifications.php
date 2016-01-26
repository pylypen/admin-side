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
class Notifications extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_notifications}}';
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
            [['notification_text'], 'required'],
            [['notification_id'], 'integer'],
            [['notification_text'], 'string'],
            ['notification_type', 'in', 'range' => ['all', 'new_instructor']],
            [
                [
                    'notification_text',
                    'notification_type'
                ],
                'filter', 'filter' => function ($value) {
                    return trim(strip_tags($value));
                }
            ]
        ];
    }
}
