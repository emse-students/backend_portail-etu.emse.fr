<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190206085427 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event ADD event_open TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE booking CHANGE payment_means_id payment_means_id INT DEFAULT NULL, CHANGE paid paid TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE form_output ADD booking_id INT NOT NULL');
        $this->addSql('ALTER TABLE form_output ADD CONSTRAINT FK_CE30C0303301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('CREATE INDEX IDX_CE30C0303301C60 ON form_output (booking_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking CHANGE payment_means_id payment_means_id INT NOT NULL, CHANGE paid paid TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE event DROP event_open');
        $this->addSql('ALTER TABLE form_output DROP FOREIGN KEY FK_CE30C0303301C60');
        $this->addSql('DROP INDEX IDX_CE30C0303301C60 ON form_output');
        $this->addSql('ALTER TABLE form_output DROP booking_id');
    }
}
