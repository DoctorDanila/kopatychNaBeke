<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for table "subscriptions".
 *
 * @property int $id
 * @property string $issueDate
 * @property string|null $returnDate
 * @property string $status
 * @property int|null $reader
 * @property int|null $book
 *
 * @property Books $book0
 * @property Readers $reader0
 */
class Subscriptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscriptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['issueDate'], 'required'],
            [['issueDate', 'returnDate'], 'safe'],
            [['issueDate', 'returnDate'], 'date', 'format' => 'php:Y-m-d\TH:i:s.v\Z'],
            [['reader', 'book'], 'integer'],
            [['status'], 'string', 'max' => 16],
            [['reader'], 'exist', 'skipOnError' => true, 'targetClass' => Readers::class, 'targetAttribute' => ['reader' => 'id']],
            [['book'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book' => 'id']],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['book'],$fields['reader']);
        return $fields;
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields["book"] = function () {
            return $this->getBook()->one();
        };
        $extraFields["reader"] = function () {
            return $this->getReader()->one();
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
            'issueDate' => 'Issue Date',
            'returnDate' => 'Return Date',
            'status' => 'Status',
            'reader' => 'Reader',
            'book' => 'Book',
        ];
    }

    /**
     * Gets query for [[Book0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook0()
    {
        return $this->hasOne(Books::class, ['id' => 'book']);
    }

    /**
     * Gets query for [[Reader0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReader0()
    {
        return $this->hasOne(Readers::class, ['id' => 'reader']);
    }
}
