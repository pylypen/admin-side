<?php namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_post".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 */
class PaymentInformations extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_informations}}';
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
            [
                [
                    'payment_user_id',
                    'payment_first_name',
                    'payment_last_name',
                    'payment_contact_fname',
                    'payment_contact_lname',
                    'payment_biography',
                    'payment_image',
                    'payment_zip',
                    'payment_bussines_name',
                    'payment_gender',
                    'payment_timezone',
                    'payment_phone',
                    'payment_profession',
                    'payment_year_exp',
                    'payment_active_lead',
                    'payment_clientele',
                    'payment_certifications',
                    'payment_hear',
                    'payment_comments',
                    'created_at',
                    'updated_at',
                    'payment_style',
                    'payment_weight',
                    'payment_height',
                    'payment_fitness',
                    'payment_strength',
                    'payment_video_title',
                    'payment_video_url',
                ],
                'filter',
                'filter' => function ($value) {
                    return trim(strip_tags($value));
                }
            ]
        ];
    }
}
