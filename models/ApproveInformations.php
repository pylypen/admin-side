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
class ApproveInformations extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%approve_informations}}';
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
    
    public function beforeSave($insert)
    {
        if (strpos($this->payment_image, ";base64") !== false) {
            $types = array(
                'image/jpg' => 'jpg',
                'image/jpeg' => 'jpeg',
                'image/png' => 'png',
                'image/gif' => 'gif'
            );
            list($type, $data) = explode(';', $this->payment_image);
            list(, $data) = explode(',', $data);
            $imageData = base64_decode($data);

            $pathToSave = "/uploads/" . substr(md5(time() + date("r")), rand(0, 3) * 8, 8) . "." . $types[explode(":", $type)[1]];
            file_put_contents(__DIR__ . "/../../rest/web/" . $pathToSave, $imageData);
            $this->payment_image = $pathToSave;
        }

        return parent::beforeSave($insert);
    }
    
    public function toArray(array $fields = array(), array $expand = array(), $recursive = true)
    {
        $array = parent::toArray($fields, $expand, $recursive);
//        $array['blog_text'] = nl2br($array['blog_text']);
        if (strpos($array['payment_image'], 'uploads') !== false && strpos($array['payment_image'], 'http:') === false) {
            $array['payment_image'] = Yii::$app->request->getHostInfo() . $array['payment_image'];
        }
        return $array;
    }
}
