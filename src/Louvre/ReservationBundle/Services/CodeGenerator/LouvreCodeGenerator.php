<?php

namespace Louvre\ReservationBundle\Services\CodeGenerator;

class LouvreCodeGenerator
{
	// Create a random code for Reservation's code
	public function generateRandomCode($length = 30)
	{
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomCode = 'LR-';

		for ($i = 0; $i < $length; $i++)
		{
			$randomCode .= $characters[rand(0, $charactersLength - 1)];
		}

		return $randomCode;
	}
}
