<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220606083613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE histoire_commande ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE histoire_commande ADD CONSTRAINT FK_F7196CB8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F7196CB8A76ED395 ON histoire_commande (user_id)');
        $this->addSql('ALTER TABLE product ADD fabricant_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADCBAAAAB3 FOREIGN KEY (fabricant_id) REFERENCES fabricant (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADCBAAAAB3 ON product (fabricant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE histoire_commande DROP FOREIGN KEY FK_F7196CB8A76ED395');
        $this->addSql('DROP INDEX IDX_F7196CB8A76ED395 ON histoire_commande');
        $this->addSql('ALTER TABLE histoire_commande DROP user_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADCBAAAAB3');
        $this->addSql('DROP INDEX IDX_D34A04ADCBAAAAB3 ON product');
        $this->addSql('ALTER TABLE product DROP fabricant_id');
    }
}
