<?php namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Users;
use yii\data\ActiveDataProvider;

class ExportEmailsController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionActiveStudents()
    {
        $query = Users::find()
                    ->where(['user_type' => 'student'])
                    ->andWhere("`user_login_time` > ".strtotime('-3 month'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
                'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionActiveInstructors()
    {
        $query = Users::find()
                    ->where("`user_type` != 'student'")
                    ->andWhere("`user_login_time` > ".strtotime('-3 month'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
                'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionInactiveStudents()
    {
        $query = Users::find()
                    ->where(['user_type' => 'student'])
                    ->andWhere("`user_login_time` < ".strtotime('-3 month'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
                'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionInactiveInstructors()
    {
        $query = Users::find()
                    ->where("`user_type` != 'student'")
                    ->andWhere("`user_login_time` < ".strtotime('-3 month'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
                'dataProvider' => $dataProvider,
        ]);
    }
}
