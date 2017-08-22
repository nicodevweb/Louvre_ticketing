<?php

namespace Louvre\ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="Louvre\ReservationBundle\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez indiquer votre prénom")
     * @Assert\Type("alpha", message="Votre prénom doit être une chaîne de caractères sans chiffres")
     * @Assert\Length(min = 3, minMessage="Votre prénom doit contenir au moins 3 lettres")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez indiquer votre nom")
     * @Assert\Type("alpha", message="Votre nom doit être une chaîne de caractères sans chiffres")
     * @Assert\Length(min = 3, minMessage="Votre nom doit contenir au moins 3 lettres")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez indiquer votre pays de résidence")
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime")
     * @Assert\NotBlank(message="Vous devez indiquer votre date de naissance")
     * @Assert\DateTime(message="Le format de la date est incorrect")
     */
    private $birthdate;

    /**
     * @ORM\Column(name="reduced_price", type="boolean")
     * @Assert\Type("bool", message="La case cochée doit être soit vraie, soit fausse")
     */
    private $reducedPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="Louvre\ReservationBundle\Entity\Reservation", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservation;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Ticket
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Ticket
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Ticket
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set reducedPrice
     *
     * @param boolean $reducedPrice
     *
     * @return Ticket
     */
    public function setReducedPrice($reducedPrice)
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    /**
     * Get reducedPrice
     *
     * @return boolean
     */
    public function getReducedPrice()
    {
        return $this->reducedPrice;
    }

    /**
     * Set reservation
     *
     * @param \Louvre\ReservationBundle\Entity\Reservation $reservation
     *
     * @return Ticket
     */
    public function setReservation(\Louvre\ReservationBundle\Entity\Reservation $reservation)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \Louvre\ReservationBundle\Entity\Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }
}
