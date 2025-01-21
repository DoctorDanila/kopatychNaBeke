<?php

namespace app\modules\v2\controllers;

use app\components\controllers\BaseAPIController;
use app\modules\v2\models\Resume;
use app\modules\v2\models\Stuff;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class StuffController extends BaseAPIController
{
    public $modelClass = Stuff::class;

    public function actions(){
        $actions = parent::actions();
//        $actions['create'] = 'create';
//        unset($actions['update']);
//        unset($actions['delete']);
        unset($actions['index']);
//        $actions['login'] = [
//            'modelClass' => $this->modelClass,
//            'class'=>'app\modules\v2\actions\LoginAction'
//        ];
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

    public function actionLogin() {

        if ($stuff = Stuff::findByLogin(Yii::$app->request->post('login')) and $stuff->validatePassword(Yii::$app->request->post('password'))) {
            $response = [
                'id'=> $stuff->id,
                'login'=> $stuff->login,
                'token'=>$stuff->token,
            ];
            return $response;
        }
        return Stuff::findByLogin(Yii::$app->request->post('login')) and $stuff->validatePassword(Yii::$app->request->post('password'));
    }

    public function actionUpdate($id)
    {
        $model = Stuff::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Stuff with id $id not found");
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
        $model = Stuff::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Stuff with id $id not found");
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