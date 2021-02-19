<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210218112153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campus (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, villes_no_ville_id INT NOT NULL, nom VARCHAR(30) NOT NULL, rue VARCHAR(30) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, INDEX IDX_2F577D5927E30153 (villes_no_ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, campus_no_campus_id INT NOT NULL, pseudo VARCHAR(30) NOT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, telephone VARCHAR(15) DEFAULT NULL, mail VARCHAR(30) NOT NULL, mot_passe VARCHAR(255) NOT NULL, administrateur TINYINT(1) NOT NULL, actif TINYINT(1) NOT NULL, roles JSON NOT NULL, nom_photo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D79F6B1186CC499D (pseudo), UNIQUE INDEX UNIQ_D79F6B115126AC48 (mail), INDEX IDX_D79F6B112A5DBDD1 (campus_no_campus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant_sortie (participant_id INT NOT NULL, sortie_id INT NOT NULL, INDEX IDX_8E436D739D1C3019 (participant_id), INDEX IDX_8E436D73CC72D953 (sortie_id), PRIMARY KEY(participant_id, sortie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sortie (id INT AUTO_INCREMENT NOT NULL, organisateur_id INT NOT NULL, etats_no_etat_id INT NOT NULL, lieux_no_lieu_id INT NOT NULL, nom VARCHAR(30) NOT NULL, datedebut DATETIME NOT NULL, duree INT DEFAULT NULL, datecloture DATETIME NOT NULL, nbinscriptionsmax INT NOT NULL, descriptioninfos TEXT DEFAULT NULL, etatsortie INT DEFAULT NULL, url_photo VARCHAR(250) DEFAULT NULL, INDEX IDX_3C3FD3F2D936B2FA (organisateur_id), INDEX IDX_3C3FD3F2A04E64FD (etats_no_etat_id), INDEX IDX_3C3FD3F2171DE0C3 (lieux_no_lieu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, code_postal VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D5927E30153 FOREIGN KEY (villes_no_ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B112A5DBDD1 FOREIGN KEY (campus_no_campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE participant_sortie ADD CONSTRAINT FK_8E436D739D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant_sortie ADD CONSTRAINT FK_8E436D73CC72D953 FOREIGN KEY (sortie_id) REFERENCES sortie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2D936B2FA FOREIGN KEY (organisateur_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2A04E64FD FOREIGN KEY (etats_no_etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2171DE0C3 FOREIGN KEY (lieux_no_lieu_id) REFERENCES lieu (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B112A5DBDD1');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2A04E64FD');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2171DE0C3');
        $this->addSql('ALTER TABLE participant_sortie DROP FOREIGN KEY FK_8E436D739D1C3019');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2D936B2FA');
        $this->addSql('ALTER TABLE participant_sortie DROP FOREIGN KEY FK_8E436D73CC72D953');
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D5927E30153');
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE participant_sortie');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE sortie');
        $this->addSql('DROP TABLE ville');
    }
}
