<?php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;

class RegistrationService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly LoggerInterface $logger
    ) {}

    public function registerUser(User $user, string $plainPassword): string
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));

        $user->setRole(['ROLE_USER']);
         
        $user->setCreatedAt(new \DateTimeImmutable());

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->logger->info('User successfully registered', ['email' => $user->getEmail()]);
            return 'Registration successful!';
        } catch (\Exception $e) {
            $this->logger->error('Error registration user', ['error' => $e->getMessage(), 'user' => $user->getEmail()]);
            throw new \RuntimeException('Registration failed. Please try again later.');
        }
    }
}
