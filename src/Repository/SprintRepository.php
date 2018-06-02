<?php

namespace App\Repository;

use App\Entity\Board;
use App\Entity\Issue;
use App\Entity\Sprint;
use App\Entity\Transition;
use App\Service\JiraCloudClient;
use JiraRestApi\Issue\IssueService;

class SprintRepository
{
    /** @var JiraCloudClient */
    private $cloudClient;

    public function __construct(JiraCloudClient $cloudClient)
    {
        $this->cloudClient = $cloudClient;
    }

    public function findByName(string $teamKey, string $sprintName): ?Sprint
    {
        if ($board = $this->findBoard($teamKey)) {
            $data = json_decode($this->cloudClient->get("/board/{$board->getId()}/sprint"));

            foreach ($data->values as $sprintData) {

                $sprint = new Sprint($sprintData);

                if ($sprint->getName() === $sprintName || $sprint->getName() === "{$board->getTeamKey()} {$sprintName}") {
                    $sprint->setBoard($board);
                    return $this->loadIssues($sprint);
                }
            }
        }
        return null;
    }

    private function findBoard(string $teamKey): ?Board
    {
        $data = json_decode($this->cloudClient->get('/board'));

        foreach ($data->values as $boardData) {
            if (strpos($boardData->location->name, "({$teamKey})") !== false) {
                $data = json_decode($this->cloudClient->get("/board/{$boardData->id}/configuration"));
                return new Board($data);
            }
        }
        return null;
    }

    private function loadIssues(Sprint $sprint): Sprint
    {
        $issueService = new IssueService();
        $estimateField = $sprint->getBoard()->getEstimationField();

        if (!$ret = $issueService->search("Sprint = {$sprint->getId()}", 0, 100,
            ['summary', 'issuetype', 'status', $estimateField], ['changelog'])) {
            return null;
        }

        foreach ($ret->getIssues() as $jiraIssue) {

            $issue = new Issue($jiraIssue, $estimateField);

            foreach ($jiraIssue->changelog->histories as $change) {
                foreach ($change->items as $item) {
                    if ($item->field === 'status') {
                        $transition = new Transition($change->created, $item->fromString, $item->toString);
                        $issue->addTransition($transition);
                    }
                }
            }
            $sprint->addIssue($issue);
        }
        return $sprint;
    }
}