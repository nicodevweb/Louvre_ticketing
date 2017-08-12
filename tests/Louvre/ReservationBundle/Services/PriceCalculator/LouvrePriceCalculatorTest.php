<?php

namespace Tests\Louvre\ReservationBundle\Services\PriceCalculator;

use DateTime;
use Louvre\ReservationBundle\Entity\Reservation;
use Louvre\ReservationBundle\Entity\Ticket;
use Louvre\ReservationBundle\Services\AgeCalculator\LouvreAgeCalculator;
use Louvre\ReservationBundle\Services\PriceCalculator\LouvrePriceCalculator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LouvrePriceCalculatorTest extends WebTestCase
{
	private $reservation1,
			$reservation2,
			$ticket1,
			$ticket2,
			$ticket3,
			$ageCalculator,
			$priceCalculator,
			$dateToCompare;

	protected function setUp()
	{
		parent::setUp();

		$this->reservation1 = new Reservation('HFJK65FDJ6S37GBHJKD44J');
		$this->reservation1->setType('fullDay');

		$this->reservation2 = new Reservation('HFJK65FDJ6S37GBHJKD44J');
		$this->reservation2->setType('halfDay');

		$this->ticket1 = new Ticket();
		$this->ticket1->setBirthdate(new DateTime('1987-06-15'))
			 		 ->setReducedPrice(false);

		$this->ticket2 = new Ticket();
		$this->ticket2->setBirthdate(new DateTime('1927-01-01'))
			 		 ->setReducedPrice(false);

		$this->ticket3 = new Ticket();
		$this->ticket3->setBirthdate(new DateTime('1974-02-28'))
			 		 ->setReducedPrice(true);


		$this->dateToCompare1 = $this->ticket1->getBirthdate();
		$this->dateToCompare2 = $this->ticket2->getBirthdate();
		$this->dateToCompare3 = $this->ticket3->getBirthdate();
		$this->ageCalculator = new LouvreAgeCalculator();
	}

	/** @test */
	public function shouldCalculatePrice()
	{
		$this->priceCalculator = new LouvrePriceCalculator($this->ageCalculator);

		$price1 = $this->priceCalculator->calculatePrice($this->reservation1->getType(), $this->ticket1->getBirthdate(), $this->ticket1->getReducedPrice());
		$price2 = $this->priceCalculator->calculatePrice($this->reservation2->getType(), $this->ticket2->getBirthdate(), $this->ticket2->getReducedPrice());
		$price3 = $this->priceCalculator->calculatePrice($this->reservation2->getType(), $this->ticket3->getBirthdate(), $this->ticket3->getReducedPrice());

		$this->assertEquals(16, $price1);
		$this->assertEquals(6, $price2);
		$this->assertEquals(5, $price3);
	}
}