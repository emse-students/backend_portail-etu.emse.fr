<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190306162858 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, logo_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, tag VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, last_action_date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_list TINYINT(1) DEFAULT NULL, INDEX IDX_FD8521CCF98F144A (logo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, payment_means_id INT DEFAULT NULL, user_id INT DEFAULT NULL, event_id INT NOT NULL, paid TINYINT(1) NOT NULL, user_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_E00CEDDEB2A44DAA (payment_means_id), INDEX IDX_E00CEDDEA76ED395 (user_id), INDEX IDX_E00CEDDE71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, association_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, price DOUBLE PRECISION DEFAULT NULL, place VARCHAR(255) DEFAULT NULL, shotgun_list_length INT DEFAULT NULL, shotgun_waiting_list TINYINT(1) DEFAULT NULL, shotgun_starting_date DATETIME DEFAULT NULL, closing_date DATETIME DEFAULT NULL, duration VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, event_open TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_3BAE0AA7EFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_payment_means (event_id INT NOT NULL, payment_means_id INT NOT NULL, INDEX IDX_A30F027671F7E88B (event_id), INDEX IDX_A30F0276B2A44DAA (payment_means_id), PRIMARY KEY(event_id, payment_means_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_input (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, INDEX IDX_5B6F961471F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_output (id INT AUTO_INCREMENT NOT NULL, form_input_id INT NOT NULL, booking_id INT NOT NULL, answer LONGTEXT DEFAULT NULL, INDEX IDX_CE30C03087789F92 (form_input_id), INDEX IDX_CE30C0303301C60 (booking_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_output_option (form_output_id INT NOT NULL, option_id INT NOT NULL, INDEX IDX_2D8D7D66AF09C78C (form_output_id), INDEX IDX_2D8D7D66A7C41D6F (option_id), PRIMARY KEY(form_output_id, option_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE img_object (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, booking_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, reason VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_1981A66DA76ED395 (user_id), UNIQUE INDEX UNIQ_1981A66D3301C60 (booking_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_option (id INT AUTO_INCREMENT NOT NULL, form_input_id INT NOT NULL, o_value VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5868D41E87789F92 (form_input_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_means (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, association_id INT NOT NULL, role_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_462CE4F5A76ED395 (user_id), INDEX IDX_462CE4F5EFB9C8A5 (association_id), INDEX IDX_462CE4F5D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, hierarchy INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user_right (role_id INT NOT NULL, user_right_id INT NOT NULL, INDEX IDX_D93CE460D60322AC (role_id), INDEX IDX_D93CE460B41A8C35 (user_right_id), PRIMARY KEY(role_id, user_right_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(191) NOT NULL, login VARCHAR(191) NOT NULL, firstname VARCHAR(191) DEFAULT NULL, lastname VARCHAR(191) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, promo INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, balance DOUBLE PRECISION NOT NULL, contribute_bde TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E9AA08CB10 (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_right (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCF98F144A FOREIGN KEY (logo_id) REFERENCES img_object (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEB2A44DAA FOREIGN KEY (payment_means_id) REFERENCES payment_means (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE event_payment_means ADD CONSTRAINT FK_A30F027671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_payment_means ADD CONSTRAINT FK_A30F0276B2A44DAA FOREIGN KEY (payment_means_id) REFERENCES payment_means (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE form_input ADD CONSTRAINT FK_5B6F961471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE form_output ADD CONSTRAINT FK_CE30C03087789F92 FOREIGN KEY (form_input_id) REFERENCES form_input (id)');
        $this->addSql('ALTER TABLE form_output ADD CONSTRAINT FK_CE30C0303301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE form_output_option ADD CONSTRAINT FK_2D8D7D66AF09C78C FOREIGN KEY (form_output_id) REFERENCES form_output (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE form_output_option ADD CONSTRAINT FK_2D8D7D66A7C41D6F FOREIGN KEY (option_id) REFERENCES form_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE form_option ADD CONSTRAINT FK_5868D41E87789F92 FOREIGN KEY (form_input_id) REFERENCES form_input (id)');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F5EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F5D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE role_user_right ADD CONSTRAINT FK_D93CE460D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user_right ADD CONSTRAINT FK_D93CE460B41A8C35 FOREIGN KEY (user_right_id) REFERENCES user_right (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7EFB9C8A5');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5EFB9C8A5');
        $this->addSql('ALTER TABLE form_output DROP FOREIGN KEY FK_CE30C0303301C60');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D3301C60');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE71F7E88B');
        $this->addSql('ALTER TABLE event_payment_means DROP FOREIGN KEY FK_A30F027671F7E88B');
        $this->addSql('ALTER TABLE form_input DROP FOREIGN KEY FK_5B6F961471F7E88B');
        $this->addSql('ALTER TABLE form_output DROP FOREIGN KEY FK_CE30C03087789F92');
        $this->addSql('ALTER TABLE form_option DROP FOREIGN KEY FK_5868D41E87789F92');
        $this->addSql('ALTER TABLE form_output_option DROP FOREIGN KEY FK_2D8D7D66AF09C78C');
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CCF98F144A');
        $this->addSql('ALTER TABLE form_output_option DROP FOREIGN KEY FK_2D8D7D66A7C41D6F');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEB2A44DAA');
        $this->addSql('ALTER TABLE event_payment_means DROP FOREIGN KEY FK_A30F0276B2A44DAA');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5D60322AC');
        $this->addSql('ALTER TABLE role_user_right DROP FOREIGN KEY FK_D93CE460D60322AC');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA76ED395');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DA76ED395');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5A76ED395');
        $this->addSql('ALTER TABLE role_user_right DROP FOREIGN KEY FK_D93CE460B41A8C35');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_payment_means');
        $this->addSql('DROP TABLE form_input');
        $this->addSql('DROP TABLE form_output');
        $this->addSql('DROP TABLE form_output_option');
        $this->addSql('DROP TABLE img_object');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE form_option');
        $this->addSql('DROP TABLE payment_means');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user_right');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_right');
    }
}
