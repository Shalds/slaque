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

        $groupe = $user->getGroupes();

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

        $groupeRep = $this->getDoctrine()->getRepository(Groupe::class);
        $userRep = $this->getDoctrine()->getRepository(User::class);

        $em = $this->getDoctrine()->getManager();
        $tabIdUser = $request->get('idUser');
        $idGroupe = $request->get('idGroupe');

        $groupe = $groupeRep->find($idGroupe);

        foreach ($tabIdUser as $item){
            $groupe->addUser($userRep->find($item));
            $em->persist($groupe);
        }
        $em->flush();


        return new JsonResponse($groupe);
    }

    /**
     * @Route("/message/view-user-groupe", name="view_user_groupe")
     */
    public function viewUserGroupe(Request $request)
    {

        $groupeRep = $this->getDoctrine()->getRepository(Groupe::class);
        $idGroupe = $request->get('idGroupe');

        $groupe = $groupeRep->find($idGroupe);

        return new JsonResponse($groupe);
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
