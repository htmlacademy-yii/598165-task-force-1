<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "favorite".
 *
 * @property int $id
 * @property int $user_id
 * @property int $favorite_id
 *
 * @property User $user
 * @property User $favorite
 */
class Favorite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favorite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'favorite_id'], 'required'],
            [['user_id', 'favorite_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['favorite_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['favorite_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'favorite_id' => 'Favorite ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Favorite]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorite()
    {
        return $this->hasOne(User::className(), ['id' => 'favorite_id']);
    }
}
