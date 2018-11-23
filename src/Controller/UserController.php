<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use App\Uploader\Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/connexion", name="user_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request)
    {
        if($this->getUser()){
            $this->addFlash('warning', "Vous êtes déjà connecté !");

            return $this->redirectToRoute("message");
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/connexion.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/inscription", name="user_register")
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

    /**
     * @Route("/message/update_profil", name="user_update_profil")
     */
    public function update_profil(Request $request, Uploader $uploader)
    {

        var_dump($request->files->get("user_edit_profil"));
        die();
        $file = $request->files->get('user_edit_profil["Image"]');

        $user = $this->getUser();

        $error = $uploader->setUploadFile($file);

        if ($error){
            dump($error);
           die();
        }

        $uploader->uploadFile();
        $user->setImage( $uploader->getNewFileNameWithExt() );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse($user);
    }

    /**
     * @Route("/deconnexion", name="user_logout")
     */
    public function logout(){

    }
}
