<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190803012944 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD idpartenaire_id INT DEFAULT NULL, ADD idcompte_id INT DEFAULT NULL, ADD nomcomplet VARCHAR(255) DEFAULT NULL, ADD mail VARCHAR(255) DEFAULT NULL, ADD tel INT DEFAULT NULL, ADD adresse VARCHAR(255) DEFAULT NULL, ADD status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E1440477 FOREIGN KEY (idpartenaire_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498CDECFD5 FOREIGN KEY (idcompte_id) REFERENCES compte (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E1440477 ON user (idpartenaire_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6498CDECFD5 ON user (idcompte_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E1440477');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498CDECFD5');
        $this->addSql('DROP INDEX IDX_8D93D649E1440477 ON user');
        $this->addSql('DROP INDEX IDX_8D93D6498CDECFD5 ON user');
        $this->addSql('ALTER TABLE user DROP idpartenaire_id, DROP idcompte_id, DROP nomcomplet, DROP mail, DROP tel, DROP adresse, DROP status');
    }
}
