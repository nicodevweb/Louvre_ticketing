<?php

namespace Tests\Louvre\ReservationBundle\Services\TicketCounter;

use Louvre\ReservationBundle\Entity\Ticket;
use Louvre\ReservationBundle\Services\TicketCounter\LouvreTicketCounter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LouvreCodeGeneratorTest extends WebTestCase
{
	private $counter;

	protected function setUp()
	{
		parent::setUp();

		$this->counter = new LouvreTicketCounter();
	}

	/** @test */
	public function serviceShouldCountTickets()
	{
		$ticket1 = new Ticket();
		$ticket2 = new Ticket();
		$ticket3 = new Ticket();

		$tickets1 = array();
		$tickets1[] = $ticket1;
		$tickets1[] = $ticket2;
		$tickets1[] = $ticket3;

		$tickets2 = array();
		$tickets2[] = $ticket1;
		$tickets2[] = $ticket2;

		$nbTickets1 = $this->counter->countReservationTickets($tickets1);
		$nbTickets2 = $this->counter->countReservationTickets($tickets2);

		$this->assertEquals(3, $nbTickets1);
		$this->assertEquals(2, $nbTickets2);
	}
}
