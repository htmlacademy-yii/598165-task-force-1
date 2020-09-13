<?php


namespace frontend\services;


interface GeoObjectInterface
{
    public function getData();

    public function setData(array $data);

    public function getCity();

    public function getCoords();

    public function getAddress();

    public function getAutocompletionList();

}
