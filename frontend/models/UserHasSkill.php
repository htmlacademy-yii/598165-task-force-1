<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_has_skill".
 *
 * @property int $user_id
 * @property int $skill_id
 *
 * @property User $user
 * @property Skill $skill
 */
class UserHasSkill extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user_has_skill';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'skill_id'], 'required'],
            [['user_id', 'skill_id'], 'integer'],
            [['user_id', 'skill_id'], 'unique', 'targetAttribute' => ['user_id', 'skill_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['skill_id'], 'exist', 'skipOnError' => true, 'targetClass' => Skill::className(), 'targetAttribute' => ['skill_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'user_id' => 'User ID',
            'skill_id' => 'Skill ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Skill]].
     *
     * @return ActiveQuery
     */
    public function getSkill(): ActiveQuery
    {
        return $this->hasOne(Skill::className(), ['id' => 'skill_id']);
    }
}
