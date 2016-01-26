<?php namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "gallery".
 *
 * @property integer $image_id
 * @property string $image_base64
 * @property string $image_desc
 * @property integer $trainer_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class DisputeTypes extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dispute_types}}';
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
            [['type_name'], 'required'],
            [['type_id',], 'integer'],
            [['type_name'], 'string'],
            [['type_name'], 'filter', 'filter' => function ($value) {
                return trim(strip_tags($value));
            }]
        ];
    }
}
