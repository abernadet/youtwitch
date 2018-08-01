<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180801075932 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tabo (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, id_channel VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6CA3C2CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE yabo (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, id_channel VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_F4A0E4F1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tabo ADD CONSTRAINT FK_6CA3C2CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE yabo ADD CONSTRAINT FK_F4A0E4F1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD phone VARCHAR(10) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD birthdate DATE NOT NULL');
        $this->addSql('ALTER TABLE chat ADD user_id INT DEFAULT NULL, ADD message LONGTEXT NOT NULL, ADD date_envoie DATETIME NOT NULL, DROP user');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAA76ED395 ON chat (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tabo');
        $this->addSql('DROP TABLE yabo');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAA76ED395');
        $this->addSql('DROP INDEX IDX_659DF2AAA76ED395 ON chat');
        $this->addSql('ALTER TABLE chat ADD user VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, DROP user_id, DROP message, DROP date_envoie');
        $this->addSql('ALTER TABLE user DROP phone, DROP address, DROP birthdate');
    }
}
