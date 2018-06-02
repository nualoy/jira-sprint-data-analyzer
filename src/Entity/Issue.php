<?php

namespace App\Entity;

class Issue
{
    /** @var string */
    private $key;

    /** @var string */
    private $type;

    /** @var string */
    private $summary;

    /** @var int */
    private $estimate;

    /** @var string */
    private $status;

    /** @var array<Transition> */
    private $transitions = [];

    public function __construct(\JiraRestApi\Issue\Issue $data, string $estimateField)
    {
        $field = $data->fields;
        $this->key = $data->key;
        $this->type = $field->issuetype->name;
        $this->summary = $field->summary;
        $this->estimate = $field->customFields[$estimateField] ?? null;
        $this->status = $field->status->name;
    }

    public function addTransition(Transition $transition): void
    {
        $this->transitions[] = $transition;
        usort($this->transitions, function ($first, $second) {
            /**
             * @var Transition $first
             * @var Transition $second
             */
            return $first->getDate() > $second->getDate();
        });
    }


    public function getKey(): string
    {
        return $this->key;
    }

    public function getType(): string
    {
        return $this->type;
    }


    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getEstimate(): ?int
    {
        return $this->estimate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /** @return array<Transition> */
    public function getTransitions(): array
    {
        return $this->transitions;
    }
}