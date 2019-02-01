<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129144125 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE form_output_option DROP FOREIGN KEY FK_2D8D7D66A7C41D6F');
        $this->addSql('CREATE TABLE form_option (id INT AUTO_INCREMENT NOT NULL, form_input_id INT NOT NULL, o_value VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5868D41E87789F92 (form_input_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE form_option ADD CONSTRAINT FK_5868D41E87789F92 FOREIGN KEY (form_input_id) REFERENCES form_input (id)');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('ALTER TABLE form_output_option ADD CONSTRAINT FK_2D8D7D66A7C41D6F FOREIGN KEY (option_id) REFERENCES form_option (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE form_output_option DROP FOREIGN KEY FK_2D8D7D66A7C41D6F');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, form_input_id INT NOT NULL, o_value VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5A8600B087789F92 (form_input_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B087789F92 FOREIGN KEY (form_input_id) REFERENCES form_input (id)');
        $this->addSql('DROP TABLE form_option');
        $this->addSql('ALTER TABLE form_output_option ADD CONSTRAINT FK_2D8D7D66A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id) ON DELETE CASCADE');
    }
}
