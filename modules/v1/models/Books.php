<?php

namespace app\modules\v1\models;

use Yii;
use \app\modules\v1\models\Subscriptions;
/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title
 * @property int $year
 *
 * @property Subscriptions[] $subscriptions
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'year'], 'required'],
            [['year'], 'integer'],
            [['title'], 'string', 'max' => 64],
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
            'title' => 'Title',
            'year' => 'Year',
        ];
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscriptions::class, ['book' => 'id']);
    }
}
