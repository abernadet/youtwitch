<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180727140032 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tabo ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tabo ADD CONSTRAINT FK_6CA3C2CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6CA3C2CA76ED395 ON tabo (user_id)');
        $this->addSql('ALTER TABLE yabo ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE yabo ADD CONSTRAINT FK_F4A0E4F1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F4A0E4F1A76ED395 ON yabo (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tabo DROP FOREIGN KEY FK_6CA3C2CA76ED395');
        $this->addSql('DROP INDEX IDX_6CA3C2CA76ED395 ON tabo');
        $this->addSql('ALTER TABLE tabo DROP user_id');
        $this->addSql('ALTER TABLE yabo DROP FOREIGN KEY FK_F4A0E4F1A76ED395');
        $this->addSql('DROP INDEX IDX_F4A0E4F1A76ED395 ON yabo');
        $this->addSql('ALTER TABLE yabo DROP user_id');
    }
}
