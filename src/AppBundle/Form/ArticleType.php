<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\CategoryType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

Class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('title', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('content', TextareaType::class, ['attr' => ['class' => 'tinymce form-control', 'rows' => '15']])
            ->add('category', EntityType::class, ["class" => 'AppBundle:Category', 'choice_label' => 'name', 'attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['label' => 'Отправить'])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article',
        ));
    }
}
