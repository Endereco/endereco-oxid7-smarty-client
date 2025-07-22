<?php

namespace Endereco\Oxid7Client\Widget;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Theme;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleConfigurationDaoBridgeInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class IncludeConfigWidget extends \OxidEsales\Eshop\Application\Component\Widget\WidgetController
{
    /**
     * @var string Widget template
     */
    protected $sThisTemplate = 'enderecoconfig_default.tpl';

    /**
     * Getter for protected property.
     *
     * @return string
     */
    public function getThisTemplate()
    {
        // If there is a custom template, return it.
        $customTemplateName = '/endereco/config.tpl';
        $templateFile = Registry::getConfig()->getTemplatePath($customTemplateName, $this->isAdmin());
        if (file_exists($templateFile)) {
            return $customTemplateName;
        }

        // If there is a specific config, return it.
        $aConfigMapping = [
            'flow' => 'enderecoconfig_flow.tpl',
            'wave' => 'enderecoconfig_wave.tpl',
            'azure' => 'enderecoconfig_azure.tpl',
        ];

        $aConfigMapping = $this->extendConfigMapping($aConfigMapping);

        $oTheme = oxNew(Theme::class);
        $oTheme->load($oTheme->getActiveThemeId());

        $counter = 0;
        while (true) {
            $sThemeId = $oTheme->getId();

            if (isset($aConfigMapping[$sThemeId])) {
                return $aConfigMapping[$sThemeId];
            }

            // Get theme parent.
            $oTheme = $oTheme->getParent();

            if (null === $oTheme || (20 < $counter)) {
                break;
            }

            $counter++;
        }

        // If none of the above hits, return default config.
        return $this->sThisTemplate;
    }

    public function extendConfigMapping($configMapping = [])
    {
        return $configMapping;
    }

    /**
     * Render
     *
     * @return string Template name.
     */
    public function render()
    {
        parent::render();

        $oConfig = Registry::getConfig();

        $sOxId = Registry::get(Request::class)->getRequestEscapedParameter('oxid');
        if (!$sOxId) {
            $sOxId = $oConfig->getShopId();
        }

        $languageId = (int)Registry::getLang()->getBaseLanguage();

        $this->_aViewData['enderecoclient'] = [];


        $moduleConfiguration = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleConfigurationDaoBridgeInterface::class)
            ->get("endereco-oxid7-client");

        foreach ($moduleConfiguration->getModuleSettings() as $moduleSetting) {
            $this->_aViewData['enderecoclient'][$moduleSetting->getName()] = $moduleSetting->getValue();
            if ($moduleSetting->getName() == 'sAllowedControllerClasses') {
                $this->_aViewData['enderecoclient']['aAllowedControllerClasses'] = explode(',', $moduleSetting->getValue());
            }
        }

        if (property_exists($this, '_aViewParams')) {
            $this->_aViewData['enderecoclient']['sControllerClass'] = $this->_aViewParams['curClass'];
        } else {
            $this->_aViewData['enderecoclient']['sControllerClass'] = '';
        }

        $this->_aViewData['enderecoclient']['sModuleVersion'] = $moduleConfiguration->getVersion();

        $oTheme = oxNew(Theme::class);
        $oTheme->load($oTheme->getActiveThemeId());
        $sThemeId = $oTheme->getId();

        $this->_aViewData['enderecoclient']['sThemeName'] = $sThemeId;

        $viewNameGenerator = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\TableViewNameGenerator::class);

        $sCountryTable = $viewNameGenerator->getViewName('oxcountry', $languageId, $sOxId);
        $sql = "SELECT `OXISOALPHA2`, `OXTITLE`, `OXID` FROM {$sCountryTable} WHERE `OXISOALPHA2` <> ''";
        $resultSet = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getAll(
            $sql,
            []
        );
        $aCountries = [];
        $aCountryMapping = [];
        $aCountryMappingReverse = [];

        foreach ($resultSet as $result) {
            $aCountries[$result[0]] = $result[1];
            $aCountryMapping[strtoupper($result[0])] = $result[2];
            $aCountryMappingReverse[$result[2]] = strtoupper($result[0]);
        }
        $this->_aViewData['enderecoclient']['sCountries'] = json_encode($aCountries);
        $this->_aViewData['enderecoclient']['oCountryMapping'] = json_encode($aCountryMapping);
        $this->_aViewData['enderecoclient']['oCountryMappingReverse'] = json_encode($aCountryMappingReverse);

        $sStatesTable = $viewNameGenerator->getViewName('oxstates', $languageId, $sOxId);
        $sql =
            "SELECT `MOJOISO31662`, `OXTITLE`, `OXID`, `OXISOALPHA2` 
            FROM {$sStatesTable} WHERE `MOJOISO31662` <> '' AND `MOJOISO31662` IS NOT NULL";
        $resultSet = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getAll(
            $sql,
            []
        );
        $aStates = [];
        $aStatesMapping = [];
        $aStatesMappingReverse = [];

        foreach ($resultSet as $result) {
            $aStates[$result[0]] = $result[1];
            $aStatesMapping[strtoupper($result[0])] = $result[2];
            $aStatesMappingReverse[$result[3] . '-' . $result[2]] = strtoupper($result[0]);
        }

        $this->_aViewData['enderecoclient']['oSubdivisions'] = json_encode($aStates);
        $this->_aViewData['enderecoclient']['oSubdivisionMapping'] = json_encode($aStatesMapping);
        $this->_aViewData['enderecoclient']['oSubdivisionMappingReverse'] = json_encode($aStatesMappingReverse);

        return $this->getThisTemplate();
    }
}
