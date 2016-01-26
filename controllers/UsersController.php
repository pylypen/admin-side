<?php namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Users;
use app\models\ApproveInformations;
use app\models\PaymentInformations;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    
    const CLIENT_URL = 'http://gymgecko.ypgym.net/';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    public function actions()
    {
        return [
            'editable' => [
                'modelClass' => Users::className(),
                'forceCreate' => false,
            ]
        ];
    }
    
    public function _construct()
    {
        if (empty(Yii::$app->user->identity)) {
            Yii::$app->user->logout();

            return $this->goHome();
        }
    }
    
    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = new \yii\db\Query;
        $query = $query->from('users')
            ->leftJoin('payment_informations', '`payment_informations`.`payment_user_id` = `users`.`user_id`')
            ->leftJoin('countries', '`countries`.`country_id` = `users`.`user_country`');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
                'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionAdminUsers()
    {
        $this->checkAccess('admin_staff');
        
        $query = User::find()->where(['user_superadmin' => false]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('admin_users', [
                'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionCreateAdminUsers()
    {
        $this->checkAccess('admin_staff');
        
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('/users/index/');
        } else {
            return $this->render('create-user-admin', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, 'users');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('/users/index/');
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }
    
    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateAdmin($id)
    {
        $this->checkAccess('admin_staff');
        
        $model = $this->findModel($id, 'admin_users');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('/users/admin-users');
        } else {
            return $this->render('update_admin', [
                    'model' => $model,
            ]);
        }
    }
    
    public function actionTrainer($id)
    {
        $model = Users::find()
                ->where(['user_id'=>(int)$id])
                ->andWhere("`user_type` != 'student'")
                ->one()->toArray();
        
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        switch ($model['user_status']) {
            case 're-approve':
                $u_approve = ApproveInformations::findOne(['payment_user_id' => (int)$id]);
                $u_info = PaymentInformations::findOne(['payment_user_id' => (int)$id]);
                
                foreach ($u_info as $key => $val) {
                    if (in_array($val, ['updated_at', 'created_at', 'payment_hear', 'payment_comments', 'payment_user_status', 'payment_user_color'])) {
                        break;
                    }
                    
                    @$key_ = $key.'_visible';
                    @$model[$key_] = ($u_approve->$key !== $val) ? true : false;
                }
                return $this->render('trainer_re_approve', [
                        'model' => $model,
                        'approve' => (Yii::$app->user->identity->user_superadmin || Yii::$app->user->identity->user_approved_trainers != 'disallow') && $model['user_status'] !== 'active',
                ]);
                break;
            default:
                return $this->render('trainer_user', [
                        'model' => $model,
                        'approve' => (Yii::$app->user->identity->user_superadmin || Yii::$app->user->identity->user_approved_trainers != 'disallow') && $model['user_status'] !== 'active',
                ]);
        }
    }
    
    public function actionStudent($id)
    {
        $model = Users::find()
                ->where(['user_id'=>(int)$id])
                ->andWhere(['user_type' => 'student'])
                ->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        return $this->render('student_user', [
                'model' => $model,
        ]);
    }
    
    public function actionApproveTrainer($id)
    {
        $this->checkAccess('approve_trainer');
        
        Users::setApproveUser($id);
        
        return $this->redirect('/users/approve-profiles');
    }
    
    public function actionApproveProfiles()
    {
        $this->checkAccess('approve_trainer');
        
        $model = new Users;
        
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getDisabledUsers()
        ]);
        
        return $this->render('approve-profiles', [
                'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionLoginByUser($id)
    {
        $access = $this->checkAccess('students_edit');
        if ($access) {
            $token = Yii::$app->security->generateRandomString() . '_' . time();
            $model = Users::findOne((int)$id);
            $model->user_password_reset_token = $token;
            $model->save();
            
            $this->redirect(self::CLIENT_URL.'auto_login/' . $token);
        } else {
            return $this->redirect('/users/student?id=' . (int)$id);
        }
        
    }

    protected function findModel($id, $type)
    {
        switch ($type) {
            case 'users':
                $model = Users::findOne((int)$id);
                break;
            case 'admin_users':
                $model = User::findOne((int)$id);
                break;
            default:
                $model = null;
        }

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function checkAccess($action)
    {
        if (empty(Yii::$app->user->identity)) {
            return $this->redirect('/');
        }
        switch ($action) {
            case 'admin_staff':
                if (!Yii::$app->user->identity->user_superadmin) {
                    return $this->redirect('/');
                }
                break;
            case 'approve_trainer':
                return (Yii::$app->user->identity->user_superadmin || Yii::$app->user->identity->user_approved_trainers != 'disallow') ? true : $this->redirect('/');
                break;
            case 'students_edit':
                return (Yii::$app->user->identity->user_superadmin || Yii::$app->user->identity->user_students_edit != 'read') ? true : false;
                break;
        }
    }
}
