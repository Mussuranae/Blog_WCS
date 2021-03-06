<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\Slugify;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        if (!$user)
        {
            $article = $articleRepository->findAllWithCategoriesAndTags();
            return $this->render('article/index.html.twig', [
                'articles' => $article
            ]);
        }
        else
        {
            $userMail = $user->getEmail();
            $article = $articleRepository->findAllWithCategoriesAndTags();
            return $this->render('article/index.html.twig', [
                'articles' => $article, 'user' => $userMail
            ]);
        }


    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     * @IsGranted("ROLE_AUTHOR")
     */
    public function new(Request $request, Slugify $slugify, \Swift_Mailer $mailer): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $author = $this->getUser();
            $article->setAuthor($author);

            $entityManager = $this->getDoctrine()->getManager();

            $slug = $slugify->generate($article->getTitle());
            $article->setSlug($slug);

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article créé');

            $message = (new \Swift_Message('Nouvel article publié'))
                ->setBody($this->renderView('mail/notification.html.twig', ['article' => $article]), 'text/html');
            $mailer->send($message);

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_AUTHOR")
     */
    public function edit(Request $request, Article $article, Slugify $slugify): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setSlug();
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Article modifié');


            return $this->redirectToRoute('article_index', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     * @IsGranted("ROLE_AUTHOR")
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('danger', 'Article supprimé');

        }

        return $this->redirectToRoute('article_index');
    }
}
