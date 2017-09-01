<?php

namespace AppBundle\Form;

use AppBundle\Entity\Recette;
use AppBundle\Entity\RecetteCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class AdminRecetteType
 * @package AppBundle\Form
 */
class AdminRecetteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label'      => 'Nom',
                'required'   => false,
                'label_attr' => ['class' => 'control-label']
            ))
            ->add('category', EntityType::class, [
                'label'      => 'Catégorie',
                'class'      => RecetteCategory::class,
                'label_attr' => ['class' => 'control-label']
            ])
            ->add('position', IntegerType::class, array(
                'label'      => 'Ordre',
                'required'   => false,
                'label_attr' => ['class' => 'control-label']
            ))
            ->add('pdf', FileType::class, array(
                'label'      => 'Fichier pdf',
                'required'   => false,
                'label_attr' => ['class' => 'control-label']
            ))
            ->add('active', CheckboxType::class, array(
                'label'      => 'Publié ?',
                'required'   => false,
                'label_attr' => ['class' => 'control-label']
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Recette::class

        ));
    }
}
