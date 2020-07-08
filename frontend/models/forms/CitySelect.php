<?php


namespace frontend\models\forms;


use frontend\models\City;
use frontend\models\User;
use yii\base\Model;

class CitySelect extends Model
{

    private ?int $currentCityId = null;


    /**
     * Get cities list.
     *
     * @return  array
     */
    public function getCities() : array
    {
        return City::find()->asArray()->all();
    }

  /**
     * Get current city id.
     *
     * @return  int
     */
    public function getCurrentCityId() : int
    {
        if (!$this->currentCityId) {
            $currentUser = User::findOne(\Yii::$app->user->getId());
            return $currentUser->city_id;

        }
        return $this->currentCityId;
    }

    /**
     * Set current city id.
     *
     * @param  $id int
     */
    public function setCurrentCityId($id)
    {
        $this->currentCityId = $id;
    }
}
