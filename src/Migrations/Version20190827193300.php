<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827193300 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO operation (`user_id`,`booking_id`,`amount`,`type`,`reason`,`created_at`,`payment_means_id`)
                            SELECT b.user_id, b.id, -e.price, \'event_debit\', e.name, b.created_at, b.payment_means_id
                            FROM booking b, event e
                            WHERE b.event_id = e.id
                            AND b.user_id IS NOT NULL
                            AND b.payment_means_id != 1
                            AND b.paid = True');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM operation WHERE payment_means_id != 1');
    }
}
