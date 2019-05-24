<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('category', null, ['choice_label' => 'name'])
            ->add('tags',  EntityType::Class, ['class' => Tag::Class, 'choice_label' => 'name', 'expanded' => true, 'multiple' => true, 'by_reference' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
