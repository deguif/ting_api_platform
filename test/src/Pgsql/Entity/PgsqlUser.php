<?php

namespace App\Pgsql\Entity;

use CCMBenchmark\Ting\Entity\NotifyProperty;
use CCMBenchmark\Ting\Entity\NotifyPropertyInterface;

class PgsqlUser implements NotifyPropertyInterface
{
    use NotifyProperty;

    private $firstname;

    private $lastname;

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): PgsqlUser
    {
        $this->propertyChanged('firstname', $this->firstname, $firstname);
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): PgsqlUser
    {
        $this->propertyChanged('lastname', $this->lastname, $lastname);
        $this->lastname = $lastname;

        return $this;
    }
}
