<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250702152641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clubs (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, budget DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coaches (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE footballers (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persons (id INT AUTO_INCREMENT NOT NULL, id_club INT DEFAULT NULL, name VARCHAR(100) NOT NULL, surname VARCHAR(100) NOT NULL, salary DOUBLE PRECISION DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_A25CC7D333CE2470 (id_club), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coaches ADD CONSTRAINT FK_C4131765BF396750 FOREIGN KEY (id) REFERENCES persons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE footballers ADD CONSTRAINT FK_30600288BF396750 FOREIGN KEY (id) REFERENCES persons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persons ADD CONSTRAINT FK_A25CC7D333CE2470 FOREIGN KEY (id_club) REFERENCES clubs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coaches DROP FOREIGN KEY FK_C4131765BF396750');
        $this->addSql('ALTER TABLE footballers DROP FOREIGN KEY FK_30600288BF396750');
        $this->addSql('ALTER TABLE persons DROP FOREIGN KEY FK_A25CC7D333CE2470');
        $this->addSql('DROP TABLE clubs');
        $this->addSql('DROP TABLE coaches');
        $this->addSql('DROP TABLE footballers');
        $this->addSql('DROP TABLE persons');
    }
}
