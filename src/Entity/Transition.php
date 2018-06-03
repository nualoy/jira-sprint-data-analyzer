<?php
namespace App\Entity;

class Transition
{
    /** @var string */
    private $from;

    /** @var string */
    private $to;

    /** @var string */
    private $type;

    /** @var \DateTime */
    private $date;

    public function __construct(string $dateString, \stdClass $item)
    {
        $this->from = $item->fromString;
        $this->to = $item->toString;
        $this->date = new \DateTime($dateString);
        $this->type = mb_strtolower($item->field);
    }

    /**
     * @return string
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): ?string
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

    public function getType(): string
    {
        return $this->type;
    }
}