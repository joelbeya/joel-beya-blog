<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Nous avons besoin d'accéder à la requête pour obtenir le numéro de page
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator

class CrudController extends AbstractController
{
    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, ValidatorInterface $validator): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $article->setLastupdate(new \DateTime());
            $article->setAuthor($user->getUserName());

            $em = $this->getDoctrine()->getManager(); // On récupère l'entity manager

            $errors = $validator->validate($article);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }
            $em->persist($article); // On confie notre entité à l'entity manager (on persist l'entité)
            $em->flush(); // On execute la requete

            $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('ajoute', 'Votre article '.$article->getTitle().' a bien été ajouté');

            return $this->redirectToRoute("home");
        }
        return $this->render('crud/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show($id)
    {
        $article = $this->getDoctrine()
                        ->getRepository(Article::class)
                        ->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                  'No Article found in the Database'.$id
            );
        }

        return $this->render('crud/show.html.twig', [
          'article' => $article,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id, Request $request, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager(); // On récupère l'entity manage
        $article = $em->getRepository(Article::class)
                      ->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        if (!$article) {
            throw $this->createNotFoundException(
                  'No Article found in the Database'.$id
            );
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $article->setLastupdate(new \DateTime());
            $article->setAuthor($user->getUserName());

            $errors = $validator->validate($article);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }

            $em->flush(); // On execute la requete

            // return $this->redirectToRoute("home");
            return $this->render('crud/show.html.twig', [
                'article' => $article,
                'modifier' => 'Votre article '.$article->getTitle().' a bien été modifié'
            ]);
        }

        return $this->render('crud/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id, Request $request)
    {
      $em = $this->getDoctrine()->getManager(); // On récupère l'entity manage
      $article = $em->getRepository(Article::class)
                    ->find($id);

      $user = $this->getUser();

      if ($user) {
          if ($article->getAuthor() != $user->getUserName()) {
              $request
                      ->getSession()
                      ->getFlashBag()
                      ->add('error', "Desolé, vous pourriez supprimer cet article ".$article->getTitle()."  si vous en etiez auteur");

            return $this->redirectToRoute("home");
          }
      }

      $em->remove($article);
      $em->flush();
      $request
              ->getSession()
              ->getFlashBag()
              ->add('supprime', 'Votre article '.$article->getTitle().' a bien été supprimé');

      return $this->redirectToRoute("home");
    }

    /**
     * Transform a string to a valid url
     * @param  string $str       [description]
     * @param  array  $replace   [description]
     * @param  string $delimiter [description]
     * @return string            [description]
     */
    public function generateSlug($str, $replace = array(), $delimiter = '-'): string
    {
        if (!empty($replace)) {
            $str = str_replace((array) $replace, ' ', $str);
        }
        $accent = array("é", "è");
        $str    = str_replace($accent, 'e', $str);
        $str    = str_replace('ç', 'c', $str);
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        if (substr($clean, -1) == '-') {
            $clean = substr($clean, 0, strlen($clean) - 1);
        }
        return $clean;
    }
}
