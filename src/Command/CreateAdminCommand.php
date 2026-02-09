<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an admin user for the Beeline Banking system',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Check if admin already exists
        $existingAdmin = $this->entityManager->getRepository(Utilisateur::class)
            ->findOneBy(['email' => 'admin@beeline.com']);

        if ($existingAdmin) {
            $io->warning('Admin user already exists!');
            $io->table(
                ['Email', 'Status'],
                [['admin@beeline.com', 'Already exists']]
            );
            return Command::SUCCESS;
        }

        // Create admin user
        $admin = new Utilisateur();
        $admin->setEmail('admin@beeline.com');
        $admin->setNom('Admin');
        $admin->setPrenom('System');
        $admin->setTelephone('+216 00 000 000');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setStatutCompte('active');
        
        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedPassword);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success('Admin user created successfully!');
        $io->table(
            ['Email', 'Password', 'Role', 'Status'],
            [
                ['admin@beeline.com', 'admin123', 'ROLE_ADMIN', 'active']
            ]
        );

        $io->note('You can now login at: http://127.0.0.1:8000/login');
        $io->caution('IMPORTANT: Change the password after first login!');

        return Command::SUCCESS;
    }
}
