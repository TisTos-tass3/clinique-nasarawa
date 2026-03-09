<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260308135611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD montant_total INT NOT NULL');
        $this->addSql('ALTER TABLE facture ADD montant_paye INT NOT NULL');
        $this->addSql('ALTER TABLE facture ADD reste_apayer INT NOT NULL');
        $this->addSql('ALTER TABLE facture DROP montant');
        $this->addSql('ALTER TABLE facture DROP mode_paiement');
        $this->addSql('ALTER TABLE facture RENAME COLUMN statut_paiement TO statut');
        $this->addSql('ALTER TABLE facture_ligne ADD prescription_prestation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture_ligne ADD CONSTRAINT FK_C5C45334FD5FB388 FOREIGN KEY (prescription_prestation_id) REFERENCES prescription_prestation (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_C5C45334FD5FB388 ON facture_ligne (prescription_prestation_id)');
        $this->addSql('ALTER TABLE paiement ALTER mode TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE prescription_prestation ALTER statut TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE prescription_prestation ALTER a_facturer SET DEFAULT true');
        $this->addSql('ALTER TABLE tarif_prestation ADD service_execution VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD montant DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE facture ADD mode_paiement VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE facture DROP montant_total');
        $this->addSql('ALTER TABLE facture DROP montant_paye');
        $this->addSql('ALTER TABLE facture DROP reste_apayer');
        $this->addSql('ALTER TABLE facture RENAME COLUMN statut TO statut_paiement');
        $this->addSql('ALTER TABLE facture_ligne DROP CONSTRAINT FK_C5C45334FD5FB388');
        $this->addSql('DROP INDEX IDX_C5C45334FD5FB388');
        $this->addSql('ALTER TABLE facture_ligne DROP prescription_prestation_id');
        $this->addSql('ALTER TABLE paiement ALTER mode TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE prescription_prestation ALTER statut TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE prescription_prestation ALTER a_facturer SET DEFAULT false');
        $this->addSql('ALTER TABLE tarif_prestation DROP service_execution');
    }
}
