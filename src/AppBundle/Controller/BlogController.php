<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ArticleType;
use AppBundle\Form\CategoryType;
use AppBundle\Entity\Article;
use AppBundle\Entity\Category;

class BlogController extends Controller
{

    public function mainAction()
    {
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();
        return $this->render('blog/main.html.twig', ['title' => 'Блог', 'articles' => $articles]);
        
    }
    
    public function createAction(Request $request)
    {
        $article = new Article();
        $article->setTitle('Название статьи'); // value= аттрибут
        $article->setContent('текст статьи'); // value= аттрибут
        $form = $this->createForm(ArticleType::class, $article);
   /*     $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Отправить'))
            ->getForm();*/
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $title = $request->get('title');
            $content = $request->get('content');
            $article->setTitle($title);
            $article->setContent($content);
            $article->setStatus("draft");
            $em = $this->getDoctrine()->getManager();
//            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($article);
//
//            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            return $this->redirectToRoute('blog');
        }
        return $this->render('blog/create.html.twig', ['title' => 'Новая статья', 'form' => $form->createView()]);
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
    
    public function categoryAction(Request $request)
    {
        $category = new Category();
        //$form = $this->createForm(CategoryType::class, $category);
        echo "ok";
        die();
//        $form->handleRequest($request);
//        if($form->isSubmitted()&&$form->isValid()){
//            $category = $form->getData();
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($article);
//            $em->flush();
//        }
//        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
//        return $this->render('blog/category.html.twig', ['title' => 'Категории', 'form' => $form, 'categories' => $categories]);
    }
}

