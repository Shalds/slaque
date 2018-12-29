<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Message;
use App\Entity\User;
use App\Form\GroupeAddType;
use App\Form\GroupeAddUserType;
use App\Form\MessageType;
use App\Form\UserEditProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class MessageController extends Controller
{
    /**
     * @Route("/message", name="message")
     */
    public function index()
    {
        $message = new Message();
        $formMessage = $this->createForm(MessageType::class, $message);

        $groupe = new Groupe();
        $formAddGroupe = $this->createForm(GroupeAddType::class, $groupe);

        $formAddUserGroupe = $this->createForm(GroupeAddUserType::class, $groupe);

        $user = new User();
        $formUserEditProfilType = $this->createForm(UserEditProfilType::class, $user);




        return $this->render('message/message.html.twig', [
            'formMessage' => $formMessage->createView(),
            'formGroupe' => $formAddGroupe->createView(),
            'formAddUserGroupe' => $formAddUserGroupe->createView(),
            'formUserEditProfilType' => $formUserEditProfilType->createView()
        ]);
    }

    /**
     * @Route("/message/add", name="add_message")
     */
    public function addMessage(Request $request){

            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            $text = $request->get('text');
            $idGroupe = $request->get('idGroupe');

            $groupeRep = $this->getDoctrine()->getRepository(Groupe::class);
            $groupe = $groupeRep->find($idGroupe);

            $message = new Message();
            $message->setText($text);
            $message->setDateCreated(new \DateTime());
            $message->setUser($user);
            $message->setGroupe($groupe);

            $em->persist($message);
            $em->flush();


        return new JsonResponse($message);
    }

    /**
     * @Route("/message/viewMessage", name="view_message")
     */
    public function getMessage(Request $request)
    {

        $idGroupe = $request->get('idGroupe');

        $repoGroupe = $this->getDoctrine()->getRepository(Groupe::class);

        $groupe = $repoGroupe->find($idGroupe);

        return new JsonResponse($groupe);
    }



}
