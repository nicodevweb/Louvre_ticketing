<?php

namespace Louvre\ReservationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('firstName',      TextType::class, array(
                'label' => 'Prénom :',
                'attr' => array(
                    'placeholder' => 'Sélectionnez votre prénom ...'
                )                
            ))
            ->add('lastName',       TextType::class, array(
                'label' => 'Nom :',
                'attr' => array(
                    'placeholder' => 'Sélectionnez votre nom ...'
                )      
            ))
            ->add('country',        CountryType::class, array(
                'label' => 'Pays :',
                'placeholder' => 'Sélectionnez votre pays de résidence ...'           
            ))
            ->add('birthDate',      DateType::class, array(
                'label' => 'Date de naissance :',
                'placeholder' => array(
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour'
                ),
                'years' => range(date('Y')-100, date('Y')),
                'attr' => array(
                    'class' => 'datepickerTicket')
            ))
            ->add('reducedPrice',   CheckboxType::class, array(
                'label' => 'Je bénéficie d\'un tarif réduit *',
                'required' => false
            ))
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
