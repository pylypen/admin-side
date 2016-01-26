<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\DisputeTypes;
use app\models\TransactionHistory;
use app\models\Classes;

class DisputeController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionTypes()
    {
        $this->checkAccess('admin_staff');
        
        $dataProvider = new ActiveDataProvider([
            'query' => DisputeTypes::find()
        ]);

        return $this->render('types', [
                'dataProvider' => $dataProvider
        ]);
    }
    
    public function actionList()
    {
        $this->checkAccess('admin_staff');
        
        $dataProvider = new ActiveDataProvider([
            'query' => Classes::getDisputeList()
        ]);
        
        return $this->render('list', [
                'dataProvider' => $dataProvider
        ]);
    }
    
    public function actionCreateDisputeType()
    {
        $this->checkAccess('admin_staff');
        
        $model = new DisputeTypes();

        if ($model->load(Yii::$app->request->get(), '') && $model->save()) {
            return $this->redirect('/dispute/types');
        }
        
        return $this->redirect('/dispute/types?notset');
    }
    
    public function actionDeleteDisputeType($id)
    {
        $this->checkAccess('admin_staff');
        
        $model = DisputeTypes::findOne((int)$id);

        if ($model) {
            $model->delete();
        }
        
        return $this->redirect('/dispute/types?notset');
    }
    
    public function actionUpdateType($id)
    {
        $this->checkAccess('admin_staff');
        
        $model = DisputeTypes::findOne((int)$id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('/dispute/types');
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }
    
    public function actionUpdateList($id)
    {
        $this->checkAccess('admin_staff');
        
        $post = Yii::$app->request->post();
        $model = Classes::findOne((int)$id);
        $dataProvider = new ActiveDataProvider([
            'query' => TransactionHistory::find()->where([
                    'class_id' => $id,
                    'transaction_dispute_status' => 'open'
            ])
        ]);
        
        if (!empty($post)) {
            switch ($post['Classes']['class_name']) {
                case 'approved':
                    TransactionHistory::setRefundDispute($model);
                    TransactionHistory::sendEmailStudentList($model);
                    break;
            }
            
            TransactionHistory::updateAll(
                ['transaction_dispute_status' => $post['Classes']['class_name']],
                ['class_id' => $id, 'transaction_dispute_status' => 'open']
            );
            
            return $this->redirect('/dispute/list');
        } else {
            return $this->render('update-list', [
                    'model' => $model,
                    'dataProvider' => $dataProvider
            ]);
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
