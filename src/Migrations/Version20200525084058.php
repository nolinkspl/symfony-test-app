<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200525084058 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE amount_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE conversion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE amount (id INT NOT NULL, conversion_id INT DEFAULT NULL, currency_id INT NOT NULL, amount BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8EA170424C1FF126 ON amount (conversion_id)');
        $this->addSql('CREATE TABLE conversion (id INT NOT NULL, uid VARCHAR(40) DEFAULT NULL, is_executed BOOLEAN NOT NULL, amount_id INT NOT NULL, rate DOUBLE PRECISION DEFAULT NULL, expire_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE currency (id INT NOT NULL, code VARCHAR(3) NOT NULL, rates TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE token (id INT NOT NULL, uid VARCHAR(40) NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE amount ADD CONSTRAINT FK_8EA170424C1FF126 FOREIGN KEY (conversion_id) REFERENCES conversion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE amount DROP CONSTRAINT FK_8EA170424C1FF126');
        $this->addSql('DROP SEQUENCE amount_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE conversion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE currency_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE token_id_seq CASCADE');
        $this->addSql('DROP TABLE amount');
        $this->addSql('DROP TABLE conversion');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE token');
    }
}
