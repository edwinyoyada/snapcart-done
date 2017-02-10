<?php

namespace Snapcart\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
        $userService = $this->get('snapcart.user_service');

        $header = $request->headers->get('Authorization');
        $token = $userService->getTokenFromHeader($header);
        $user = $userService->getUserFromToken($token);

        if (!$user)
            throw new TokenNotFoundException('Token Not Found');

        $userService->emptyToken($user);

        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("/users", name="users")
     */
    public function getUserAction(Request $request)
    {
        $userService = $this->get('snapcart.user_service');

        $header = $request->headers->get('Authorization');
        $token = $userService->getTokenFromHeader($header);

        $sortBy = 'id';
        $sortDir = 'DESC';
        $userFilter = '';
        $distanceFilter = 0;
        $distanceType = 'GT';
        $createdFilter = new \DateTime();
        $createdType = 'LT';

        if ($request->query->has('sortBy'))
            $sortBy = $request->query->get('sortBy');
        if ($request->query->has('sortBy'))
            $sortDir = $request->query->get('sortDir');
        if ($request->query->has('username'))
            $userFilter = $request->query->get('username');
        if ($request->query->has('distanceFilter'))
            $distanceFilter = $request->query->get('distanceFilter');
        if ($request->query->has('distanceType'))
            $distanceType = $request->query->get('distanceType');
        if ($request->query->has('createdFilter'))
            $createdFilter = new \DateTime($request->query->get('createdFilter'));
        if ($request->query->has('createdType'))
            $createdType = $request->query->get('createdType');

        $users = $userService->getUsersList($token, $sortBy, $sortDir, $userFilter, $distanceFilter, $distanceType, $createdFilter, $createdType);

        return new JsonResponse($users);
    }
}
