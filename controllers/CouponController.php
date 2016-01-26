<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Coupons;
use yii\data\ActiveDataProvider;

class CouponController extends Controller
{
    public function actionIndex()
    {
        $data = Coupons::find()->where(['>', 'coupon_expire_time', time()]);
        $get = Yii::$app->request->get();
        
        if (!empty($get)) {
            Coupons::setNewCoupon($get);
            $this->redirect('/coupon');
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $data,
        ]);
        
        return $this->render('index', [
                'dataProvider' => $dataProvider
        ]);
    }
}
