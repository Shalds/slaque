<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Message;
use App\Form\GroupeType;
use App\Form\MessageType;
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
        $formGroupe = $this->createForm(GroupeType::class, $groupe);

        return $this->render('message/message.html.twig', [
            'formMessage' => $formMessage->createView(),
            'formGroupe' => $formGroupe->createView()
        ]);
    }

    /**
     * @Route("/message/add", name="add_message")
     */
    public function addMessage(Request $request){

            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            $text = $request->get('text');
            $message = new Message();
            $message->setText($text);
            $message->setDateCreated(new \DateTime());
            $message->setUser($user);

            $em->persist($message);
            $em->flush();
            //make something curious, get some unbelieveable data
            $arrData = ['output' => 'here the result which will appear in div'];

        return new JsonResponse($arrData);
    }


}
