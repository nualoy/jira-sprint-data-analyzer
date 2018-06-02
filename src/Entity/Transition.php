<?php

declare(strict_types=1);

namespace App\Entity;

class Transition
{
    /** @var string */
    private $from;

    /** @var string */
    private $to;

    /** @var \DateTime */
    private $date;

    public function __construct(string $dateString, string $fromString, string $toString)
    {
        $this->from = $fromString;
        $this->to = $toString;
        $this->date = new \DateTime($dateString);
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }
}