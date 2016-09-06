<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\AdvertSkill;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdvertController extends Controller {

public function indexAction($page)
  {
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
    if ($page < 1) {
      // On déclenche une exception NotFoundHttpException, cela va afficher
      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }
      $listAdverts = $this->getDoctrine()//on n'utilise plus findAll mais getAdverts méth défini dans AdvertReposotory pour charger ttes les infos des annonces
          ->getManager()
          ->getRepository('OCPlatformBundle:Advert')
          ->getAdverts()
      ;

      // L'appel de la vue ne change pas
      return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
          'listAdverts' => $listAdverts,
      ));
  }

  public function viewAction($id)
  {
$em = $this->getDoctrine()->getManager();


    // On récupère l'annonce $id

    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);


    if (null === $advert) {

      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");

    }

    // On récupère la liste des candidatures de cette annonce
    $listApplications = $em
      ->getRepository('OCPlatformBundle:Application')
      ->findBy(array('advert' => $advert))
    ;

// On récupère maintenant la liste des AdvertSkill
    $listAdvertSkills = $em
      ->getRepository('OCPlatformBundle:AdvertSkill')
      ->findBy(array('advert' => $advert))
    ;

    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
      'advert'           => $advert,
      'listApplications' => $listApplications,
      'listAdvertSkills' => $listAdvertSkills,
    ));
  }

 public function addAction(Request $request)    
  {
      $em = $this->getDoctrine()->getManager();

      // On ne sait toujours pas gérer le formulaire, patience cela vient dans la prochaine partie !

      if ($request->isMethod('POST')) {
          $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

          return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
  }
      return $this->render('OCPlatformBundle:Advert:add.html.twig');
  }
  
   public function editAction($id, Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

      if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      // Ici encore, il faudra mettre la gestion du formulaire

      if ($request->isMethod('POST')) {
          $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

          return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
      }

      return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
          'advert' => $advert
      ));
  }
  
  
  public function deleteAction($id)
  {
      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

      if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      // On boucle sur les catégories de l'annonce pour les supprimer
      foreach ($advert->getCategories() as $category) {
          $advert->removeCategory($category);
      }

      $em->flush();

      return $this->render('OCPlatformBundle:Advert:delete.html.twig');
  }


public function menuAction($limit)
{
    $em = $this->getDoctrine()->getManager();
    $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->findBy(
        array(),                 // Pas de critère
        array('date' => 'desc'), // On trie par date décroissante
        $limit,                  // On sélectionne $limit annonces
        0                        // À partir du premier
    );
    return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
        'listAdverts' => $listAdverts
    ));
}
}
