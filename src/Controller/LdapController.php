<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Ldap\Ldap;
use App\Form\LdapLoginFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LdapController extends AbstractController
{
    #[Route('/ldap', name: 'app_ldap')]
    public function index(): Response
    {
        $ldap = Ldap::create('ext_ldap', [
            'host' => 'ldap.forumsys.com',
            'port' => 389,
            'encryption' => 'none',
        ]);
        $dn = 'cn=read-only-admin,dc=example,dc=com';
        //dn = 'dc=example,dc=com';
        $password = 'password';
        $ldap->bind($dn, $password);
        $query = $ldap->query('dc=example,dc=com', '(objectClass=*)');
        $results = $query->execute();

        foreach ($results as $entry) {
            // Do something with the results
            dump($entry);
        }
        die;

        return $this->render('ldap/index.html.twig', [
            'controller_name' => 'LdapController',
        ]);
    }

    #[Route('/login', name: 'ldap_login')]
    public function login(Request $request, SessionInterface $session): Response
    {
        $form = $this->createForm(LdapLoginFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $username = $data['username'];
            $password = $data['password'];

            $ldap = Ldap::create('ext_ldap', [
                'host' => 'ldap.forumsys.com',
                'port' => 389,
                'encryption' => 'none',
            ]);

            try {
                $ldap->bind("uid={$username},dc=example,dc=com", $password);
                // Authentication successful, start the session
                // You can store user information in the session if needed
                $session->set('user', $username);

                // Redirect to the desired page after login
                return $this->redirectToRoute('app_ldap');
            } catch (ConnectionException $e) {
                dump($username);
                dump($password);
                dd('here: ' . $e); die;
                // Authentication failed, handle the error
                $this->addFlash('error', 'Login failed: Invalid credentials.');
            }
        }

        return $this->render('ldap/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login2', name: 'ldap_login_2')]
    public function login2(AuthenticationUtils $authenticationUtils, Request $request, SessionInterface $session): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        //$form = $this->createForm(LdapLoginFormType::class);
        //$form->handleRequest($request);
        return $this->render('ldap/login2.html.twig', [
            //'form' => $form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout2', name: 'ldap_logout_2')]
    public function logout()
    {
        return $this->redirectToRoute('ldap_login_2');
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
