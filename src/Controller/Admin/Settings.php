<?php

namespace Endereco\Oxid7Client\Controller\Admin;

use \GuzzleHttp\Client;
use \GuzzleHttp\Psr7\Request;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleConfigurationDaoBridgeInterface;

class Settings extends \OxidEsales\Eshop\Application\Controller\Admin\AdminController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'endereco_settings.tpl';

    /**
     * Executes parent method parent::render()
     *
     * @return string
     */
    public function render()
    {
        $oConfig = Registry::getConfig();
        parent::render();

        $sOxId = (string)\OxidEsales\Eshop\Core\Registry::getRequest()->getRequestEscapedParameter('oxid');
        if (!$sOxId) {
            $sOxId = $oConfig->getShopId();
        }

        $this->_aViewData['oxid'] = $sOxId;

        $this->_aViewData['cstrs'] = [];

        $moduleConfiguration = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleConfigurationDaoBridgeInterface::class)
            ->get("endereco-oxid7-client");

        foreach ($moduleConfiguration->getModuleSettings() as $moduleSetting) {
            $this->_aViewData['cstrs'][$moduleSetting->getName()] = $moduleSetting->getValue();
            if ($moduleSetting->getName() == 'sAllowedControllerClasses') {
                $this->_aViewData['cstrs']['aAllowedControllerClasses'] = explode(',', $moduleSetting->getValue());
            }
        }


        // Check connection to remote server.
        $sOxId = (string)\OxidEsales\Eshop\Core\Registry::getConfig()->getShopId();
        $sApiKy = $this->_aViewData['cstrs']['sAPIKEY'];
        $sEndpoint = $this->_aViewData['cstrs']['sSERVICEURL'];
        $sAgentInfo = "Endereco Oxid7 Client v" . $moduleConfiguration->getVersion();
        $bHasConnection = false;
        try {
            $message = [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'readinessCheck'
            ];
            $client = new Client(['timeout' => 5.0]);

            $newHeaders = [
                'Content-Type' => 'application/json',
                'X-Auth-Key' => $sApiKy,
                'X-Transaction-Id' => 'not_required',
                'X-Transaction-Referer' => 'Settings.php',
                'X-Agent' => $sAgentInfo,
            ];
            $request = new Request('POST', $sEndpoint, $newHeaders, json_encode($message));
            $client->send($request);
            $bHasConnection = true;

        } catch (\Exception $e) {
            // Do nothing.
        }

        $this->_aViewData['cstrs']['bHasConnection'] = $bHasConnection;

        return $this->_sThisTemplate;
    }


    /**
     * Saves changed modules configuration parameters.
     *
     * @return void
     */
    public function save()
    {
        $oConfig = Registry::getConfig();
        $checkboxes = [
            'sUSEAMS',
            'sCHECKALL',
            'sCHECKPAYPAL',
            'sAMSBLURTRIGGER',
            'sSMARTFILL',
            'bUseEmailservice',
            'bUsePersonalService',
            'bAllowControllerFilter',
            'bPreselectCountry',
            'sAMSSubmitTrigger',
            'sAMSResumeSubmit',
            'bUseCss',
            'bShowDebug',
            'bShowEmailserviceErrors',
            'bChangeFieldsOrder',
            'bAllowCloseModal',
            'bConfirmWithCheckbox',
            'bCorrectTranspositionedNames'
        ];

        $sOxId = \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestEscapedParameter('oxid');
        $aConfStrs = \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestEscapedParameter('cstrs');

        if (
            class_exists(\OxidEsales\EshopCommunity\Internal\Container\ContainerFactory::class)
        ) {
            $moduleSettingBridge = \OxidEsales\EshopCommunity\Internal\Container\ContainerFactory::getInstance()
                ->getContainer()
                ->get(\OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface::class);

            if (is_array($aConfStrs)) {
                foreach ($aConfStrs as $sVarName => $sVarVal) {
                    if (in_array($sVarName, $checkboxes)) {
                        $moduleSettingBridge->save($sVarName, true, 'endereco-oxid7-client');
                    } else {
                        $moduleSettingBridge->save($sVarName, $sVarVal, 'endereco-oxid7-client');
                    }
                }
            }

            foreach ($checkboxes as $checkboxname) {
                if (!isset($aConfStrs[$checkboxname])) {
                    $moduleSettingBridge->save($checkboxname, false, 'endereco-oxid7-client');
                }
            }
        } else {

            if (is_array($aConfStrs)) {
                foreach ($aConfStrs as $sVarName => $sVarVal) {
                    if (in_array($sVarName, $checkboxes)) {
                        $oConfig->saveShopConfVar('bool', $sVarName, true, $sOxId, 'module:endereco-oxid7-client');
                    } else {
                        $oConfig->saveShopConfVar('str', $sVarName, $sVarVal, $sOxId, 'module:endereco-oxid7-client');
                    }
                }
            }

            foreach ($checkboxes as $checkboxname) {
                if (!isset($aConfStrs[$checkboxname])) {
                    $oConfig->saveShopConfVar('bool', $checkboxname, false, $sOxId, 'module:endereco-oxid7-client');
                }
            }
        }

        return;
    }
}
