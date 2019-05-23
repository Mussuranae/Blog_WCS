<?php

namespace App\Controller;

use App\Form\ArticleSearchType;
use App\Form\CategoryType;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;

class BlogController extends AbstractController
{


    /**
     * Show all row from article's entity
     *
     * @Route("/index", name="blog_index")
     * @return Response             A response instance
     */
    public function index(): Response
    {
        $articleRepo = $this->getDoctrine()
            ->getRepository(Article::class);

        $articles = $articleRepo->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        $form = $this->createForm(ArticleSearchType::class, null, ['method' => \Symfony\Component\HttpFoundation\Request::METHOD_GET]);

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles, 'form' => $form->createView()]
        );
    }




    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/article/{slug}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     *  @return Response A response instance
     */
    public function show(?string $slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug' => $slug,
            ]
        );
    }

    /**
     *
     * @Route("/category/{name}", name="show_category")
     * @return Response A response instance
     */

    public function showByCategory(Category $category) : Response
    {
        $articles = $category->getArticles();


        $category = new Category();
        $formCat = $this->createForm(CategoryType::class, $category);

        return $this->render('blog/category.html.twig', ['articles' => $articles, 'category' => $category, 'form' => $formCat->createView()]);
    }


    /**
     * @Route("/tag/{name}", name="show_tag")
     * @return Response
     */
    public function showListTags(Tag $tag) : Response
    {
        $articles = $tag->getArticles();
        return $this->render('blog/tag.html.twig', ['articles' => $articles, 'tag' => $tag]);
    }

}