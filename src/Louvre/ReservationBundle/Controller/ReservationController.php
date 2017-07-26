<?php

namespace Louvre\ReservationBundle\Controller;

use Louvre\ReservationBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends Controller
{
	public function indexAction()
	{
		return $this->render('LouvreReservationBundle:Reservation:index.html.twig');
	}

	public function calendarAction(Request $request)
	{
		// Reservation object is created
		$reservation = new Reservation();

		// FormBuilder is created by form factory service
		$form = $this->get('form.factory')->createBuilder(FormType::class, $reservation)
		// Entity fields wanted are added to the form
			->add('date',		HiddenType::class)
			->add('fullDay', 	SubmitType::class, array('label' => 'Journée'))
			->add('halfDay', 	SubmitType::class, array('label' => 'Demi-journée'))
			// Form is generated from $formBuilder
			->getForm()
		;

		// If POST request executed (if form is submitted)
		if ($request->isMethod('POST'))
		{
			// Now Request <-> Form are related
			// From now on, $reservation has form values
			$form->handleRequest($request);

			// Reservation type hydratation
			$form->get('fullDay')->isClicked() ? $reservation->setType('fullDay') : $reservation->setType('halfDay');

			var_dump($reservation);

			// Reservation object's session is created

			// Redirection to ticket infos' page
		}

		// Method createView()  is used so the form will be added to view
		return $this->render('LouvreReservationBundle:Reservation:calendar.html.twig', array(
			'form' =>$form->createView(),
		));
	}
}