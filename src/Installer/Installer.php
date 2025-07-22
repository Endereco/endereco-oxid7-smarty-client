<?php

namespace Endereco\Oxid7Client\Installer;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Facts\Config\ConfigFile;

class Installer
{
    private static $configReader;

    public static function onActivate()
    {
        if (is_null(self::$configReader)) {
            self::$configReader = new ConfigFile();
        }

        if (!isset(self::$configReader->bEnderecoUseMigrations) && self::$configReader->bEnderecoUseMigrations) {
            return;
        }

        // Underneath is a fallback in case your system can't use migrations.

        // Extend oxaddress.
        self::addDbField('oxaddress', 'MOJOAMSTS', "varchar(64) NULL");

        // Extend oxaddress.
        self::addDbField('oxaddress', 'MOJOAMSSTATUS', "TEXT NULL");

        // Extend oxaddress.
        self::addDbField('oxaddress', 'MOJOAMSPREDICTIONS', "TEXT NULL");

        // Extend oxuser.
        self::addDbField('oxuser', 'MOJOAMSTS', "varchar(64) NULL");

        // Extend oxuser.
        self::addDbField('oxuser', 'MOJOAMSSTATUS', "TEXT NULL");

        // Extend oxaddress.
        self::addDbField('oxuser', 'MOJOAMSPREDICTIONS', "TEXT NULL");

        // Convert existing ams status to TEXT.
        self::changeDbFieldType('oxuser', 'MOJOAMSSTATUS', 'TEXT');

        self::changeDbFieldType('oxaddress', 'MOJOAMSSTATUS', 'TEXT');

        // Add NAMESCORE
        self::addDbField('oxuser', 'MOJONAMESCORE', "double NOT NULL DEFAULT '1.0'");
        self::addDbField('oxaddress', 'MOJONAMESCORE', "double NOT NULL DEFAULT '1.0'");

        // Extend oxtates.
        self::addDbField('oxstates', 'MOJOISO31662', "char(6) NULL");

        // Fill up missing states iso codes once. In general its better to double check, because the default OXISOALPHA2
        // only allows 2 characters, which is not enough for some countries e.g. Mexico
        $aColumns = DatabaseProvider::getDb()->getAll("SHOW COLUMNS FROM `oxstates` LIKE 'MOJOISO31662';");
        if (0 !== count($aColumns)) {
            $sql = "INSERT INTO `oxstates` (`OXID`, `OXCOUNTRYID`, `MOJOISO31662`)
                    SELECT 
                        `oxstates`.`OXID`, 
                        `oxstates`.`OXCOUNTRYID`,
                        CASE 
                            WHEN `oxstates`.`OXISOALPHA2` REGEXP '^[A-Z]{2}-[A-Z0-9]{1,3}$' 
                                THEN `oxstates`.`OXISOALPHA2`
                            ELSE CONCAT(`oxcountry`.`OXISOALPHA2`, '-', `oxstates`.`OXISOALPHA2`)
                        END AS `MOJOISO31662`
                    FROM `oxstates`
                    JOIN `oxcountry` ON `oxcountry`.`OXID` = `oxstates`.`OXCOUNTRYID`
                    WHERE `oxstates`.`MOJOISO31662` IS NULL
                    ON DUPLICATE KEY UPDATE `MOJOISO31662` = VALUES(`MOJOISO31662`);";
            DatabaseProvider::getDb()->execute($sql);
        }
        unset($aColumns);

        // Add HASH columns
        self::addDbField('oxuser', 'MOJOADDRESSHASH', "VARCHAR(64) NOT NULL DEFAULT ''");
        self::addDbField('oxaddress', 'MOJOADDRESSHASH', "VARCHAR(64) NOT NULL DEFAULT ''");

        // Add columns to oxorder table
        self::addDbField('oxorder', 'MOJOAMSTS', "varchar(64) NULL");
        self::addDbField('oxorder', 'MOJOAMSSTATUS', "TEXT NULL");
        self::addDbField('oxorder', 'MOJOAMSPREDICTIONS', "TEXT NULL");
        self::addDbField('oxorder', 'MOJONAMESCORE', "double NOT NULL DEFAULT '1.0'");
    }

    /**
     * @param $sTableName
     * @param $sFieldName
     * @param $sFieldDefinition
     * @param string $sFieldComment
     */
    protected static function addDbField($sTableName, $sFieldName, $sFieldDefinition, $sFieldComment = '')
    {

        $oDbMetaDataHandler = Registry::get(DbMetaDataHandler::class);
        if (!$oDbMetaDataHandler->fieldExists($sFieldName, $sTableName)) {
            if ($sFieldComment) {
                $sFieldComment = "COMMENT '$sFieldComment'";
            }
            DatabaseProvider::getDb()->execute(
                "ALTER TABLE $sTableName ADD COLUMN $sFieldName $sFieldDefinition $sFieldComment"
            );
        }
    }

    /**
     * @param $sTableName
     * @param $sFieldName
     * @param $sFieldType
     */
    protected static function changeDbFieldType($sTableName, $sFieldName, $sFieldType)
    {
        $aTableFields = DatabaseProvider::getDb()->getAll("SHOW FIELDS FROM $sTableName");
        foreach ($aTableFields as $aField) {
            if (strtolower($aField[0]) == strtolower($sFieldName)) {
                if (strtolower($aField[1]) != strtolower($sFieldType)) {
                    DatabaseProvider::getDb()->execute("ALTER TABLE $sTableName MODIFY $sFieldName $sFieldType");
                    $oDbMetaDataHandler = Registry::get(DbMetaDataHandler::class);
                    $oDbMetaDataHandler->updateViews();
                }
            }
        }
    }
}
