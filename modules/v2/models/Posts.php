<?php

namespace app\modules\v2\models;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property string $name
 *
 * @property Stuff[] $stuffs
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields["stuff"] = function () {
            return $this->getStuffs()->one();
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
        ];
    }

    /**
     * Gets query for [[Stuffs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStuffs()
    {
        return $this->hasMany(Stuff::class, ['post_id' => 'id']);
    }
}
