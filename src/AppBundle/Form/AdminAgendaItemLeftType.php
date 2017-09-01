<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class AdminAgendaItemLeftType
 * @package AppBundle\Form
 */
class AdminAgendaItemLeftType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom'))
            ->add('date', DateType::class, array('label' => 'Date'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('active', CheckboxType::class, array('label' => 'Message', 'required' => false))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AgendaItemLeft'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rg_agenda_item_left';
    }
}
