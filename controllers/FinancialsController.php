<?php namespace app\controllers;

use Yii;
use app\models\TransactionHistory;
use app\models\Classes;
use app\models\Actives;
use app\models\Users;
use app\models\AdminData;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class FinancialsController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    public function actions()
    {
        return [
            'editable' => [
                'modelClass' => TransactionHistory::className(),
                'forceCreate' => false,
            ]
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        if (!empty(Yii::$app->request->get()) && Yii::$app->user->identity->user_superadmin) {
            foreach (Yii::$app->request->get() as $k => $v) {
                $model = AdminData::findOne(['admin_data_param' => $k]);
                $model->admin_data_value = $v;
                $model->save();
            }
            $this->redirect('/financials');
        }
        
        $sum_status = AdminData::getSumStatus();
        $data = TransactionHistory::getRevenue();
        
        return $this->render('index', [
                'data' => $data,
                'sum_status' => $sum_status
        ]);
    }
    
    public function actionTrainersRevenue()
    {
        $data = TransactionHistory::getTrainersRevenue();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $data['users'],
        ]);
        
        unset($data['users']);
        
        return $this->render('trainers-revenue', [
                'dataProvider' => $dataProvider,
                'data' => $data,
        ]);
    }
    
    public function actionBookedNumber()
    {
        $data = Classes::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $data,
        ]);

        return $this->render('booked_number', [
                'data' => $dataProvider,
        ]);
    }
    
    public function actionBookedNumberPdf()
    {
        $this->layout = 'main-login';
        $pdf = Yii::$app->pdf;

        $data = Classes::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $data,
            'totalCount' => 100,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => false
        ]);

        $pdf->content = $this->render('booked_number_pdf', [
                'data' => $dataProvider,
        ]);

        return $pdf->render();
    }
    
    public function actionActivityBookings()
    {
        $data = Actives::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $data,
        ]);

        return $this->render('activity_bookings', [
                'data' => $dataProvider,
        ]);
    }
    
    public function actionActivityBookingsPdf()
    {
        $this->layout = 'main-login';
        $pdf = Yii::$app->pdf;

        $data = Actives::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $data,
            'pagination' => false,
            'sort' => false
        ]);

        $pdf->content = $this->render('activity_bookings_pdf', [
            'data' => $dataProvider,
        ]);

        return $pdf->render();
    }
    
    public function actionTrainersBookings()
    {
        $data = Users::find()->where("`user_type` != 'student'");

        $dataProvider = new ActiveDataProvider([
            'query' => $data,
        ]);

        return $this->render('trainer_bookings', [
                'data' => $dataProvider,
        ]);
    }
    
    public function actionTrainersBookingsPdf()
    {
        $this->layout = 'main-login';
        $pdf = Yii::$app->pdf;

        $data = Users::find()->where("`user_type` != 'student'");

        $dataProvider = new ActiveDataProvider([
            'query' => $data,
            'pagination' => false,
            'sort' => false
        ]);

        $pdf->content = $this->render('trainer_bookings_pdf', [
            'data' => $dataProvider,
        ]);

        return $pdf->render();
    }
    
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
