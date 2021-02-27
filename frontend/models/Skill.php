<?php

namespace frontend\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "skill".
 *
 * @property int $id
 * @property string $name
 * @property string|null $icon
 *
 * @property Task[] $tasks
 * @property UserHasSkill[] $userHasSkills
 * @property User[] $users
 */
class Skill extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'skill';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name', 'icon'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'icon' => 'Icon',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(Task::className(), ['skill_id' => 'id']);
    }

    /**
     * Gets query for [[UserHasSkills]].
     *
     * @return ActiveQuery
     */
    public function getUserHasSkills(): ActiveQuery
    {
        return $this->hasMany(UserHasSkill::className(), ['skill_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_has_skill', ['skill_id' => 'id']);
    }

    /**
     * Gets fields for filter form.
     *
     * @return array
     */
    public static function getFormFields() : array
    {
        return ArrayHelper::map(Skill::find()->all(), 'id', 'name');
    }


}
