<?php

namespace App\Controller;

use App\Entity\Groupe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupeController extends Controller
{
    /**
     * @Route("/message/addgroupe", name="add_groupe")
     */
    public function addGroupe(Request $request)
    {

        $groupe = new Groupe();

        $em = $this->getDoctrine()->getManager();

        $nameGroupe = $request->get('name');
        $groupe->setName($nameGroupe);

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

        $groupeRep = $this->getDoctrine()->getRepository(Groupe::class);

        $groupes = $groupeRep->findBy(
            [],
            ['dateCreated' => 'DESC']
        );

        return new JsonResponse($groupes);
    }
}
