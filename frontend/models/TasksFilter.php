<?php


namespace frontend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for form "taskFilter".
 *
 * @property int[] $skills;
 * @property string[] $additional;
 * @property string $period;
 * @property string $search;
 */
class TasksFilter extends Model
{
    public $skills;
    public $additional;
    public $period;
    public $search;

    const SECONDS_IN_A_DAY = 24 * 60 * 60;

    const ALL = 0;
    const DAY = 1;
    const WEEK = 7;
    const MONTH = 30;

    const PERIODS = [
        self::ALL => 'За все время',
        self::DAY => 'За день',
        self::WEEK => 'За неделю',
        self::MONTH => 'За месяц',
    ];

    const withoutResponses = 'withoutResponses';
    const remoteWork = 'remoteWork';

    const ADDITIONAL = [
        self::withoutResponses => 'Без откликов',
        self::remoteWork => 'Удаленная работа'
    ];

    public static function getSkillsFields()
    {
        return ArrayHelper::map(Skill::find()->all(), 'id', 'name');
    }

    public static function getPeriodsFields()
    {
        return self::PERIODS;
    }

    public static function getAdditionalFields()
    {
        return self::ADDITIONAL;
    }

    public function rules()
    {
        return [
            [
                ['skills'],
                'exist',
                'allowArray' => true,
                'targetClass' => Skill::class,
                'targetAttribute' => 'id'
            ],
            [
                ['additional'],
                'safe'
            ],
            [
                ['period'],
                'in',
                'range' => array_keys(self::PERIODS)
            ],
            [
                ['search'],
                'string',
                'max' => 128
            ],
            [
                ['search'],
                'trim'
            ],
        ];
    }

    public function applyFilters($query)
    {
        if (!empty($this->skills)) {
            $query->where(['skill_id' => $this->skills]);
        }

        if (!empty($this->additional)) {
            if (in_array(self::withoutResponses, $this->additional)) {
//            TODO
            }
            if (in_array(self::remoteWork, $this->additional)) {
                $query->andWhere(['city_id' => null]);
            }
        }

        if ($this->period != self::ALL) {
            $query->andFilterWhere(['>=', 'created_at', $this->calculatePeriod($this->period)]);

        }

        if (isset($this->search)) {
            $query->andFilterWhere(['LIKE', 'title', $this->search]);
        }

        return $query;
    }

    private function calculatePeriod($period)
    {
        return date('Y-m-d H:i:s',
            time() - intval($period) * self::SECONDS_IN_A_DAY);
    }
}
