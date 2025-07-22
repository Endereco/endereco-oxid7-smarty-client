<?php

namespace Endereco\Oxid7Client\Widget;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleConfigurationDaoBridgeInterface;

class IncludeColorWidget extends \OxidEsales\Eshop\Application\Component\Widget\WidgetController
{
    /**
     * @var string Widget template
     */
    protected $sThisTemplate = 'enderecocolor.tpl';

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
        $this->_aViewData['enderecoclient'] = [];
        $settings = [];

        $moduleConfiguration = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleConfigurationDaoBridgeInterface::class)
            ->get("endereco-oxid7-client");

        foreach ($moduleConfiguration->getModuleSettings() as $moduleSetting) {
            $this->_aViewData['enderecoclient'][$moduleSetting->getName()] = $moduleSetting->getValue();
            $settings[$moduleSetting->getName()] = $moduleSetting->getValue();
        }

        if ($settings['sMainColor']) {
            list($red, $gren, $blue) = $this->hex2rgb($settings['sMainColor']);
            $this->_aViewData['enderecoclient']['sMainColorBG'] = "rgba($red, $gren, $blue, 0.1)";
        }

        if ($settings['sErrorColor']) {
            list($red, $gren, $blue) = $this->hex2rgb($settings['sErrorColor']);
            $this->_aViewData['enderecoclient']['sErrorColorBG'] = "rgba($red, $gren, $blue, 0.125)";
        }

        if ($settings['sSelectionColor']) {
            list($red, $gren, $blue) = $this->hex2rgb($settings['sSelectionColor']);
            $this->_aViewData['enderecoclient']['sSelectionColorBG'] = "rgba($red, $gren, $blue, 0.125)";
        }

        return $this->sThisTemplate;
    }

    private function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return [$r, $g, $b];
    }
}
