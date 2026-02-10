<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260210181121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE condition_offre (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, taux_special DOUBLE PRECISION DEFAULT NULL, montant_seuil NUMERIC(15, 2) DEFAULT NULL, duree_max INT DEFAULT NULL, condit_num INT DEFAULT NULL, INDEX IDX_E35D62644CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, financement_id INT NOT NULL, nom_fichier VARCHAR(255) NOT NULL, type_document VARCHAR(50) NOT NULL, chemin_stockage VARCHAR(255) NOT NULL, statut_verification VARCHAR(50) NOT NULL, INDEX IDX_D8698A76A737ED74 (financement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, niveau_experience VARCHAR(100) DEFAULT NULL, preferences JSON DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, specialite VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8157AA0FFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE condition_offre ADD CONSTRAINT FK_E35D62644CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76A737ED74 FOREIGN KEY (financement_id) REFERENCES financement (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE agence CHANGE telephone telephone VARCHAR(20) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE banque CHANGE site_web site_web VARCHAR(255) DEFAULT NULL, CHANGE telephone_bq telephone_bq VARCHAR(20) DEFAULT NULL, CHANGE email_bq email_bq VARCHAR(255) DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE financement ADD offre_id INT DEFAULT NULL, ADD type_dmd VARCHAR(50) DEFAULT NULL, CHANGE date_reponse date_reponse DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE financement ADD CONSTRAINT FK_59895F564CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('CREATE INDEX IDX_59895F564CC8505A ON financement (offre_id)');
        $this->addSql('ALTER TABLE offre ADD montant_min NUMERIC(15, 2) DEFAULT NULL, ADD montant_max NUMERIC(15, 2) DEFAULT NULL, DROP conditions, CHANGE type_offre type_offre VARCHAR(100) DEFAULT NULL, CHANGE date_debut date_debut DATE DEFAULT NULL, CHANGE date_fin date_fin DATE DEFAULT NULL, CHANGE taux taux NUMERIC(5, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD duree INT DEFAULT NULL, ADD priorite VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD priorite_defaut VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles JSON NOT NULL, CHANGE telephone telephone VARCHAR(20) DEFAULT NULL, CHANGE cin cin VARCHAR(20) DEFAULT NULL, CHANGE code_postal code_postal VARCHAR(10) DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE available_at available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condition_offre DROP FOREIGN KEY FK_E35D62644CC8505A');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76A737ED74');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FFB88E14F');
        $this->addSql('DROP TABLE condition_offre');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE profile');
        $this->addSql('ALTER TABLE agence CHANGE telephone telephone VARCHAR(20) DEFAULT \'NULL\', CHANGE email email VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE banque CHANGE site_web site_web VARCHAR(255) DEFAULT \'NULL\', CHANGE telephone_bq telephone_bq VARCHAR(20) DEFAULT \'NULL\', CHANGE email_bq email_bq VARCHAR(255) DEFAULT \'NULL\', CHANGE logo logo VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE financement DROP FOREIGN KEY FK_59895F564CC8505A');
        $this->addSql('DROP INDEX IDX_59895F564CC8505A ON financement');
        $this->addSql('ALTER TABLE financement DROP offre_id, DROP type_dmd, CHANGE date_reponse date_reponse DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL, CHANGE available_at available_at DATETIME NOT NULL, CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE offre ADD conditions LONGTEXT DEFAULT NULL, DROP montant_min, DROP montant_max, CHANGE type_offre type_offre VARCHAR(100) DEFAULT \'NULL\', CHANGE date_debut date_debut DATE DEFAULT \'NULL\', CHANGE date_fin date_fin DATE DEFAULT \'NULL\', CHANGE taux taux NUMERIC(5, 2) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE rendez_vous DROP duree, DROP priorite');
        $this->addSql('ALTER TABLE service DROP priorite_defaut');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE telephone telephone VARCHAR(20) DEFAULT \'NULL\', CHANGE cin cin VARCHAR(20) DEFAULT \'NULL\', CHANGE code_postal code_postal VARCHAR(10) DEFAULT \'NULL\', CHANGE photo photo VARCHAR(255) DEFAULT \'NULL\'');
    }
}
