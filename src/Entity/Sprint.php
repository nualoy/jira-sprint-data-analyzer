<?php

namespace App\Entity;

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