<?php

namespace Louvre\ReservationBundle\Controller;

use Louvre\ReservationBundle\Entity\Reservation;
use Louvre\ReservationBundle\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
		$form = $this->createForm(ReservationType::class, $reservation, array('calendar' => true));

		// If POST request executed (if form is submitted)
		// Now Request <-> Form are related (handleRequest)
		// From now on, $reservation has valid form values
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			// Reservation type hydratation
			$form->get('fullDay')->isClicked() ? $reservation->setType('fullDay') : $reservation->setType('halfDay');

			// Reservation object's session is created
			$session = $request->getSession();
			$session->set('reservation', $reservation);

			// Redirection to ticket infos' page
			return $this->redirectToRoute('louvre_reservation_ticketing');
		}

		// Method createView()  is used so the form will be added to view
		return $this->render('LouvreReservationBundle:Reservation:calendar.html.twig', array(
			'form' =>$form->createView(),
		));
	}

	public function ticketingAction(Request $request)
	{
		return $this->render('LouvreReservationBundle:Reservation:ticketing.html.twig');
	}
}