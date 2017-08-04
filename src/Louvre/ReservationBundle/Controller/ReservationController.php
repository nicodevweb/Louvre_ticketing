<?php

namespace Louvre\ReservationBundle\Controller;

use Louvre\ReservationBundle\Entity\Reservation;
use Louvre\ReservationBundle\Entity\Ticket;
use Louvre\ReservationBundle\Form\ReservationType;
use Louvre\ReservationBundle\Form\TicketType;
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
		// Ticketing view reservation form, based on Reservation object's session
		$reservationForm = $this->createForm(ReservationType::class, $request->getSession()->get('reservation'), array('ticket' => true));

		if ($request->isMethod('POST') && $reservationForm->handleRequest($request)->isValid())
		{
			// Call PriceCalculator service
			// $this->get() is Controller's services container ($this->container->get('nom_du_bundle.nomduservice'))
			$priceCalculator = $this->get('louvre_reservation.pricecalculator');
			$reservationSession = $request->getSession()->get('reservation');
			$reservationTickets = $request->getSession()->get('reservation')->getTickets();

			// Calculate price for each Ticket in Reservation
			foreach ($reservationTickets as $ticket)
			{
				// Use calculatePrice method from priceCalculator service
				$price = $priceCalculator->calculatePrice($reservationSession->getType(), $ticket->getBirthdate(), $ticket->getReducedPrice());

				// Set price to Ticket
				$ticket->setPrice($price);
			}

			// Redirection to price and confirmation view
			return $this->redirectToRoute('louvre_reservation_confirmation');
		}

		return $this->render('LouvreReservationBundle:Reservation:ticketing.html.twig', array(
			'reservationForm' => $reservationForm->createView()
		));
	}

	public function confirmationAction(Request $request)
	{
		return $this->render('LouvreReservationBundle:Reservation:confirmation.html.twig', array(

		));
	}

	public function paymentAction(Request $request)
	{
		// Get order total price
		$reservationSession = $request->getSession()->get('reservation');
		$reservationTickets = $request->getSession()->get('reservation')->getTickets();
		$totalPrice = 0;

		foreach ($reservationTickets as $ticket)
		{
			$totalPrice += $ticket->getprice();
		}

		// Convert total price in euros to cents
		$totalPrice = $totalPrice * 100;
		$_SESSION['totalPrice'] = $totalPrice;

		return $this->render('LouvreReservationBundle:Reservation:payment.html.twig', array(
			'totalPrice' => $totalPrice
		));
	}

	public function checkoutAction()
	{
		\Stripe\Stripe::setApiKey('sk_test_SJviYGmyjoe9FathSOqpy6tF');

        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];

        // Create a charge: this will charge the user's card
        try 
        {
            $charge = \Stripe\Charge::create(array(
                'amount' => $_SESSION['totalPrice'], // Amount in cents
                'currency' => 'eur',
                'source' => $token,
                'description' => 'Le musée du Louvre - Paiement'
            ));

            $this->addFlash('success', 'Merci pour votre achat !');

            return $this->redirectToRoute('louvre_reservation_payment');
        } 
        catch(\Stripe\Error\Card $e)
        {
            $this->addFlash('error', 'Votre carte n\'a pas été acceptée.');

            return $this->redirectToRoute('louvre_reservation_payment');
        }
	}
}