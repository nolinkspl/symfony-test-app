<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200525111729 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE amount DROP CONSTRAINT fk_8ea170424c1ff126');
        $this->addSql('DROP INDEX uniq_8ea170424c1ff126');
        $this->addSql('ALTER TABLE amount DROP conversion_id');
        $this->addSql('ALTER TABLE amount ADD CONSTRAINT FK_8EA1704238248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8EA1704238248176 ON amount (currency_id)');
        $this->addSql('ALTER TABLE conversion ADD CONSTRAINT FK_BD9127449BB17698 FOREIGN KEY (amount_id) REFERENCES amount (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BD9127449BB17698 ON conversion (amount_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conversion DROP CONSTRAINT FK_BD9127449BB17698');
        $this->addSql('DROP INDEX UNIQ_BD9127449BB17698');
        $this->addSql('ALTER TABLE amount DROP CONSTRAINT FK_8EA1704238248176');
        $this->addSql('DROP INDEX UNIQ_8EA1704238248176');
        $this->addSql('ALTER TABLE amount ADD conversion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amount ADD CONSTRAINT fk_8ea170424c1ff126 FOREIGN KEY (conversion_id) REFERENCES amount (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_8ea170424c1ff126 ON amount (conversion_id)');
    }
}
