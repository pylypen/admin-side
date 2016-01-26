<?php namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_users}}';
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
                [
                    [
                        'user_email',
                        'user_password_hash',
                        'user_instructors_edit',
                        'user_students_edit',
                        'user_classes_edit',
                        'user_approved_classes',
                        'user_approved_blogs',
                        'user_approved_trainers',
                        'user_instructors_earnings',
                        'user_instructors_invoices',
                        'user_payouts',
                        'user_export_emails'
                    ], 'required'
                ],
                [['user_email', 'user_password_hash'], 'string'],
                [['user_email', 'user_password_hash'], 'string', 'max' => 255],
                [['user_id'], 'integer'],
                ['user_password_hash', 'string', 'min' => 5],
                [['user_email'], 'unique'],
                [['user_email'], 'email'],
                [['user_auth_key'], 'string', 'max' => 32],
                ['user_instructors_edit', 'in', 'range' => ['read', 'edit']],
                ['user_students_edit', 'in', 'range' => ['read', 'edit']],
                ['user_classes_edit', 'in', 'range' => ['read', 'edit']],
                ['user_approved_classes', 'in', 'range' => ['disallow', 'approve']],
                ['user_approved_blogs', 'in', 'range' => ['disallow', 'approve']],
                ['user_approved_trainers', 'in', 'range' => ['disallow', 'approve']],
                ['user_instructors_earnings', 'in', 'range' => ['disallow', 'read']],
                ['user_instructors_invoices', 'in', 'range' => ['disallow', 'read']],
                ['user_payouts', 'in', 'range' => ['disallow', 'read']],
                ['user_export_emails', 'in', 'range' => ['disallow', 'read']],
                [
                    [
                        'user_email',
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
        return $fields;
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateAuthKey();
        }

        if (!empty($this->user_password_hash)) {
            $this->user_password_hash = $this->setPassword($this->user_password_hash);
        }

        parent::beforeSave($insert);
        return true;
    }
 
    public function getEnumList($type)
    {
        switch ($type) {
            case 'user_instructors_edit':
            case 'user_students_edit':
            case 'user_classes_edit':
                $list = ['read'=>'read', 'edit'=>'edit'];
                break;
            case 'user_approved_classes':
            case 'user_approved_blogs':
            case 'user_approved_trainers':
                $list = ['disallow'=>'disallow', 'approve'=>'approve'];
                break;
            case 'user_instructors_earnings':
            case 'user_instructors_invoices':
            case 'user_payouts':
            case 'user_export_emails':
                $list = ['disallow'=>'disallow', 'read'=>'read'];
                break;
            default:
                $list = [];
        }
    
        return $list;
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
    
    public function toArray(array $fields = array(), array $expand = array(), $recursive = true)
    {
        $array = parent::toArray($fields, $expand, $recursive);
        unset($array['user_auth_key']);
        unset($array['user_password_hash']);
        unset($array['user_password_reset_token']);
        
        return $array;
    }

    public function savePersonal($post, $user)
    {
        $modelPayment = new PaymentInformations;
        $uinfo = $modelPayment::findOne(['payment_user_id' => $user->user_id]);

        $uinfo->load($post, '');
        $uinfo->save();
    }
}
