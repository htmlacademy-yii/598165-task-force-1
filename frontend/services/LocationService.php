<?php


namespace frontend\services;


interface LocationService
{
    public function getLocation(string $address) : ?GeoObjectInterface;
}
