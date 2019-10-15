<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Entity\User;
use App\Repository\OrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrganizationsController
 * @package App\Controller
 * @Route("/", name="organizations.")
 */

class OrganizationsController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param OrganizationRepository $organizationRepository
     * @return Response
     */
    public function index(OrganizationRepository $organizationRepository)
    {
        $organizations = $organizationRepository->findAll();

        return $this->render('organization/index.html.twig', [
            'orgs'=>$organizations
        ]);
    }

    /**
     * @Route("/organizations/{id}", name="show")
     * @param $id
     * @return Response
     */
    public function show($id) {
        $organization = $this->getDoctrine()->getRepository('App\Entity\Organization')->find($id);
        $users = $organization->getUser();

        return $this->render('organization/show.html.twig', [
            'org'=> $organization,
            'users'=> $users
        ]);
    }


}
