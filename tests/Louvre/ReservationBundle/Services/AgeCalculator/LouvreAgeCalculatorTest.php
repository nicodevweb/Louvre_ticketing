<?php

namespace Tests\Louvre\ReservationBundle\Services\AgeCalculator;

use DateTime;
use Louvre\ReservationBundle\Entity\Ticket;
use Louvre\ReservationBundle\Services\AgeCalculator\LouvreAgeCalculator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LouvreAgeCalculatorTest extends WebTestCase
{
	private $ticket;

	protected function setUp()
	{
		parent::setUp();

		$this->ticket = new Ticket();
		$this->ticket->setBirthdate(new DateTime('1987-06-15'));
	}

	/** @test */
	public function serviceShouldCalculateAge()
	{
		$dateToCompare = $this->ticket->getBirthdate();

		$ageCalculator = new LouvreAgeCalculator();
		
		$this->assertEquals(30, $ageCalculator->calculateAge($dateToCompare));
	}
}
