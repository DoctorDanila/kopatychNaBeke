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
 * @property Book $book0
 * @property Reader $reader0
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
            [['reader', 'book'], 'integer'],
            [['status'], 'string', 'max' => 16],
            [['reader'], 'exist', 'skipOnError' => true, 'targetClass' => Reader::class, 'targetAttribute' => ['reader' => 'id']],
            [['book'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['book' => 'id']],
        ];
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
        return $this->hasOne(Book::class, ['id' => 'book']);
    }

    /**
     * Gets query for [[Reader0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReader0()
    {
        return $this->hasOne(Reader::class, ['id' => 'reader']);
    }
}
