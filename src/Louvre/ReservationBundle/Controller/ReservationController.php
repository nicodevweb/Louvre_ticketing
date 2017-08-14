<?php

namespace Louvre\ReservationBundle\Controller;

use Louvre\ReservationBundle\Entity\Reservation;
use Louvre\ReservationBundle\Entity\Ticket;
use Louvre\ReservationBundle\Form\ReservationType;
use Louvre\ReservationBundle\Form\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends Controller
{
	public function indexAction()
	{
		return $this->render('LouvreReservationBundle:Reservation:index.html.twig');
	}

	public function calendarAction(Request $request)
	{
		// New reservation code is created
		$code = $this->get('louvre_reservation.codegenerator')->generateRandomCode();

		// Reservation object is constructed with its code
		$reservation = new Reservation($code);

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

			// Calculate price for each Ticket in Reservation
			foreach ($request->getSession()->get('reservation')->getTickets() as $ticket)
			{
				// Use calculatePrice method from priceCalculator service
				$price = $priceCalculator->calculatePrice($request->getSession()->get('reservation')->getType(), $ticket->getBirthdate(), $ticket->getReducedPrice());

				// Hydrate Ticket with its price
				$ticket->setPrice($price);
			}

			// Count number of tickets in Reservation and set it in Reservation's session
			$nbTickets = $this->get('louvre_reservation.ticketcounter')->countReservationTickets($request->getSession()->get('reservation')->getTickets());
			$request->getSession()->get('reservation')->setNbTickets($nbTickets);

			// Calculate Reservation's total price with PriceCalculator service
			$totalPrice = $this->get('louvre_reservation.pricecalculator')->calculateTotal($request->getSession()->get('reservation')->getTickets());
			$request->getSession()->get('reservation')->setTotalPrice($totalPrice);
			$request->getSession()->set('totalPrice', $totalPrice);

			// Redirection to price and confirmation view
			return $this->redirectToRoute('louvre_reservation_confirmation');
		}

		return $this->render('LouvreReservationBundle:Reservation:ticketing.html.twig', array(
			'reservationForm' => $reservationForm->createView()
		));
	}

	public function confirmationAction(Request $request)
	{
		return $this->render('LouvreReservationBundle:Reservation:confirmation.html.twig');
	}

	public function paymentAction(Request $request)
	{
		return $this->render('LouvreReservationBundle:Reservation:payment.html.twig');
	}

	public function checkoutAction(Request $request)
	{
		\Stripe\Stripe::setApiKey('sk_test_SJviYGmyjoe9FathSOqpy6tF');

        // Get the credit card details submitted by the form
        $token = $request->request->get('stripeToken');

        // Create a charge: this will charge the user's card
        try 
        {
            \Stripe\Charge::create(array(
                'amount' => $request->getSession()->get('totalPrice') * 100, // Amount in cents
                'currency' => 'eur',
                'source' => $token,
                'description' => 'Le musée du Louvre - Paiement'
            ));

            // Get EntityManager
            $em = $this->getDoctrine()->getManager();

            // Reservation is persisted in EntityManager
            $em->persist($request->getSession()->get('reservation'));

            // Each Reservation's Ticket is persisted in EntityManager
            foreach ($request->getSession()->get('reservation')->getTickets() as $ticket)
            {
            	// Set Reservation in Ticket's attribute
            	$ticket->setReservation($request->getSession()->get('reservation'));

            	$em->persist($ticket);
            }

            $em->flush();

            $this->addFlash('success', 'Merci pour votre achat !');

            return $this->redirectToRoute('louvre_reservation_validation');
        } 
        catch(\Stripe\Error\Card $e)
        {
            $this->addFlash('error', 'Votre carte n\'a pas été acceptée.');

            return $this->redirectToRoute('louvre_reservation_payment');
        }
	}

	public function validationAction(Request $request)
	{
		// Get reservation's info to prepare email and session reset before rendering
		$reservation = $request->getSession()->get('reservation');
		$tickets = $reservation->getTickets();

		// Send email with tickets in it
		$mailer = $this->get('swiftmailer.mailer.default');

		$message = (new \Swift_Message('Votre réservation de billets au Musée du Louvre'))
			->setFrom('no-reply-museedulouvre@gmail.com')
			->setTo($request->getSession()->get('reservation')->getEmail())
		;

		$data['logo'] = $message->embed(\Swift_Image::fromPath('http://localhost/Louvre_ticketing/web/images/louvre_logo.jpg'));
			
		$message->setBody(
				$this->renderView(
					'LouvreReservationBundle:Emails:validation.html.twig',
					array(
						'tickets' => $tickets,
						'data' => $data
					)
				),
				'text/html'
			)
		;

		$mailer->send($message);

		// Reset the session data
		$request->getSession()->invalidate();

		return $this->render('LouvreReservationBundle:Reservation:validation.html.twig', array(
			'reservation' => $reservation
		));
	}

	/**
	 * API used in calendarAction
	 *
	 * Use AJAX POST data
	 * Return JsonResponse to give information about a date's number of tickets sold
	 */
	public function getNbTicketsAction(Request $request)
	{
		// Get date chosen in calendar
		$chosenDate = $request->get('chosenDate');

		// Use magic method to get reservations at a precise date
		$reservationsList = $this->getDoctrine()->getManager()->getRepository('LouvreReservationBundle:Reservation')->findByDate($chosenDate);
		$nbTickets = 0;

		foreach ($reservationsList as $reservation)
		{
			$nbTickets += $reservation->getNbTickets();
		}

		// // Ask database if day is sold out
		return ($nbTickets >= 1000) ? new JsonResponse(array('result' => 'sold_out')) : new JsonResponse(array('result' => 'not_sold_out'));
	}
}
