<?php

namespace Louvre\ReservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends Controller
{
	public function indexAction()
	{
		return $this->render('LouvreReservationBundle:Reservation:index.html.twig');
	}

	public function calendarAction()
	{
		return $this->render('LouvreReservationBundle:Reservation:calendar.html.twig');
	}
}
