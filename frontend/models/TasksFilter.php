<?php


namespace frontend\models;


use yii\base\Model;
use yii\db\ActiveQuery;
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
    public ?array $skills = [];
    public ?array $additional = [];
    public string $period = '';
    public string $search = '';

    const SECONDS_IN_A_DAY = 86400;

    const PERIOD_ALL = 0;
    const PERIOD_DAY = 1;
    const PERIOD_WEEK = 7;
    const PERIOD_MONTH = 30;

    const PERIODS = [
        self::PERIOD_ALL => 'За все время',
        self::PERIOD_DAY => 'За день',
        self::PERIOD_WEEK => 'За неделю',
        self::PERIOD_MONTH => 'За месяц',
    ];

    const ADDITIONAL_WITHOUT_RESPONSES = 'withoutResponses';
    const ADDITIONAL_REMOTE_WORK = 'remoteWork';

    const ADDITIONAL = [
        self::ADDITIONAL_WITHOUT_RESPONSES => 'Без откликов',
        self::ADDITIONAL_REMOTE_WORK => 'Удаленная работа'
    ];

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
                'in',
                'range' => array_keys(self::ADDITIONAL),
                'allowArray' => true,
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


    /**
     * Gets period fields.
     *
     * @return array
     */
    public static function getPeriodsFields(): array
    {
        return self::PERIODS;
    }


    /**
     * Gets additional fields.
     *
     * @return array
     */
    public static function getAdditionalFields(): array
    {
        return self::ADDITIONAL;
    }

    /**
     * Applies form filters.
     *
     * @param ActiveQuery $query
     * @return  ActiveQuery
     */
    public function applyFilters(ActiveQuery $query): ActiveQuery
    {
        if (!empty($this->skills)) {
            $query->andWhere(['skill_id' => $this->skills]);
        }

        if (!empty($this->additional)) {
            if (in_array(self::ADDITIONAL_WITHOUT_RESPONSES, $this->additional)) {
                $query->andWhere(['contractor_id' => null]);
            }
            if (in_array(self::ADDITIONAL_REMOTE_WORK, $this->additional)) {
                $query->andWhere(['city_id' => null]);
            }
        }

        if (intval($this->period) !== self::PERIOD_ALL) {
            $query->andFilterWhere(['>=', 'created_at', $this->calculatePeriod($this->period)]);

        }

        if (isset($this->search)) {
            $query->andFilterWhere(['LIKE', 'title', $this->search]);
        }

        return $query;
    }

    /**
     * Calculates time period.
     *
     * @param string $period
     * @return  string
     */
    private function calculatePeriod(string $period): string
    {
        return date('Y-m-d H:i:s',
            time() - intval($period) * self::SECONDS_IN_A_DAY);
    }
}
