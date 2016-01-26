<?php namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $text
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Notification extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notifications}}';
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
            [['user_id', 'text'], 'required'],
            ['user_id', 'integer'],
            [['title', 'text', 'status'], 'string'],
            ['status', 'in', 'range' => array('new', 'read')]
        ];
    }
    
    public static function addNotify($text, $user_id)
    {
        $notification = new Notification();
        $notification->load(
            array(
                "user_id" => $user_id,
                "title" => $text,
                "text" => $text,
                "status" => "new"
            ),
            ''
        );
        $notification->save();
    }
}
