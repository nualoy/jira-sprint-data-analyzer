<?php
namespace App\Command;

use App\Entity\Issue;
use App\Entity\Sprint;
use App\Entity\Transition;
use App\Repository\SprintRepository;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadSprintDataCommand extends ContainerAwareCommand
{
    /** @var SprintRepository */
    private $sprintRepository;

    public function __construct(SprintRepository $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setName('sprint:load')
            ->setDescription('Loads the data for a given sprint and team')
            ->addArgument('team', InputArgument::REQUIRED, 'The team name')
            ->addArgument('sprint', InputArgument::REQUIRED, 'The sprint name')
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Output format: text, json');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $teamKey = $input->getArgument('team');
        $sprintName = $input->getArgument('sprint');

        $sprint = null;

        if (!$sprint = $this->sprintRepository->findByName($teamKey, $sprintName)) {
            throw new \InvalidArgumentException('Sprint not found');
        }

        $input->getOption('format') === 'json' ?
            $this->outputJson($sprint, $output) :
            $this->outputText($sprint, $output);

        return 0;
    }

    private function outputJson(Sprint $sprint, OutputInterface $output): void
    {
        $serializer = SerializerBuilder::create()->build();
        $output->writeln($serializer->serialize($sprint, 'json'));
    }

    private function outputText(Sprint $sprint, OutputInterface $output): void
    {
        // Board

        $output->writeln($sprint->getBoard()->getName());
        $output->writeln(implode("\t", $sprint->getBoard()->getColumns()));

        $output->writeln('==========================================================================================');

        // Sprint

        $output->writeln($sprint->getName());
        $output->writeln($sprint->getStartDate()->format('Y-m-d') . ' - ' . $sprint->getEndDate()->format('Y-m-d'));
        $output->writeln('Goal: ' . $sprint->getGoal());

        $output->writeln('==========================================================================================');

        // Issues
        foreach ($sprint->getIssues() as $issue) {

            /** @var Issue $issue */
            $output->writeln($issue->getKey() . "\t" . $issue->getSummary() . "\t" . $issue->getType() . "\t" .
                $issue->getStatus() . "\t" . $issue->getEstimate());

            /** @var Transition $transition */
            foreach ($issue->getTransitions() as $transition) {
                $output->writeln("\t" . $transition->getDate()->format('Y-m-d') .
                    "\t{$transition->getType()}\tFrom {$transition->getFrom()} to {$transition->getTo()}");

            }
        }
    }
}