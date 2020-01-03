<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\ArticleSearchType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Nous avons besoin d'accéder à la requête pour obtenir le numéro de page
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator


class BlogController extends AbstractController
{

    /**
     * @Route("")
     */
    public function index()
    {
        return $this->redirectToRoute('home', array('page' => 1));
    }

    /**
     * @Route("/page", name="home", requirements = {"page" = "\d+"}, defaults={1})
     */
    public function home(PaginatorInterface $paginator, Request $request) // Nous ajoutons les paramètres requis)
    {
        $article = new Article();
        $searchForm = $this->createForm(ArticleSearchType::class, $article);
        $article->setTitle('');

        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid())
        {
            $data = $this->getDoctrine()
                                     ->getRepository(Article::class)
                                     ->findByTitle($article->getTitle());

            if (!$data) {
                $this->addFlash(
                    'notice',
                    'Votre recheche ' . $article->getTitle() . ' est introuvable sur notre base de données'
                );
                return $this->redirectToRoute('home');
             }

             $article = $paginator->paginate(
                 $data, // Requête contenant les données à paginer (ici nos articles)
                 $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                 3 // Nombre de résultats par page
             );

             return $this->render('blog/home.html.twig', [
                 'article' => $article,
                 'search_form' => $searchForm->createView()
             ]);
        }


        $data = $this->getDoctrine()
                     ->getRepository(Article::class)
                     ->findBy(array(), array('lastupdate' => 'ASC'));

        if (!$data) {
            throw $this->createNotFoundException(
                  'No Article found in the Database'
            );
        }

        $article = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );

        return $this->render('blog/home.html.twig', [
            'article' => $article,
            'search_form' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/cv", name="cv")
     */
    public function cv()
    {
        return $this->render('blog/pages/cv.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('blog/pages/about.html.twig', [
            'about' => 'about',
        ]);
    }
}
