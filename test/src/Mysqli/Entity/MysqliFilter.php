<?php

namespace App\Mysqli\Entity;

use CCMBenchmark\Ting\Entity\NotifyProperty;
use CCMBenchmark\Ting\Entity\NotifyPropertyInterface;

class MysqliFilter implements NotifyPropertyInterface
{
    use NotifyProperty;

    private $name;

    private $value;

    private $valuePartial;

    private $valueStart;

    private $valueEnd;

    private $valueWordStart;

    private $valueIpartial;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): MysqliFilter
    {
        $this->propertyChanged('name', $this->name, $name);
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): MysqliFilter
    {
        $this->propertyChanged('value', $this->value, $value);
        $this->value = $value;

        return $this;
    }

    public function getValuePartial(): ?string
    {
        return $this->valuePartial;
    }

    public function setValuePartial(?string $valuePartial): MysqliFilter
    {
        $this->propertyChanged('valuePartial', $this->valuePartial, $valuePartial);
        $this->valuePartial = $valuePartial;

        return $this;
    }

    public function getValueStart(): ?string
    {
        return $this->valueStart;
    }

    public function setValueStart(?string $valueStart): MysqliFilter
    {
        $this->propertyChanged('valueStart', $this->valueStart, $valueStart);
        $this->valueStart = $valueStart;

        return $this;
    }

    public function getValueEnd(): ?string
    {
        return $this->valueEnd;
    }

    public function setValueEnd(?string $valueEnd): MysqliFilter
    {
        $this->propertyChanged('valueEnd', $this->valueEnd, $valueEnd);
        $this->valueEnd = $valueEnd;

        return $this;
    }

    public function getValueWordStart(): ?string
    {
        return $this->valueWordStart;
    }

    public function setValueWordStart(?string $valueWordStart): MysqliFilter
    {
        $this->propertyChanged('valueWordStart', $this->valueWordStart, $valueWordStart);
        $this->valueWordStart = $valueWordStart;

        return $this;
    }

    public function getValueIpartial(): ?string
    {
        return $this->valueIpartial;
    }

    public function setValueIpartial(?string $valueIpartial): MysqliFilter
    {
        $this->propertyChanged('valueWordStart', $this->valueIpartial, $valueIpartial);
        $this->valueIpartial = $valueIpartial;

        return $this;
    }
}
