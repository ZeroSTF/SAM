<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731170621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, bien_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, i_t DOUBLE PRECISION NOT NULL, i_km DOUBLE PRECISION NOT NULL, INDEX IDX_47CC8C92BD95B80F (bien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bien (id INT AUTO_INCREMENT NOT NULL, bien_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, lat DOUBLE PRECISION NOT NULL, lon DOUBLE PRECISION NOT NULL, x INT NOT NULL, y INT NOT NULL, INDEX IDX_45EDC386BD95B80F (bien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, num_tel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur_maintenance (fournisseur_id INT NOT NULL, maintenance_id INT NOT NULL, INDEX IDX_4864977A670C757F (fournisseur_id), INDEX IDX_4864977AF6C202BC (maintenance_id), PRIMARY KEY(fournisseur_id, maintenance_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance (id INT AUTO_INCREMENT NOT NULL, bien_id INT DEFAULT NULL, date DATETIME NOT NULL, date_t DATETIME NOT NULL, details VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_2F84F8E9BD95B80F (bien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance_user (maintenance_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4280B685F6C202BC (maintenance_id), INDEX IDX_4280B685A76ED395 (user_id), PRIMARY KEY(maintenance_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, pwd VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, num_tel VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, date DATETIME NOT NULL, etat VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', is_verified TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id)');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC386BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id)');
        $this->addSql('ALTER TABLE fournisseur_maintenance ADD CONSTRAINT FK_4864977A670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fournisseur_maintenance ADD CONSTRAINT FK_4864977AF6C202BC FOREIGN KEY (maintenance_id) REFERENCES maintenance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id)');
        $this->addSql('ALTER TABLE maintenance_user ADD CONSTRAINT FK_4280B685F6C202BC FOREIGN KEY (maintenance_id) REFERENCES maintenance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maintenance_user ADD CONSTRAINT FK_4280B685A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92BD95B80F');
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC386BD95B80F');
        $this->addSql('ALTER TABLE fournisseur_maintenance DROP FOREIGN KEY FK_4864977A670C757F');
        $this->addSql('ALTER TABLE fournisseur_maintenance DROP FOREIGN KEY FK_4864977AF6C202BC');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9BD95B80F');
        $this->addSql('ALTER TABLE maintenance_user DROP FOREIGN KEY FK_4280B685F6C202BC');
        $this->addSql('ALTER TABLE maintenance_user DROP FOREIGN KEY FK_4280B685A76ED395');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE bien');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE fournisseur_maintenance');
        $this->addSql('DROP TABLE maintenance');
        $this->addSql('DROP TABLE maintenance_user');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
