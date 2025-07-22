<?php
namespace Endereco\Oxid7Client\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221103000 extends AbstractMigration
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function preUp(Schema $schema): void
    {
        $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * @param Schema $schema
     * 
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function up(Schema $schema): void
    {
        // Add a columns to order.
        // Create columns in oxuser.
        if (!$schema->getTable('oxorder')->hasColumn('MOJOAMSTS')) {
            $this->addSql(
                "ALTER TABLE `oxorder`
                ADD `MOJOAMSTS` varchar(64) NOT NULL;"
            );
        }
        if (!$schema->getTable('oxorder')->hasColumn('MOJOAMSSTATUS')) {
            $this->addSql(
                "ALTER TABLE `oxorder`
                ADD `MOJOAMSSTATUS` text NOT NULL;"
            );
        }
        if (!$schema->getTable('oxorder')->hasColumn('MOJOAMSPREDICTIONS')) {
            $this->addSql(
                "ALTER TABLE `oxorder`
                ADD `MOJOAMSPREDICTIONS` text NOT NULL;"
            );
        }

        if (!$schema->getTable('oxorder')->hasColumn('MOJONAMESCORE')) {
            $this->addSql(
                "ALTER TABLE `oxorder`
                ADD `MOJONAMESCORE` double NOT NULL DEFAULT '1.0';"
            );
        }
    }

    /**
     * @param Schema $schema
     * 
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `oxorder` DROP `MOJOAMSTS`;');
        $this->addSql('ALTER TABLE `oxorder` DROP `MOJOAMSSTATUS`;');
        $this->addSql('ALTER TABLE `oxorder` DROP `MOJOAMSPREDICTIONS`;');
        $this->addSql('ALTER TABLE `oxorder` DROP `MOJONAMESCORE`;');
    }
}
