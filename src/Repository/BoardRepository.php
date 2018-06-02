<?php

namespace App\Repository;

use App\Entity\Board;
use App\Entity\LeanBoard;
use App\Entity\Sprint;
use App\Service\JiraCloudClient;

class BoardRepository
{
    /** @var JiraCloudClient */
    private $cloudClient;

    public function __construct(JiraCloudClient $cloudClient)
    {
        $this->cloudClient = $cloudClient;
    }

    public function findByTeamKey(string $teamKey): ?Board
    {
        $data = json_decode($this->cloudClient->get('/board'));

        foreach ($data->values as $boardData) {
            if (strpos($boardData->location->name, "({$teamKey})") !== false) {
                return $this->findById($boardData->id);
            }
        }
        return null;
    }

    public function findById(string $id): ?Board
    {
        $data = json_decode($this->cloudClient->get("/board/{$id}/configuration"));
        return new Board($data);
    }

    public function findWithSprintsById($teamKey): ?Board
    {
        $board = null;
        if ($board = $this->findById($teamKey)) {
            $data = json_decode($this->cloudClient->get("/board/{$board->getId()}/sprint"));
            foreach ($data->values as $sprintData) {
                $board->addSprint(new Sprint($sprintData));
            }
        }
        return $board;
    }

    public function findAll(): array
    {
        $data = json_decode($this->cloudClient->get('/board'));
        $boards = [];
        foreach ($data->values as $boardData) {
            $boards[] = new LeanBoard($boardData);
        }
        return $boards;
    }
}