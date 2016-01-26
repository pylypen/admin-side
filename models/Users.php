<?php namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Users extends ActiveRecord implements IdentityInterface
{

    const STATUS_DELETED = 'deleted';
    const STATUS_DISABLED = 'disabled';
    const STATUS_ACTIVE = 'active';
    const STATUS_RE_APPROVE = 're-approve';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
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
    public function rules()
    {
        return [
            [['user_email', 'user_password_hash', 'user_zip', 'user_city', 'user_state'], 'required'],
            [
                [
                    'user_email',
                    'user_password_hash',
                    'user_status',
                    'user_address',
                    'user_apt_billing',
                    'user_zip',
                    'user_city',
                    'user_fb_id',
                    'user_twitter_id',
                    'stream_username',
                    'stream_password',
                ], 'string'
            ],
            [
                [
                    'user_email',
                    'user_password_hash',
                    'user_address',
                    'user_apt_billing',
                    'user_zip',
                    'user_city',
                    'user_fb_id',
                    'user_twitter_id'
                ], 'string', 'max' => 255
            ],
            [['user_id', 'user_country', 'user_state', 'user_twitter_id', 'stream_userid'], 'integer'],
            ['user_password_hash', 'string', 'min' => 5],
            [['user_email', 'user_fb_id', 'user_twitter_id'], 'unique'],
            [['user_email'], 'email'],
            ['user_status', 'default', 'value' => self::STATUS_ACTIVE],
            ['user_status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_DISABLED, self::STATUS_RE_APPROVE]],
            ['user_type', 'default', 'value' => 'student'],
            ['user_type', 'in', 'range' => ['student', 'trainer', 'studio']],
            [['user_auth_key'], 'string', 'max' => 32],
            ['user_fees', 'match', 'pattern'=>'/^[0-9]{1,2}(\.[0-9]{0,2})?$/'],
            [
                [
                    'user_email',
                    'user_type',
                    'user_address',
                    'user_apt_billing',
                    'user_country',
                    'user_zip',
                    'user_city',
                    'user_state',
                    'user_fb_id',
                    'user_twitter_id',
                    'user_fees',
                    'created_at',
                    'updated_at'
                ],
                'filter', 'filter' => function ($value) {
                    return trim(strip_tags($value));
                }
            ]
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();
        $fields['bookings_count'] = function () {
            return $this->getBookingsCount();
        };
        $fields[] = 'payment';
//        $fields['payment'] = function () {
//            return $this->getPayment();
//        };
        $fields['active_lead'] = function () {
            return $this->getDataForFields('payment_active_lead');
        };
        $fields['clientele'] = function () {
            return $this->getDataForFields('payment_clientele');
        };
        $fields['certifications'] = function () {
            return $this->getDataForFields('payment_certifications');
        };
        $fields['profession'] = function () {
            return $this->getDataForFields('payment_profession');
        };
        
        return $fields;
    }
    
    private function getDataForFields($select)
    {
        switch ($select) {
            case 'payment_active_lead':
                $db = 'actives';
                $select_db = 'actives_name';
                $select_db_id = 'actives_id';
                break;
            case 'payment_clientele':
                $db = 'clienteles';
                $select_db = 'clientele_name';
                $select_db_id = 'clientele_id';
                break;
            case 'payment_certifications':
                $db = 'certifications';
                $select_db = 'certifications_name';
                $select_db_id = 'certifications_id';
                break;
            case 'payment_profession':
                $db = 'professions';
                $select_db = 'profession_name';
                $select_db_id = 'profession_id';
                break;
            default:
                return false;
        }
        
        $return = [];
        $subquery = (new \yii\db\Query)->from('payment_informations')
                    ->select($select)
                    ->where(['payment_user_id' => $this->user_id])
                    ->one()[$select];
        
        if (!$subquery) {
            return false;
        }
        
        $query = (new \yii\db\Query)->from($db)->select($select_db)
                    ->where("$select_db_id IN ($subquery)")->all();

        foreach ($query as $val) {
            @$return[] = $val[$select_db];
        }

        return implode(', ', $return);
    }
    
    private function getBookingsCount()
    {
        return TransactionHistory::find()
            ->where("`class_id` IN (SELECT `class_id` FROM `classes` WHERE `class_trainer_id` = {$this->user_id})")
            ->andWhere("`status` != 'REFUNDED'")
            ->count();
    }
    
    public function getPayment()
    {
        if ($this->user_type !== 'student') {
            return $this->hasOne(ApproveInformations::className(), ['payment_user_id' => 'user_id']);
        } else {
            return $this->hasOne(PaymentInformations::className(), ['payment_user_id' => 'user_id']);
        }
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['user_auth_key' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['user_email' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
                'user_password_reset_token' => $token,
                'user_status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function findExistPasswordResetToken($token)
    {
        return static::findOne([
                'user_password_reset_token' => $token,
                'user_status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->user_auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function getUserRole()
    {
        return $this->user_type;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->user_password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->user_auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->user_password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->user_password_reset_token = null;
    }

    /**
     * Generates email for new user
     */
    public function sendEmailToNewUser($auth)
    {
        $message = \Yii::$app->mailer->compose('userCreate', ['auth' => $auth]);
        $message->setFrom([\Yii::$app->params['supportEmail']]);
        $message->setTo($this->user_email);
        $message->setSubject("Account created");
        return $message->send();
    }

    public function getUserInfo($id)
    {
        $data = array();
        $modelPayment = new ApproveInformations;
        @$data['payment'] = $modelPayment::findOne(['payment_user_id' => $id]);

        return $data;
    }

    public function getUserData($id)
    {
        $data = array();
        $modelPayment = new PaymentInformations;
        @$data['payment'] = $modelPayment::findOne(['payment_user_id' => $id]);

        $modelEducation = new UserEducations;
        @$data['education'] = $modelEducation::findAll(['useredu_user_id' => $id]);

        $modelCredentials = new Credentials;
        @$data['credentials'] = $modelCredentials::findAll(['credentials_user_id' => $id]);

        $modelReferences = new References;
        @$data['references'] = $modelReferences::findAll(['references_user_id' => $id]);

        return $data;
    }
    
    public function toArray(array $fields = array(), array $expand = array(), $recursive = true)
    {
        $array = parent::toArray($fields, $expand, $recursive);
        unset($array['user_auth_key']);
        unset($array['user_password_hash']);
        unset($array['user_password_reset_token']);
        unset($array['stream_userid']);
        unset($array['stream_username']);
        unset($array['stream_password']);
        
        return array_merge($array, $this->getUserInfo($this->id));
    }

    public function savePersonal($post, $user)
    {
        $modelPayment = new PaymentInformations;
        $uinfo = $modelPayment::findOne(['payment_user_id' => $user->user_id]);

        $uinfo->load($post, '');
        $uinfo->save();
    }
    
    public function getDisabledUsers()
    {
        return static::find()->where(['user_status' => 'disabled'])
                        ->orWhere(['user_status' => 're-approve'])
                        ->orderBy('created_at DESC');
    }
    
    public static function setApproveUser($id)
    {
        $modelSave = [];
        $modelPaymentSave = PaymentInformations::findOne(['payment_user_id' => (int)$id]);
        $modelPayment = ApproveInformations::findOne(['payment_user_id' => (int)$id]);
        
        if (empty($modelPaymentSave)) {
            $modelPaymentSave = new PaymentInformations;
        }
        
        foreach ($modelPayment as $key => $val) {
            $modelSave[$key] = $val;
        }
        
        $modelPaymentSave->load($modelSave, '');
        $modelPaymentSave->save();
        
        $model = Users::find()->where(['user_id' => (int)$id])->one();
        $model->user_status = 'active';
        $model->save();
    }
}
