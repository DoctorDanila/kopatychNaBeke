<?php

namespace app\modules\v2\controllers;

use app\components\controllers\BaseAPIController;
use app\modules\v2\models\Resume;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ResumeController extends BaseAPIController
{
    public $modelClass = Resume::class;

    public function actions(){
        $actions = parent::actions();
//        $actions['create'] = 'create';
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
        $model = Resume::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Resume with id $id not found");
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
        $model = Resume::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Resume with id $id not found");
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