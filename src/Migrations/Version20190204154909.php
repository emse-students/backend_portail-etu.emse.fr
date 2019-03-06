<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190204154909 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, booking_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, reason VARCHAR(255) NOT NULL, INDEX IDX_1981A66DA76ED395 (user_id), UNIQUE INDEX UNIQ_1981A66D3301C60 (booking_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, payment_means_id INT NOT NULL, user_id INT DEFAULT NULL, event_id INT NOT NULL, paid TINYINT(1) DEFAULT NULL, user_name VARCHAR(255) DEFAULT NULL, INDEX IDX_E00CEDDEB2A44DAA (payment_means_id), INDEX IDX_E00CEDDEA76ED395 (user_id), INDEX IDX_E00CEDDE71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEB2A44DAA FOREIGN KEY (payment_means_id) REFERENCES payment_means (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_payment_means ADD CONSTRAINT FK_A30F027671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_payment_means ADD CONSTRAINT FK_A30F0276B2A44DAA FOREIGN KEY (payment_means_id) REFERENCES payment_means (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user_right ADD CONSTRAINT FK_D93CE460D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user_right ADD CONSTRAINT FK_D93CE460B41A8C35 FOREIGN KEY (user_right_id) REFERENCES user_right (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F5EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F5D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE form_output_option ADD CONSTRAINT FK_2D8D7D66AF09C78C FOREIGN KEY (form_output_id) REFERENCES form_output (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE form_output_option ADD CONSTRAINT FK_2D8D7D66A7C41D6F FOREIGN KEY (option_id) REFERENCES form_option (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D3301C60');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE booking');
        $this->addSql('ALTER TABLE event_payment_means DROP FOREIGN KEY FK_A30F027671F7E88B');
        $this->addSql('ALTER TABLE event_payment_means DROP FOREIGN KEY FK_A30F0276B2A44DAA');
        $this->addSql('ALTER TABLE form_output_option DROP FOREIGN KEY FK_2D8D7D66AF09C78C');
        $this->addSql('ALTER TABLE form_output_option DROP FOREIGN KEY FK_2D8D7D66A7C41D6F');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5A76ED395');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5EFB9C8A5');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5D60322AC');
        $this->addSql('ALTER TABLE role_user_right DROP FOREIGN KEY FK_D93CE460D60322AC');
        $this->addSql('ALTER TABLE role_user_right DROP FOREIGN KEY FK_D93CE460B41A8C35');
    }
}
