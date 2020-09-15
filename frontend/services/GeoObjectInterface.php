<?php


namespace frontend\services;


interface GeoObjectInterface
{

    public function getCity() : string;

    public function getCoords() : array;

    public function getAddress() : string;

    public function getAutocompletionList() : array;

}
