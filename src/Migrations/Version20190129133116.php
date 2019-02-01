<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129133116 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE form_input ADD event_id INT NOT NULL');
        $this->addSql('ALTER TABLE form_input ADD CONSTRAINT FK_5B6F961471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_5B6F961471F7E88B ON form_input (event_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE form_input DROP FOREIGN KEY FK_5B6F961471F7E88B');
        $this->addSql('DROP INDEX IDX_5B6F961471F7E88B ON form_input');
        $this->addSql('ALTER TABLE form_input DROP event_id');
    }
}
