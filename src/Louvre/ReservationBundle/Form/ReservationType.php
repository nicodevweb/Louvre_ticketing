<?php

namespace Louvre\ReservationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
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
        }

        if ($options['ticket'])
        {
            $builder->add('email',  TextType::class, array('required' => true));
        }


        // Form for ticket registration view
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
