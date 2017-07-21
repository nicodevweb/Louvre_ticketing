<?php

namespace Louvre\ReservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends Controller
{
	public function indexAction()
	{
		$content = $this->get('templating')->render('LouvreReservationBundle:Reservation:index.html.twig');

		return new Response($content);
	}
}
