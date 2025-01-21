<?php

namespace app\modules\v2\actions;

use Yii;
use yii\rest\CreateAction;

class LoginAction extends CreateAction
{
    public function run()
    {
        $model = new \app\modules\v2\models\Stuff();

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