<?php

namespace app\modules\v1\actions;

use Yii;
use yii\rest\CreateAction;

class CustomCreateAction extends CreateAction
{
    public function run()
    {
        $model = new \app\modules\v1\models\Books();

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->validate()) {
            $model->save();
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            return $model;
        } else {
            return $model->errors;
        }
    }

}