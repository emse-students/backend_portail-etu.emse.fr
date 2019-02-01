<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190116104306 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, association_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, period INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, place VARCHAR(255) DEFAULT NULL, shotgun TINYINT(1) DEFAULT NULL, shotgun_list_length INT DEFAULT NULL, shotgun_waiting_list TINYINT(1) DEFAULT NULL, shotgun_starting_date DATETIME DEFAULT NULL, closing_date DATETIME DEFAULT NULL, INDEX IDX_3BAE0AA7EFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_payment_means (event_id INT NOT NULL, payment_means_id INT NOT NULL, INDEX IDX_A30F027671F7E88B (event_id), INDEX IDX_A30F0276B2A44DAA (payment_means_id), PRIMARY KEY(event_id, payment_means_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE event_payment_means ADD CONSTRAINT FK_A30F027671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_payment_means ADD CONSTRAINT FK_A30F0276B2A44DAA FOREIGN KEY (payment_means_id) REFERENCES payment_means (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event_payment_means DROP FOREIGN KEY FK_A30F027671F7E88B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_payment_means');
    }
}
