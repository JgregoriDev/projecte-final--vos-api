<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220925111200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genere (id INT AUTO_INCREMENT NOT NULL, genere VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genere_videojoc (genere_id INT NOT NULL, videojoc_id INT NOT NULL, INDEX IDX_D8600574D35A57F1 (genere_id), INDEX IDX_D8600574B606CCF2 (videojoc_id), PRIMARY KEY(genere_id, videojoc_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plataforma (id INT AUTO_INCREMENT NOT NULL, plataforma VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plataforma_videojoc (plataforma_id INT NOT NULL, videojoc_id INT NOT NULL, INDEX IDX_663B00EBEB90E430 (plataforma_id), INDEX IDX_663B00EBB606CCF2 (videojoc_id), PRIMARY KEY(plataforma_id, videojoc_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videojoc (id INT AUTO_INCREMENT NOT NULL, titol VARCHAR(255) NOT NULL, cantitat INT DEFAULT NULL, data_creacio DATE DEFAULT NULL, preu DOUBLE PRECISION DEFAULT NULL, portada VARCHAR(255) DEFAULT NULL, resena VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE genere_videojoc ADD CONSTRAINT FK_D8600574D35A57F1 FOREIGN KEY (genere_id) REFERENCES genere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genere_videojoc ADD CONSTRAINT FK_D8600574B606CCF2 FOREIGN KEY (videojoc_id) REFERENCES videojoc (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plataforma_videojoc ADD CONSTRAINT FK_663B00EBEB90E430 FOREIGN KEY (plataforma_id) REFERENCES plataforma (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plataforma_videojoc ADD CONSTRAINT FK_663B00EBB606CCF2 FOREIGN KEY (videojoc_id) REFERENCES videojoc (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genere_videojoc DROP FOREIGN KEY FK_D8600574D35A57F1');
        $this->addSql('ALTER TABLE genere_videojoc DROP FOREIGN KEY FK_D8600574B606CCF2');
        $this->addSql('ALTER TABLE plataforma_videojoc DROP FOREIGN KEY FK_663B00EBEB90E430');
        $this->addSql('ALTER TABLE plataforma_videojoc DROP FOREIGN KEY FK_663B00EBB606CCF2');
        $this->addSql('DROP TABLE genere');
        $this->addSql('DROP TABLE genere_videojoc');
        $this->addSql('DROP TABLE plataforma');
        $this->addSql('DROP TABLE plataforma_videojoc');
        $this->addSql('DROP TABLE videojoc');
    }
}
