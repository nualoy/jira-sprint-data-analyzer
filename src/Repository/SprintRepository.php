<?php

namespace App\Repository;

use App\Entity\Issue;
use App\Entity\Sprint;
use App\Entity\Transition;
use App\Service\JiraCloudClient;
use JiraRestApi\Issue\IssueService;

class SprintRepository
{
    /** @var JiraCloudClient */
    private $cloudClient;

    /** @var BoardRepository */
    private $boardRepository;

    public function __construct(JiraCloudClient $cloudClient, BoardRepository $boardRepository)
    {
        $this->cloudClient = $cloudClient;
        $this->boardRepository = $boardRepository;
    }

    public function findByName(string $teamKey, string $sprintName): ?Sprint
    {
        if ($board = $this->boardRepository->findByTeamKey($teamKey)) {
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

    public function findById(int $id): ?Sprint
    {
        $sprint = null;
        $data = json_decode($this->cloudClient->get("/sprint/{$id}"));

        if ($board = $this->boardRepository->findById($data->originBoardId)) {
            $sprint = new Sprint($data);
            $sprint->setBoard($board);
            return $this->loadIssues($sprint);
        }
        return $sprint;
    }

    private function loadIssues(Sprint $sprint): Sprint
    {
        $issueService = new IssueService();
        $estimateField = $sprint->getBoard()->getEstimationField();

        if (!$ret = $issueService->search("Sprint = {$sprint->getId()}", 0, 100
            /*@fixme: assuming a max of 100 issues per sprint*/,
            ['summary', 'issuetype', 'status', $estimateField], ['changelog'])) {
            return null;
        }

        foreach ($ret->getIssues() as $jiraIssue) {

            $issue = new Issue($jiraIssue, $estimateField);

            foreach ($jiraIssue->changelog->histories as $change) {
                foreach ($change->items as $item) {

                    if (\in_array($item->field, ['status', 'resolution', 'Sprint', 'Story Points'])) {
                        $transition = new Transition($change->created, $item);
                        $issue->addTransition($transition);
                    }
                }
            }
            $sprint->addIssue($issue);
        }

        return $sprint;
    }
}