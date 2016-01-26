<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Notifications;
use yii\data\ActiveDataProvider;

class NotificationsController extends Controller
{

    public function actionIndex()
    {
        $query = Notifications::find()->where(['notification_type' => 'all']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
                'dataProvider' => $dataProvider,
        ]);
    }
}
