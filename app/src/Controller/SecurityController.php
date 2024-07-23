<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($request->isMethod('POST')) {
            $user = new User();
            $user->setEmail($request->request->get('email'));
            $user->setPseudo($request->request->get('nomComplet'));
            $user->setRoles(["ROLE_ADMIN"]);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('security/register.html.twig');
    }

    /**
     * @Route("/forgottenPassword", name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request,
                                      UserPasswordEncoderInterface $encoder,
                                      \Swift_Mailer $mailer,
                                      TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('home');
            }

            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }

            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_PATH);

            $message =  (new \Swift_Message('Forgot Password'))
                ->setFrom('pfwow@admin.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    "blablabla voici le token pour reseter votre mot de passe: " . $url, 'text/html'
                );

            $mailer->send($message);
            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('home');
        }
        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneByResetToken($token);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token inconnu');
                return $this->redirectToRoute('home');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $em->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour avec succès');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/reset_password.html.twig', [
            'token' =>  $token,
        ]);
    }
}
