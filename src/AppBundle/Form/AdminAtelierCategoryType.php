<?php

namespace AppBundle\Form;

use AppBundle\Entity\AtelierCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class AdminAtelierCategoryType
 * @package AppBundle\Form
 */
class AdminAtelierCategoryType extends AbstractType
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
            ->add('subTitle', TextType::class, array(
                'label'      => 'Sous-titre',
                'required'   => false,
                'label_attr' => ['class' => 'control-label']
            ))
            ->add('order', IntegerType::class, [
                'label'      => 'Ordre',
                'required'   => false,
                'label_attr' => ['class' => 'control-label']
            ])
            ->add('position', ChoiceType::class, array(
                'label'      => 'Position',
                'choices'    => ['Colonne de gauche' => 'left', 'Colonne de droite' => 'right'],
                'label_attr' => ['class' => 'control-label']
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AtelierCategory::class

        ));
    }
}
