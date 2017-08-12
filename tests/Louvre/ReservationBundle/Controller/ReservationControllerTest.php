<?php

namespace Tests\Louvre\ReservationBundle\Controller;

use DateTime;
use Louvre\ReservationBundle\Entity\Reservation;
use Louvre\ReservationBundle\Entity\Ticket;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

class ReservationControllerTest extends WebTestCase
{
	/** @test */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Billetterie du Louvre', $client->getResponse()->getContent());
    }

    /** @test */
    public function confirmationShouldHaveSessionTickets()
    {
    	$reservation = new Reservation('KJGKGFDJGJ32DFDF');
    	$reservation->setEmail('xxx.yyy@zzz.com')
    				->setDate(new DateTime('2018-01-01'))
    				->setType('fullDay');

    	$ticket1 = new Ticket();
    	$ticket1->setFirstName('Gérard')
    			->setLastName('Giroult')
    			->setCountry('FR')
    			->setBirthdate(new DateTime('1972-09-10'))
    			->setReducedPrice(false)
    			->setPrice(16)
    	;

    	$ticket2 = new Ticket();
    	$ticket2->setFirstName('Marcel')
    			->setLastName('Pagnol')
    			->setCountry('FR')
    			->setBirthdate(new DateTime('1927-09-01'))
    			->setReducedPrice(false)
    			->setPrice(12)
    	;

    	$reservation->setTickets()->set('1', $ticket1);
      	$reservation->setTickets()->set('2', $ticket2);

    	$session = new Session(new MockArraySessionStorage());
    	$session->set('reservation', $reservation);
    	$session->set('totalPrice', ($ticket1->getPrice() + $ticket2->getprice()));

    	// Check if session is ready to be sent to the twig page, containing Reservation and Tickets objects
    	$this->assertClassHasAttribute('tickets', Reservation::class);
    	$this->assertEquals('Gérard', $session->get('reservation')->getTickets()->first()->getFirstName());
    	$this->assertEquals('Pagnol', $session->get('reservation')->getTickets()->last()->getLastName());
    	$this->assertEquals(28, $session->get('totalPrice'));
    }
}
