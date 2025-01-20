<?php

namespace app\modules\v1\controllers;

use app\components\controllers\BaseAPIController;
use Yii;
use yii\base\NotSupportedException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class SubscriptionController extends BaseAPIController
{
    public $modelClass = \app\modules\v1\models\Subscriptions::class;

    public function actions(){
        $actions = parent::actions();
        $actions['create'] = [
            'modelClass' => $this->modelClass,
            'class'=>'app\modules\v1\actions\CustomCreateAction'
        ];
//        unset($actions['update']);
//        unset($actions['delete']);
        unset($actions['index']);
        return $actions;
    }

    protected function verbs(){
        return [
            'create' => ['POST'],
            'update' => ['PUT','PATCH','POST'],
            'delete' => ['DELETE'],
            'index'=>['GET'],
        ];
    }

    public function actionUpdate($id)
    {
        $model = \app\modules\v1\models\Subscriptions::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Subscriptions with id $id not found");
        }

        $data = Yii::$app->getRequest()->getBodyParams();

        if ($model->load($data, '')) {
            if ($model->save()) {
                return $model;
            } else {
                \Yii::$app->response->statusCode = 400;
                return $model->errors;
            }
        } else {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'Data could not be loaded'];
        }
    }

    public function actionDelete($id)
    {
        $model = \app\modules\v1\models\Subscriptions::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Subscriptions with id $id not found");
        }

        if ($model->delete()) {
            return $model;
        } else {
            \Yii::$app->response->statusCode = 400;
            return $model->errors;
        }
    }

    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => $this->modelClass::find(),
            'pagination' => [
                'defaultPageSize' => \Yii::$app->request->get('limit', 20),
                'pageSizeLimit' => [1, 500],
            ],
        ]);
        return $activeData;
    }
}