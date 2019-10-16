<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Entity\User;
use App\Form\UserType;
use App\Form\XmlType;
use App\Repository\OrganizationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SimpleXMLElement;

/**
 * Class UsersController
 * @package App\Controller
 * @Route("/users", name="users.")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/createmanually", name="createManually")
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
     * @Route("/createfromxml", name="createFromXml")
     * @param Request $request
     * @param OrganizationRepository $organizationRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function createFromXml(Request $request, OrganizationRepository $organizationRepository, UserRepository $userRepository) {
        $form = $this->createForm(XmlType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var UploadedFile $file */
            $file = $request->files->get('xml')['xml'];

            if ($file) {
                $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
                $file->move(
                    $this->getParameter('uploads_dir'),
                    $filename
                );
                try {
                    $xmlView = new SimpleXMLElement(file_get_contents($this->getParameter('uploads_dir') . $filename));

                    $em = $this->getDoctrine()->getManager();

                    foreach ($xmlView->org as $org) {
                        if (!$organization = $organizationRepository->findOneByOktmo($org->attributes()->oktmo)) {
                            $organization = new Organization();
                            $organization->setName($org->attributes()->displayName);
                            $organization->setOgrn($org->attributes()->ogrn);
                            $organization->setOktmo($org->attributes()->oktmo);
                            $em->persist($organization);
                            $em->flush();
                        }

                        foreach ($org->user as $eachUser) {
                            if (!$user = $userRepository->findBySnils($eachUser->attributes()->snils)) {
                                $user = new User();
                                $user->setFirstname($eachUser->attributes()->firstname);
                                $user->setLastname($eachUser->attributes()->lastname);
                                $user->setMiddlename($eachUser->attributes()->middlename);
                                $user->setInn($eachUser->attributes()->inn);
                                $user->setSnils($eachUser->attributes()->snils);
                                $user->setOrganization($organization);
                                $em->persist($user);
                                $em->flush();
                            } else {
                                $this->addFlash('errors', 'Пользователь ' . $eachUser->attributes()->firstname . ' ' . $eachUser->attributes()->lastname . ' не был добавлен, поскольку в базе уже есть пользователь с таким СНИЛС');
                            }
                        }
                    }
                    $this->addFlash('success', 'Файл был загружен и обработан');
                }
                catch (\Exception $e) {
                    $this->addFlash('fileType', 'Неверный формат файла.');
                    error_log($e->getMessage());
                }
                unlink($this->getParameter('uploads_dir').$filename);

            }
        }

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

