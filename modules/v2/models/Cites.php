<?php

namespace app\modules\v2\models;

use Yii;

/**
 * This is the model class for table "cites".
 *
 * @property int $id
 * @property string $name
 *
 * @property Candidate[] $candidates
 * @property Stuff[] $stuffs
 */
class Cites extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cites';
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
        $extraFields["candidate"] = function () {
            return $this->getCandidates()->one();
        };
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
     * Gets query for [[Candidates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCandidates()
    {
        return $this->hasMany(Candidate::class, ['city_id' => 'id']);
    }

    /**
     * Gets query for [[Stuffs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStuffs()
    {
        return $this->hasMany(Stuff::class, ['city_id' => 'id']);
    }
}
