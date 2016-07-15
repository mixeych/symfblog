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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
            $article = $form->getData();
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
    
    public function articleAction($articleId, Request $request)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($articleId);
        if(!$article){
            throw $this->createNotFoundException('Статья не найдена');
        }
        $form = $this->createFormBuilder($article)
                ->add('category', EntityType::class, ["class" => 'AppBundle:Category', 'choice_label' => 'name'])
                ->add('save', SubmitType::class, ['label' => 'Ок'])
                ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $article = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
        }
        
        $title = $article->getTitle();
        $category = $article->getCategory();
        if($category){
            $category = $article->getCategory()->getName();
        }
        
        return $this->render('blog/singleArticle.html.twig', ['title' => $title, 'article' => $article, 'category' => $category, 'form' => $form->createView()]);
    }
    
    public function categoryAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository('AppBundle:Category')->find($id);
            if(!$category){
                return $this->json(["status" => false]);
            }
            $em->remove($category);
            $em->flush();
            return $this->json(["status" => true]);
        }
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $category = $form->getData();
            $category = $this->checkCategorySlug($category);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        return $this->render('blog/category.html.twig', ['title' => 'Категории', 'form' => $form->createView(), 'categories' => $categories]);
    }
    
    public function showCategory($categorySlug)
    {
        return $this->render('blog/showCategory.html.twig');
    }
    
    private function checkCategorySlug($category)
    {
        $validator = $this->get('validator');
        $errors = $validator->validate($category);
        if(count($errors)>0){
            $rand = rand(1, 99);
            $category->slug = $category->slug.$rand;
            $this->checkCategorySlug($category);
        }
        return $category;
    }
}

