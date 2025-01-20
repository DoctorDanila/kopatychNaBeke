<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for table "readers".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 *
 * @property Subscriptions[] $subscriptions
 */
class Readers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'readers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'email'], 'required'],
            [['name'], 'string', 'max' => 32],
            [['surname'], 'string', 'max' => 64],
            [['email'], 'string', 'max' => 128],
            [['email'], 'unique'],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields["subscription"] = function () {
            return $this->getSubscriptions()->one();
        };
        return $extraFields;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'email' => 'Email',
        ];
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscriptions::class, ['reader' => 'id']);
    }
}
