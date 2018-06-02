<?php

namespace App\Entity;

use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

class Sprint
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var \DateTime */
    private $startDate;

    /** @var \DateTime */
    private $endDate;

    /** @var string */
    private $goal;

    /**
     * @var array<\DateTime>
     * @Exclude
     */
    private $days = [];

    /** @var Board */
    private $board;

    /** @var array<Issue> */
    private $issues;

    /**
     * @param \stdClass $data
     * Assumes input as returned by:
     * /rest/agile/1.0/sprint/{sprintId}
     */
    public function __construct(\stdClass $data)
    {
        $this->id = $data->id;
        $this->name = $data->name;
        $this->startDate = new \DateTime($data->startDate);
        $this->endDate = new \DateTime($data->endDate);
        $this->goal = $data->goal;
        $this->days = $this->calculateDays();
    }

    private function calculateDays(): array
    {
        $date = clone $this->getStartDate();
        $days = [];

        while ($date->diff($this->getEndDate())->days > 0) {
            if ($date->format('N') < 6) { // weekday
                $days[] = clone $date;
            }
            $date = $date->add(new \DateInterval("P1D"));
        }
        return $days;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function getGoal(): string
    {
        return $this->goal;
    }

    /**
     * @VirtualProperty
     * @Type("int")
     * @SerializedName("duration")
     */
    public function getDuration(): int
    {
        return \count($this->days);
    }

    public function setBoard(Board $board): void
    {
        $this->board = $board;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function addIssue(Issue $issue): void
    {
        $this->issues[] = $issue;
    }

    public function getIssues(): array
    {
        return $this->issues;
    }
}