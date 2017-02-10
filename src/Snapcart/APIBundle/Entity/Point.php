<?php
/**
 * Created by PhpStorm.
 * User: OP-User
 * Date: 2/10/2017
 * Time: 1:32 PM
 */

namespace Snapcart\APIBundle\Entity;


class Point
{
    public function __construct($lat, $lng)
    {
        $this->lat  = $lat;
        $this->lng = $lng;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function getLng()
    {
        return $this->lng;
    }
}