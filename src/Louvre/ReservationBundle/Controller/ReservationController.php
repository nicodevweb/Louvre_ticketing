<?php

namespace Louvre\ReservationBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class ReservationController
{
	public function indexAction()
	{
		return new Response('Le "Hello World !" du bundle');
	}
}
