<?php

namespace app\components\controllers;

use yii\rest\ActiveController;

class BaseAPIController extends ActiveController
{
    public $serializer = [
        'class'=>'yii\rest\Serializer',
        'collectionEnvelope'=>'data',
    ];

    public function checkAccess($action, $model = null, $params = [])
    {
//        parent::checkAccess($action, $model, $params);
        return true;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class'=>\yii\filters\ContentNegotiator::class,
            'formatParam'=>'_format',
            'formats'=> [
                'application/json'=>\yii\web\Response::FORMAT_JSON,
                'xml'=>\yii\web\Response::FORMAT_XML,
            ],
        ];
        return $behaviors;
    }
}