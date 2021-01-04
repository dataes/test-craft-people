<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity=Deck::class, inversedBy="user", cascade={"persist", "remove"})
     */
    protected $deck;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @return Deck|null
     */
    public function getDeck(): ?Deck
    {
        return $this->deck;
    }

    /**
     * @param Deck|null $deck
     * @return $this
     */
    public function setDeck(?Deck $deck): self
    {
        $this->deck = $deck;

        return $this;
    }
}