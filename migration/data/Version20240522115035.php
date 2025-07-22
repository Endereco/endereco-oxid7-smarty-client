<?php
namespace Endereco\Oxid7Client\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522115035 extends AbstractMigration
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
        // Add a column for address hash.
        if (!$schema->getTable('oxuser')->hasColumn('MOJOADDRESSHASH')) {
            $this->addSql(
                "ALTER TABLE `oxuser`
                    ADD `MOJOADDRESSHASH` VARCHAR(64) NOT NULL DEFAULT '';"
            );
        }
        if (!$schema->getTable('oxaddress')->hasColumn('MOJOADDRESSHASH')) {
            $this->addSql(
                "ALTER TABLE `oxaddress`
                    ADD `MOJOADDRESSHASH` VARCHAR(64) NOT NULL DEFAULT '';"
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
        $this->addSql('ALTER TABLE `oxuser` DROP `MOJOADDRESSHASH`;');
        $this->addSql('ALTER TABLE `oxaddress` DROP `MOJOADDRESSHASH`;');

        // Not NULL is buggy and doesn't need to be restored.
    }
}
