<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510074900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users, appointments, barbers, and barber_schedule tables';
    }

    public function up(Schema $schema): void
    {
        // Create barbers table first
        $this->addSql(<<<'SQL'
            CREATE TABLE barbers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role ENUM('ROLE_USER', 'ROLE_ADMIN') DEFAULT 'ROLE_USER',
                active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        SQL);

        // Create users table
        $this->addSql(<<<'SQL'
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role ENUM('ROLE_USER', 'ROLE_ADMIN') DEFAULT 'ROLE_USER',
                phone_number VARCHAR(15),
                instagram_username VARCHAR(255),
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        SQL);

        // Create appointments table (after barbers table)
        $this->addSql(<<<'SQL'
            CREATE TABLE appointments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                barber_id INT,
                service_type ENUM('haircut', 'shave', 'haircut_shave', 'haircut_shave_razor'),
                appointment_time DATETIME,
                price DECIMAL(10, 2),
                status ENUM('booked', 'completed', 'canceled') DEFAULT 'booked',
                payment_status ENUM('pending', 'paid', 'canceled') DEFAULT 'pending',
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (barber_id) REFERENCES barbers(id)
            );
        SQL);

        // Create barber_schedule table (after barbers table)
        $this->addSql(<<<'SQL'
            CREATE TABLE barber_schedule (
                id INT AUTO_INCREMENT PRIMARY KEY,
                barber_id INT,
                day_of_week ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'),
                start_time TIME,
                end_time TIME,
                status ENUM('open', 'closed') DEFAULT 'open',
                FOREIGN KEY (barber_id) REFERENCES barbers(id)
            );
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Drop tables in reverse order
        $this->addSql(<<<'SQL'
            DROP TABLE barber_schedule;
            DROP TABLE appointments;
            DROP TABLE users;
            DROP TABLE barbers;
        SQL);
    }
}
