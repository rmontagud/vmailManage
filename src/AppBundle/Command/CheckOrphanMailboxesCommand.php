<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckOrphanMailboxesCommand extends ContainerAwareCommand {
    protected function configure() {
        $this
            ->setName('check-orphan-mailboxes')
            ->setDescription('Compares vmail directory with the database accounts')
            ->addArgument('vmaildir', InputArgument::OPTIONAL, 'vMail directory', '/var/vmail/mailboxes');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $vmaildir = $input->getArgument('vmaildir');

        if (!is_dir($vmaildir)) {
            throw new \RuntimeException($vmaildir.' does not exist');
        }

        $liveAccounts    = $this->liveAccounts();
        $orphanMailboxes = $this->findOrphanedMalboxes($vmaildir, $liveAccounts);

        if (!empty($orphanMailboxes)) {
            $output->writeln([
                '<options=bold>Found orphan virtual mail directories</>',
                implode(PHP_EOL, $orphanMailboxes),
                '',
            ]);
        } else {
            $output->writeln('<options=bold>Nothing to see here, move along</>');
        }
    }

    protected function liveAccounts() {
        // This ain't "the symfony way" but I will do it properly later
        $em = $this->getContainer()->get('doctrine')->getManager();

        $query = "SELECT domain, username FROM accounts";

        $statement = $em->getConnection()->prepare($query);
        $statement->execute();


        $result = $statement->fetchAll();

        $existingAccountsByDomain = [];

        foreach ($result as $r) {
            $existingAccountsByDomain[$r['domain']][] = $r['username'];
        }
        unset($result);
        return $existingAccountsByDomain;
    }

    protected function findOrphanedMalboxes($vmaildir, $liveAccounts) {
        $orphanMailboxes = [];
        foreach (new \DirectoryIterator($vmaildir) as $domainDir) {
            if ($domainDir->isDot()) continue;
            if ($domainDir->isDir()) {
                if (!array_key_exists($domainDir->getFilename(), $liveAccounts)) {
                    continue;
                }
                foreach (new \DirectoryIterator($domainDir->getRealPath()) as $accountDir) {
                    if ($accountDir->isDot()) continue;
                    if (!in_array($accountDir->getFilename(), $liveAccounts[$domainDir->getFilename()])) {
                        $orphanMailboxes[] = $accountDir->getRealPath();
                    }
                }
            }

        }
        return $orphanMailboxes;
    }

}
