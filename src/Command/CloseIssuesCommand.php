<?php

namespace App\Command;

use App\Entity\Issue;
use App\Managers\IssueManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CloseIssuesCommand extends Command
{
    protected static $defaultName = 'app:close-issues';
    protected $issueManager;
    protected $em;

    public function __construct(EntityManagerInterface $em, IssueManager $issueManager)
    {
        $this->issueManager = $issueManager;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Marca como resueltas todas las indicencias no resueltas.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $issues = $this->em->getRepository(Issue::class)->findBy(['solved' => false]);
        $total = count($issues);
        foreach ($issues as $issue) {
            $issue->setSolved(true);
            $issue->setSolvedAt(new \DateTime());
            $this->issueManager->update($issue);
        }

        $output->write('Se ha/n actualizado '.$total.' incidencia/s.');
    }
}