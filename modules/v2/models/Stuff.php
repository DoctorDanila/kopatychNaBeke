<?php

namespace app\modules\v2\models;

use Yii;

/**
 * This is the model class for table "stuff".
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string|null $patronymic
 * @property string $dob
 * @property int|null $post_id
 * @property int|null $city_id
 * @property int $login
 * @property string $password
 * @property string $token
 *
 * @property Cites $city
 * @property Posts $post
 */
class Stuff extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stuff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['surname', 'name', 'dob', 'login', 'password'], 'required'],
            [['dob', 'token'], 'safe'],
            [['post_id', 'city_id',], 'integer'],
            [['surname', 'name', 'patronymic'], 'string', 'max' => 32],
            [["password", ], 'string', 'max' => 255],
            [['login', ], 'string', 'max' => 32],
            [['token'], 'string', 'max' => 255],
            [['login', 'token'], 'unique', 'targetAttribute' => ['login', 'token']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cites::class, 'targetAttribute' => ['city_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::class, 'targetAttribute' => ['post_id' => 'id']],
        ];
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
            'post_id' => 'Post ID',
            'city_id' => 'City ID',
            'login' => 'Login',
            'password' => 'Password',
            'token' => 'Token',
        ];
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields["city"] = function () {
            return $this->getCity()->one();
        };
        $extraFields["post"] = function () {
            return $this->getPost()->one();
        };
        return $extraFields;
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
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posts::class, ['id' => 'post_id']);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function generateAccessToken()
    {
        $this->token = Yii::$app->security->generateRandomString($length = 16);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public static function findByLogin($login): ?Stuff
    {
        return static::findOne(['login' => $login]);
    }
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @param $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Хеширование пароля, если он изменился
        if ($this->isNewRecord || empty($this->password)) {
            $this->setPassword($this->password);
        }

        // Генерация токена доступа
        $this->generateAccessToken();

        return true;
    }
}
