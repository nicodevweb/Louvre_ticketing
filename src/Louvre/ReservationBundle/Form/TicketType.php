<?php

namespace Louvre\ReservationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Intl; // Language bundle
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Translate CountryType's country names
        \Locale::setDefault('fr');

        $builder
            ->add('firstName',      TextType::class)
            ->add('lastName',       TextType::class)
            ->add('country',        CountryType::class, array('placeholder' => 'Sélectionnez votre pays de résidence ...'))
            ->add('birthDate',      HiddenType::class)
            ->add('reducedPrice',   CheckboxType::class, array('required' => false))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\ReservationBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_reservationbundle_ticket';
    }


}
