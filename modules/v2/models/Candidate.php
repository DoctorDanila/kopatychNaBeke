<?php

namespace app\modules\v2\models;

use Yii;

/**
 * This is the model class for table "candidates".
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string|null $patronymic
 * @property string $dob
 * @property string $phone
 * @property string $tg
 * @property int|null $city_id
 * @property string $photo
 *
 * @property Cites $city
 * @property Projects[] $projects
 * @property Resume[] $resumes
 */
class Candidate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'candidates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['surname', 'name', 'dob', 'phone', 'tg',], 'required'],
            [['dob'], 'safe'],
            [['city_id'], 'integer'],
            [['surname', 'name', 'patronymic', 'tg'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 16],
            [['phone', 'tg'], 'unique', 'targetAttribute' => ['phone', 'tg']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cites::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields["city"] = function () {
            return $this->getCity()->one();
        };
        $extraFields["project"] = function () {
            return $this->getProjects()->one();
        };
        $extraFields["resume"] = function () {
            return $this->getResumes()->one();
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
            'surname' => 'Surname',
            'name' => 'Name',
            'patronymic' => 'Patronymic',
            'dob' => 'Dob',
            'phone' => 'Phone',
            'tg' => 'Tg',
            'city_id' => 'City ID',
        ];
    }

    public static function findByPhone($phone)
    {
        return self::find()->where(['phone' => $phone])->one();
    }


    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cites::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Projects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Projects::class, ['candidate_id' => 'id']);
    }

    /**
     * Gets query for [[Resumes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResumes()
    {
        return $this->hasMany(Resume::class, ['candidate_id' => 'id']);
    }
}
