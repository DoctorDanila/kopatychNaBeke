<?php

namespace app\modules\v2\models;

use Yii;

/**
 * This is the model class for table "resume".
 *
 * @property int $id
 * @property int|null $candidate_id
 * @property string $title
 * @property string $profession
 * @property string|null $description
 * @property int|null $work_exp_month
 * @property string $work_format
 * @property string $stack
 * @property string|null $status
 * @property string $creation_date
 *
 * @property Candidate $candidate
 * @property Projects[] $projects
 */
class Resume extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resume';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['candidate_id', 'work_exp_month'], 'integer'],
            [['title', 'profession', 'work_format', 'stack', 'creation_date'], 'required'],
            [['creation_date'], 'safe'],
            [['title'], 'string', 'max' => 64],
            [['profession', 'work_format'], 'string', 'max' => 32],
            [['description', 'stack'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 16],
            [['candidate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Candidate::class, 'targetAttribute' => ['candidate_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'candidate_id' => 'Candidate ID',
            'title' => 'Title',
            'profession' => 'Profession',
            'description' => 'Description',
            'work_exp_month' => 'Work Exp Month',
            'work_format' => 'Work Format',
            'stack' => 'Stack',
            'status' => 'Status',
            'creation_date' => 'Creation Date',
        ];
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields["candidate"] = function () {
            return $this->getCandidate()->one();
        };
        $extraFields["project"] = function () {
            return $this->getProjects()->all();
        };
        return $extraFields;
    }


    /**
     * Gets query for [[Candidate]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCandidate()
    {
        return $this->hasOne(Candidate::class, ['id' => 'candidate_id']);
    }

    /**
     * Gets query for [[Projects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Projects::class, ['resume_id' => 'id']);
    }
}
