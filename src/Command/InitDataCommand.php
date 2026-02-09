<?php

namespace App\Command;

use App\Entity\Banque;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:init-data',
    description: 'Initialize sample data for Beeline Banking',
)]
class InitDataCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Create BH BANK
        $bhBank = new Banque();
        $bhBank->setNomBq('BH BANK');
        $bhBank->setSiteWeb('https://www.bhbank.com');
        $bhBank->setTelephoneBq('+216 71 123 456');
        $bhBank->setEmailBq('contact@bhbank.com');
        $bhBank->setLogo('bh-logo.png');
        $bhBank->setStatut('active');
        $bhBank->setDescription('Banque d\'affaires et de services bancaires en Tunisie');

        $this->entityManager->persist($bhBank);

        // You can add more banks here
        $atijariwafa = new Banque();
        $atijariwafa->setNomBq('Attijariwafa Bank');
        $atijariwafa->setSiteWeb('https://www.attijariwafabank.com.tn');
        $atijariwafa->setTelephoneBq('+216 71 962 000');
        $atijariwafa->setEmailBq('contact@attijariwafabank.com.tn');
        $atijariwafa->setLogo('attijariwafa-logo.png');
        $atijariwafa->setStatut('active');
        $atijariwafa->setDescription('Banque de premier plan en Tunisie et en Afrique du Nord');

        $this->entityManager->persist($atijariwafa);

        $biat = new Banque();
        $biat->setNomBq('BIAT');
        $biat->setSiteWeb('https://www.biat.com.tn');
        $biat->setTelephoneBq('+216 71 340 000');
        $biat->setEmailBq('info@biat.com.tn');
        $biat->setLogo('biat-logo.png');
        $biat->setStatut('active');
        $biat->setDescription('Banque Internationale Arabe de Tunisie - Leader du secteur bancaire');

        $this->entityManager->persist($biat);

        $this->entityManager->flush();

        $io->success('Sample banks created successfully!');
        $io->table(
            ['Bank Name', 'Status', 'Email'],
            [
                ['BH BANK', 'active', 'contact@bhbank.com'],
                ['Attijariwafa Bank', 'active', 'contact@attijariwafabank.com.tn'],
                ['BIAT', 'active', 'info@biat.com.tn'],
            ]
        );

        return Command::SUCCESS;
    }
}
