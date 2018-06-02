<?php

namespace App\Entity;

class Board
{

    /** @var int */
    private $id;

    /** @var */
    private $name;

    /** @var array<string> */
    private $columns;

    /** @var string */
    private $estimationField;

    /** @var string */
    private $teamKey;

    /**
     * @param \stdClass $data
     * Assumes input as returned by:
     * /rest/agile/1.0/board/{boardId}/configuration
     */
    public function __construct(\stdClass $data)
    {
        $this->id = $data->id;
        $this->name = $data->name;
        $this->columns = array_map(function ($column) {
            return $column->name;
        }, $data->columnConfig->columns);
        $this->estimationField = $data->estimation->field->fieldId;
        $this->teamKey = $data->location->key;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getEstimationField(): string
    {
        return $this->estimationField;
    }

    public function getTeamKey(): string
    {
        return $this->teamKey;
    }
}
