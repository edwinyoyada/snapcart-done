<?php

namespace Snapcart\APIBundle\Services;

use Snapcart\APIBundle\Entity\Point;
use Snapcart\APIBundle\Entity\User;


/**
 * Created by PhpStorm.
 * User: OP-User
 * Date: 2/9/2017
 * Time: 10:45 PM
 */
class UserService
{
    private $doctrine;

    private $em;

    private $encoder;

    private $userRepo;

    public function __construct($doctrine, $encoder)
    {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
        $this->em = $doctrine->getEntityManager();
        $this->userRepo = $this->em->getRepository('SnapcartAPIBundle:User');
    }

    public function saveUser($username, $password)
    {
        $user = new User();
        $hashed = $this->encoder->encodePassword($user, $password);
        $user->setUsername($username);
        $user->setPassword($hashed);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function setLocation($user, $lat, $lng)
    {
        $user->setLocation(new Point($lat, $lng));
        $this->em->persist($user);
        $this->em->flush();
    }

    private function updateToken($user, $token)
    {
        $user->setAccessToken($token);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function setToken($user, $token)
    {
        $this->updateToken($user, $token);
    }

    public function emptyToken($user)
    {
        $this->updateToken($user, '');
    }

    public function getUserFromToken($token)
    {
        return $this->userRepo->getUserFromToken($token);
    }

    public function getTokenFromHeader($header)
    {
        return explode(' ', $header)[1];
    }

    public function getUsersList($token, $sortBy, $sortDir, $userFilter, $distanceFilter, $distanceType, $createdFilter, $createdType)
    {
        $currentUser = $this->userRepo->getUserFromToken($token);
        $users = $this->userRepo->getListOfUsers($token, $sortBy, $sortDir, $userFilter, $createdType, $createdFilter);

        $return = [];
        foreach ($users as $user) {
            $temp = [];

            $distance = $this->getDistance($currentUser->getLocation(), $user['location']);
            if($distanceType == 'GT') {
                if($distance > $distanceFilter) {
                    $temp['username'] = $user['username'];
                    $temp['distance'] = $distance;
                    $temp['created_at'] = $user['createdAt'];
                }
            }
            else {
                if($distance < $distanceFilter) {
                    $temp['username'] = $user['username'];
                    $temp['distance'] = $distance;
                    $temp['created_at'] = $user['createdAt'];
                }
            }
            $return[] = $temp;
        }

        return $return;
    }

    // http://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
    public function getDistance($location1, $location2)
    {
        $earthRadius = 6371000;

        $lat1 = $location1->getLat();
        $lng1 = $location1->getLng();
        $lat2 = $location2->getLat();
        $lng2 = $location2->getLng();

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lng2);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return ($angle * $earthRadius) / 1000;
    }
}