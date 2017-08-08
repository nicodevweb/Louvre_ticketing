<?php

namespace Louvre\ReservationBundle\Services\AgeCalculator;

class LouvreAgeCalculator
{
	// Calculate visitor's age
	public function calculateAge($birthdate)
	{
		$dateNow = new \DateTime();
		$difference = $dateNow->diff($birthdate);

		return $difference->y;
	}
}