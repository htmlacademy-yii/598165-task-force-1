<?php


namespace frontend\models;


use TaskForce\models\TaskStatus;
use yii\db\ActiveQuery;

/**
 * This is the model class for sorting users list".
 *
 * @property string $currentSort;
 */
class UsersSorting
{

    const SORT_RATING = 'rating';
    const SORT_ORDERS = 'orders';
    const SORT_POPULARITY = 'popularity';

    const SORTS = [
        self::SORT_RATING => 'Рейтингу',
        self::SORT_ORDERS => 'Числу заказов',
        self::SORT_POPULARITY => 'Популярности',
    ];

    private string $currentSort = self::SORT_RATING;


    /**
     * Applies form filters.
     *
     * @param ActiveQuery $query
     * @param string $type
     * @return  ActiveQuery
     */
    public function applySorting(ActiveQuery $query, string $type) : ActiveQuery {
        $this->currentSort = $type;

        switch ($type) {
            case self::SORT_RATING:
                $query
                    ->select([
                        '{{user}}.*',
                        'AVG({{review.rating}}) AS userRating',
                        ])
                    ->join('LEFT JOIN', '{{review}}', '{{review}}.user_id = user.id')
                    ->groupBy('{{user}}.id')
                    ->orderBy(['userRating'=> SORT_DESC]);
                break;
            case self::SORT_ORDERS:
                $query
                    ->select([
                        '{{user}}.*',
                        'COUNT({{user.id}}) AS ordersCount',
                    ])
                    ->join('LEFT JOIN', '{{task}}', '{{task}}.contractor_id = user.id')

                    ->groupBy('{{user}}.id')
                    ->orderBy(['ordersCount'=> SORT_DESC]);
                break;
            case self::SORT_POPULARITY:
                $query->orderBy(['profile_read' => SORT_DESC]);
                break;
        }
        return $query;
    }


    /**
     * Get current sort type.
     *
     * @return  string
     */
    public function getCurrentSort() : string {
        return $this->currentSort;
    }
}
