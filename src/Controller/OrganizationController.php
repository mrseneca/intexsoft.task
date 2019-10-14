<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrganizationController extends AbstractController
{
    /**
     * @Route("/organizations", name="organizations")
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
     * @param Organization $organization
     * @return Response
     */
    public function show(Organization $organization) {
        dump($organization->getUser());
        return $this->render('organization/show.html.twig', [
            'org'=> $organization
        ]);
    }
}
