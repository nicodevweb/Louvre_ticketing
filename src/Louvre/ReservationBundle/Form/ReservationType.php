<?php

namespace Louvre\ReservationBundle\Form;

use Louvre\ReservationBundle\Form\TicketType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Form for calendar view
        if ($options['calendar'])
        {
             $builder
                ->add('date',       HiddenType::class)
                ->add('fullDay',    SubmitType::class, array('label' => 'Journée'))
                ->add('halfDay',    SubmitType::class, array('label' => 'Demi-journée'))
            ;

            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $event->stopPropagation();
            }, 900);
        }

        // Form for ticket registration view
        if ($options['ticket'])
        {
            $builder
                ->add('email',  TextType::class, array('required' => true))
                // Collection TicketType form included in Reservation form
                ->add('tickets', CollectionType::class, array(
                    'entry_type' => TicketType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'label' => false
                ))
                ->add('confirm',    SubmitType::class, array('label' => 'Confirmer ma commande'))
            ;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\ReservationBundle\Entity\Reservation',
            // If visitor is in calendar view
            'calendar' => false,
            // If visitor is in ticket registration view
            'ticket' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_reservationbundle_reservation';
    }
}
