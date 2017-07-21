<?php

namespace Louvre\ReservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LouvreReservationBundle:Default:index.html.twig');
    }
}
