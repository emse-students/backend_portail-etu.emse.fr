<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212090411 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD contribute_bde TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE operation CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE img_object CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE booking CHANGE created_at created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking CHANGE created_at created_at DATETIME DEFAULT \'2019-02-12 22:00:00\' NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE created_at created_at DATETIME DEFAULT \'2019-02-12 22:00:00\' NOT NULL');
        $this->addSql('ALTER TABLE img_object CHANGE created_at created_at DATETIME DEFAULT \'2019-02-12 22:00:00\' NOT NULL');
        $this->addSql('ALTER TABLE operation CHANGE created_at created_at DATETIME DEFAULT \'2019-02-12 22:00:00\' NOT NULL');
        $this->addSql('ALTER TABLE users DROP contribute_bde');
    }
}
