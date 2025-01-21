<?php

namespace app\modules\v2\models;

use Yii;

/**
 * This is the model class for table "projects".
 *
 * @property int $id
 * @property int|null $resume_id
 * @property int|null $candidate_id
 * @property string $company_name
 * @property string $post
 * @property string|null $description
 * @property string $start_date
 * @property string|null $end_date
 *
 * @property Candidate $candidate
 * @property Resume $resume
 */
class Projects extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'projects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resume_id', 'candidate_id'], 'integer'],
            [['company_name', 'post', 'start_date'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['company_name'], 'string', 'max' => 64],
            [['post'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 255],
            [['candidate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Candidate::class, 'targetAttribute' => ['candidate_id' => 'id']],
            [['resume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resume::class, 'targetAttribute' => ['resume_id' => 'id']],
        ];
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields["candidate"] = function () {
            return $this->getCandidate()->one();
        };
        $extraFields["resume"] = function () {
            return $this->getResume()->one();
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
            'resume_id' => 'Resume ID',
            'candidate_id' => 'Candidate ID',
            'company_name' => 'Company Name',
            'post' => 'Post',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
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
     * Gets query for [[Resume]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResume()
    {
        return $this->hasOne(Resume::class, ['id' => 'resume_id']);
    }
}
