<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181227225444 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE role_right DROP FOREIGN KEY FK_43169D3B54976835');
        $this->addSql('CREATE TABLE role_user_right (role_id INT NOT NULL, user_right_id INT NOT NULL, INDEX IDX_D93CE460D60322AC (role_id), INDEX IDX_D93CE460B41A8C35 (user_right_id), PRIMARY KEY(role_id, user_right_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_right (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE role_user_right ADD CONSTRAINT FK_D93CE460D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user_right ADD CONSTRAINT FK_D93CE460B41A8C35 FOREIGN KEY (user_right_id) REFERENCES user_right (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE `right`');
        $this->addSql('DROP TABLE role_right');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE role_user_right DROP FOREIGN KEY FK_D93CE460B41A8C35');
        $this->addSql('CREATE TABLE `right` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE role_right (role_id INT NOT NULL, right_id INT NOT NULL, INDEX IDX_43169D3BD60322AC (role_id), INDEX IDX_43169D3B54976835 (right_id), PRIMARY KEY(role_id, right_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE role_right ADD CONSTRAINT FK_43169D3B54976835 FOREIGN KEY (right_id) REFERENCES `right` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_right ADD CONSTRAINT FK_43169D3BD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE role_user_right');
        $this->addSql('DROP TABLE user_right');
    }
}
