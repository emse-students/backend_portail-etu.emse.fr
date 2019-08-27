<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827192527 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation ADD payment_means_id INT NOT NULL');
        $this->addSql('UPDATE operation SET payment_means_id = 1');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DB2A44DAA FOREIGN KEY (payment_means_id) REFERENCES payment_means (id)');
        $this->addSql('CREATE INDEX IDX_1981A66DB2A44DAA ON operation (payment_means_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DB2A44DAA');
        $this->addSql('DROP INDEX IDX_1981A66DB2A44DAA ON operation');
        $this->addSql('ALTER TABLE operation DROP payment_means_id');
    }
}
