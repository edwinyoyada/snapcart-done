<?php

namespace Snapcart\APIBundle\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


/**
 * Created by PhpStorm.
 * User: OP-User
 * Date: 2/10/2017
 * Time: 7:32 PM
 */
class AuthenticationSuccessHandler
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function onJWTCreated(AuthenticationSuccessEvent $event)
    {
        $this->container->get('snapcart.user_service')->setToken($event->getUser(), $event->getData()['token']);
    }
}