<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupeController extends Controller
{
    private $idGroupe;

    /**
     * @Route("/message/addgroupe", name="add_groupe")
     */
    public function addGroupe(Request $request)
    {

        $groupe = new Groupe();

        $em = $this->getDoctrine()->getManager();

        $nameGroupe = $request->get('name');

        $user = $this->getUser();

        $groupe->setName($nameGroupe);
        $groupe->addUser($user);
        $groupe->setDateCreated(new \DateTime());

        $em->persist($groupe);
        $em->flush();

        $groupeRep = $this->getDoctrine()->getRepository(Groupe::class);

        $groupes = $groupeRep->findBy(
            [],
            ['dateCreated' => 'DESC']
        );

        return new JsonResponse($groupes);
    }

    /**
     * @Route("/message/viewgroupe", name="view_groupe")
     */
    public function viewGroupe()
    {

        $user = $this->getUser();

        $groupeRep = $this->getDoctrine()->getRepository(Groupe::class);


        return new JsonResponse($user);
    }

    /**
     * @Route("/message/select-groupe_{id}", name="select_groupe")
     */
    public function selectGroupe($id)
    {

        $groupeRep = $this->getDoctrine()->getRepository(Groupe::class);

        $groupes = $groupeRep->find($id);

        $this->setIdGroupe($id);

        return new JsonResponse($groupes);
    }

    /**
     * @Route("/message/add-user-groupe", name="add_user_groupe")
     */
    public function addUserGroupe(Request $request)
    {

        $userRep = $this->getDoctrine()->getRepository(User::class);
        $groupeRep = $this->getDoctrine()->getRepository(Groupe::class);

        $em = $this->getDoctrine()->getManager();

        //Get User by ID
        $idUser = $request->get('id');
        $user = $userRep->find($idUser);

        $groupeUser = $groupeRep->find($this->getIdGroupe());

        $groupeUser->setUser($user);

        $em->persist($groupeUser);
        $em->flush();

        return new JsonResponse($groupeUser);
    }

    /**
     * @return mixed
     */
    public function getIdGroupe()
    {
        return $this->IdGroupe;
    }

    /**
     * @param mixed $id
     */
    public function setIdGroupe($id): void
    {
        $this->IdGroupe = $id;
    }

}
