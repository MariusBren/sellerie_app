<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241015081445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD condition_state_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD229EEED8 FOREIGN KEY (condition_state_id) REFERENCES `condition` (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD229EEED8 ON product (condition_state_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD229EEED8');
        $this->addSql('DROP INDEX IDX_D34A04AD229EEED8 ON product');
        $this->addSql('ALTER TABLE product DROP condition_state_id');
    }
}
