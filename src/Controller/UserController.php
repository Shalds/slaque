<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/connexion", name="user_connexion")
     */
    public function login()
    {

        return $this->render('user/connexion.html.twig', [

        ]);
    }

    /**
     * @Route("/inscription", name="user_inscription")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user->setDateCreated(new \DateTime());
            $user->setRoles(["ROLE_USER"]);

            $hash = $passwordEncoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Votre compte a bien été crée !");

            return $this->redirectToRoute("home");

        }
        return $this->render('user/inscription.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
