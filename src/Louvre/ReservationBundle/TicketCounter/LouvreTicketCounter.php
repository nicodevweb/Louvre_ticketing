<?php

namespace Louvre\ReservationBundle\TicketCounter;

class LouvreTicketCounter
{
	/**
	 * Count number of Tickets in Reservation objects
	 *
	 * @param array $tickets Reservation's array containing Tickets
	 * @return int
	 */
	public function countReservationTickets($tickets)
	{
		$index = 0;

		foreach ($tickets as $ticket)
		{
			$index++;
		}

		return $index;
	}	
}
