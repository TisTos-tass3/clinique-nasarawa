<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260305140107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE examen_demande ADD resultat_texte TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE examen_demande ADD resultat_saisi_le TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE examen_demande ADD resultat_saisi_par_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE examen_demande ADD CONSTRAINT FK_8514B0817FF20340 FOREIGN KEY (resultat_saisi_par_id) REFERENCES utilisateur (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_8514B0817FF20340 ON examen_demande (resultat_saisi_par_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE examen_demande DROP CONSTRAINT FK_8514B0817FF20340');
        $this->addSql('DROP INDEX IDX_8514B0817FF20340');
        $this->addSql('ALTER TABLE examen_demande DROP resultat_texte');
        $this->addSql('ALTER TABLE examen_demande DROP resultat_saisi_le');
        $this->addSql('ALTER TABLE examen_demande DROP resultat_saisi_par_id');
    }
}
