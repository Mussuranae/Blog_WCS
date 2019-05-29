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
     * @Route("/tag", name="show_tag")
     * @return Response
     */
    public function showListTags(Tag $tag) : Response
    {
        $tag = new Tag();
        return $this->render('blog/tag.html.twig', ['tag' => $tag]);
    }

}