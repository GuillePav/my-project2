<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
  

    /**
     * @Route("/lucky/number/{max}", name="lucky")
     * 
     */

     public function numberAction ($max = 100)
     {

        //Génération d'un nombre aléatoire
        $number = random_int(0, $max);
        
        //ici on va chercher le template et on lui transmet la variable
        return $this->render('lucky/index.html.twig', [
            // pour fournir des variables au template
            // a gauche, le nom qui sera utilisé dans le template
            // a droite, la valeur
            'number' => $number,
       ]);
      
     }

     /**
      * @Route ("/blog/{_locale}/{year}/{title}", requirements={"_locale" = "en|fr","year"="\d\d\d\d", "title"="[a-zA-Z0-9-]*"})
      * @Route ("/blog/{year}/{title}", requirements={"year"="\d{4}", "title"="[a-zA-Z0-9-]*"})
      */
     public function blogAction($_locale = 'fr', $year, $title) {

      return $this->render('layout.html.twig');

      if($_locale === 'en') {
        return $this->render('blog/blog-en.html.twig', [
          'locale' => $_locale,
          'year' => $year,
          'title' => $title,
        ]);

      } elseif ($_locale === 'fr') {
        return $this->render('blog/blog-fr.html.twig', [
          'locale' => $_locale,
          'year' => $year,
          'title' => $title,
        ]);
      }
        
     }
}
