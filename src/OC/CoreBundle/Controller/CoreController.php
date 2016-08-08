<?php

// src/OC/PlatformBundle/Controller/CoreController.php

namespace OC\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller {

    public function indexAction() {
        return $this->render('OCCoreBundle:Core:index.html.twig');
    }

    public function contactAction(Request $request) {

        $session = $request->getSession();

        // Si l'on demande la page contact
        // Message d'infos pour dire qu'elle n'est pas encore dispo.
        $session->getFlashBag()->add('info', 'La page de contact n\'est pas encore disponible, merci de revenir plus tard.');

        // Puis on redirige vers la page d'accueil
        return $this->redirectToRoute('oc_core_home');
    }

}
