<?php

namespace App\Entity;

class LeanBoard
{
    /** @var int */
    private $id;

    /** @var */
    private $name;

    /** @var string */
    private $teamName;

    /**
     * @param \stdClass $data
     * Assumes input as returned by:
     * /rest/agile/1.0/board
     */
    public function __construct(\stdClass $data)
    {
        $this->id = $data->id;
        $this->name = $data->name;
        $this->teamName = $data->location->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTeamName(): string
    {
        return $this->teamName;
    }
}
