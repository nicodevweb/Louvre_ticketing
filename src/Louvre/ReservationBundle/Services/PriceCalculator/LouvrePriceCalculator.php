<?php

namespace Louvre\ReservationBundle\Services\PriceCalculator;

use Louvre\ReservationBundle\Services\AgeCalculator\LouvreAgeCalculator;
use Symfony\Component\HttpFoundation\Session\Session;

class LouvrePriceCalculator
{
	// AgeCalculator attribute
	private $ageCalculator;

	// Rate table
	const	RATE_NORMAL = 16,
			RATE_CHILD = 8,
			RATE_BABY = 0,
			RATE_SENIOR = 12,
			RATE_REDUCED = 10;

	// Contruct the calculator with AgeCalculator service

	public function __construct(LouvreAgeCalculator $ageCalculator)
	{
		$this->ageCalculator = $ageCalculator;
	}

	/**
	 * Calculate Ticket price according to :
	 *
	 * @var type The Reservation type
	 * @var birthdate The Ticket birthdate
	 * @var reducedPrice The Ticket reduced price
	 */
	public function calculatePrice($type, $birthdate, $reducedPrice)
	{
		// AgeCalculator service is called to calculate age
		$age = $this->ageCalculator->calculateAge($birthdate);

		switch ($age)
		{
			case $reducedPrice:
				// Return half of the price if ticket type attr = 'halfDay'
				return $type == 'fullDay' ? $this::RATE_REDUCED : ($this::RATE_REDUCED/2);
				break;

			case $age >= 60:
				// Return half of the price if ticket type attr = 'halfDay'
				return $type == 'fullDay' ? $this::RATE_SENIOR : ($this::RATE_SENIOR/2);
				break;

			case $age >= 12 && $age < 60:
				// Return half of the price if ticket type attr = 'halfDay'
				return $type == 'fullDay' ? $this::RATE_NORMAL : ($this::RATE_NORMAL/2);
				break;

			case $age >=4 && $age < 12:
				// Return half of the price if ticket type attr = 'halfDay'
				return $type == 'fullDay' ? $this::RATE_CHILD : ($this::RATE_CHILD/2);
				break;

			case $age < 4:
				// Return half of the price if ticket type attr = 'halfDay'
				return $type =='fullDay' ? $this::RATE_BABY : ($this::RATE_BABY/2);
				break;
		}
	}

	// Calculate total price for a reservation
	public function calculateTotal($tickets)
	{
		// Initialize reservation's total price to 0
		$total = 0;

		// Add each ticket value to total
		foreach ($tickets as $ticket)
		{
			$total += $ticket->getPrice();
		}

		return $total;
	}
}
