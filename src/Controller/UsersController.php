<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\XmlType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/createmanually", name="createmanually")
     * @param Request $request
     * @return Response
     */
    public function createManually(Request $request) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('organizations.show',['id'=>$user->getOrganization()->getId()]));
        }

        return $this->render('users/create.html.twig', [
            'form'=>$form->createView(),
            'title'=>'Добавление нового пользователя'
        ]);
    }

    /**
     * @Route("/createfromxml", name="createfromxml")
     * @param Request $request
     * @return Response
     */
    public function createFromXml(Request $request) {
        $form = $this->createForm(XmlType::class);
        $form->handleRequest($request);

        return $this->render('users/create.html.twig', [
            'form'=>$form->createView(),
            'title'=>'Загрузка XML-файла'
        ]);
    }

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

