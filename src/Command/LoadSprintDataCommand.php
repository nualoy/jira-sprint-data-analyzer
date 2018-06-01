<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\JiraApiClient;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;
use Nahid\JsonQ\Jsonq;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadSprintDataCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('sprint:load')
            // the short description shown while running "php bin/console list"
            ->setDescription('Loads the data for a given sprint and team')
            ->addArgument('team', InputArgument::REQUIRED, 'The team name')
            ->addArgument('sprint', InputArgument::REQUIRED, 'The sprint name');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $teamKey = $input->getArgument('team');
        $sprintName = $input->getArgument('sprint');

        $cloudClient = new JiraApiClient();
        if (!$sprint = $cloudClient->getSprint($teamKey, $sprintName)) {
            throw new \InvalidArgumentException('Sprint not found');
        }

        $this->getContainer()->

        $output->writeln($sprint->name);

        $startDate = new \DateTime($sprint->startDate);
        $endDate = new \DateTime($sprint->endDate);
        $numDays = $this->getWeekdayDifference($startDate, $endDate);

        $output->writeln($startDate->format('Y-m-d') . ' - ' . $endDate->format('Y-m-d') . " ({$numDays} days)");
        $output->writeln('Goal: ' . $sprint->goal);

        $output->writeln('==========================================================================================');

        $issueService = new IssueService();
        $jql = "project = {$teamKey} AND Sprint = '{$sprintName}'";
        if ($ret = $issueService->search($jql, 0, 100,
            ['summary', 'issuetype', 'status', 'customfield_10025'/*points*/], ['changelog'])) {

            foreach ($ret->getIssues() as $issue) {
                /** @var Issue $issue */
                echo $issue->key . "\t" . $issue->fields->summary . "\t" . $issue->fields->issuetype->name . "\t" .
                    $issue->fields->status->name . "\t" . $issue->fields->customFields['customfield_10025'] . PHP_EOL;

                foreach ($issue->changelog->histories as $change) {
                    foreach ($change->items as $item) {
                        if ($item->field === 'status') {
                            $output->writeln("\t" . (new \DateTime($change->created))->format('Y-m-d') .
                                "\tFrom {$item->fromString} to {$item->toString}");
                        }

                    }
                }
            }

            // var_dump($ret); exit;
        }


        $data = [];


//
//        $queryParam = [
////            'fields' => [  // default: '*all'
////                'summary',
////                'comment',
////            ],
//            'expand' => [
//                'changelog',
//            ]
//        ];
//
//        $issue = $issueService->get('CM-302', $queryParam);
//
//        $data = [$issue];
        $output->writeln(json_encode($data));
    }

    private function getWeekdayDifference(\DateTime $originalStartDate, \DateTime $endDate)
    {

        $startDate = clone $originalStartDate;


        $days = 0;

        while ($startDate->diff($endDate)->days > 0) {
            $days += $startDate->format('N') < 6 ? 1 : 0;
            $startDate = $startDate->add(new \DateInterval("P1D"));
        }

        return $days;
    }
}