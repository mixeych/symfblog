<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Article;

class BlogController extends Controller
{

    public function mainAction()
    {
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();
        return $this->render('blog/main.html.twig', ['title' => 'Блог', 'articles' => $articles]);
        
    }
    
    public function createAction(Request $request)
    {
        if($request->isMethod('POST')){
            $title = $request->get('title');
            $content = $request->get('content');
            $article = new Article();
            $article->setTitle($title);
            $article->setContent($content);
            $article->setStatus("draft");
            
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($article);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            return $this->redirectToRoute('blog');
        }
        return $this->render('blog/create.html.twig', ['title' => 'Новая статья']);
    }
    
    public function articleAction($articleId)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($articleId);

        if(!$article){
            throw $this->createNotFoundException('Статья не найдена');
        }
        $title = $article->getTitle();
        return $this->render('blog/singleArticle.html.twig', ['title' => $title, 'article' => $article]);
    }
    
}

