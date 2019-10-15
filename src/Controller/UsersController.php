<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersController
 * @package App\Controller
 * @Route("/users", name="users.")
 */
class UsersController extends AbstractController
{

    /**
     * @Route("/{id}", name="show")
     * @param User $user
     * @return Response
     */
    public function show(User $user) {
        return $this->render('users/show.html.twig', [
            'user'=>$user
        ]);
    }
}
