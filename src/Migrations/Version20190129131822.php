<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129131822 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE form_input (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_output (id INT AUTO_INCREMENT NOT NULL, form_input_id INT NOT NULL, answer LONGTEXT DEFAULT NULL, INDEX IDX_CE30C03087789F92 (form_input_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_output_option (form_output_id INT NOT NULL, option_id INT NOT NULL, INDEX IDX_2D8D7D66AF09C78C (form_output_id), INDEX IDX_2D8D7D66A7C41D6F (option_id), PRIMARY KEY(form_output_id, option_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, form_input_id INT NOT NULL, value VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5A8600B087789F92 (form_input_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE form_output ADD CONSTRAINT FK_CE30C03087789F92 FOREIGN KEY (form_input_id) REFERENCES form_input (id)');
        $this->addSql('ALTER TABLE form_output_option ADD CONSTRAINT FK_2D8D7D66AF09C78C FOREIGN KEY (form_output_id) REFERENCES form_output (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE form_output_option ADD CONSTRAINT FK_2D8D7D66A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B087789F92 FOREIGN KEY (form_input_id) REFERENCES form_input (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE form_output DROP FOREIGN KEY FK_CE30C03087789F92');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B087789F92');
        $this->addSql('ALTER TABLE form_output_option DROP FOREIGN KEY FK_2D8D7D66AF09C78C');
        $this->addSql('ALTER TABLE form_output_option DROP FOREIGN KEY FK_2D8D7D66A7C41D6F');
        $this->addSql('DROP TABLE form_input');
        $this->addSql('DROP TABLE form_output');
        $this->addSql('DROP TABLE form_output_option');
        $this->addSql('DROP TABLE `option`');
    }
}
