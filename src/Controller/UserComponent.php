<?php

namespace Endereco\Oxid7Client\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Core\Di\ContainerFacade;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ShopConfigurationDaoBridgeInterface;

class UserComponent extends UserComponent_parent
{
    /**
     * This functions inspects POST and tries to find open sessions in it.
     * In case something is found, doaccountings are sent.
     *
     * This should happen before any validation
     * @see https://github.com/Endereco/endereco-oxid7-client/issues/9
     */
    private function findAndCloseEnderecoSessions()
    {

        $shopConfiguration = ContainerFacade::get(ShopConfigurationDaoBridgeInterface::class)->get();

        $aModuleConfiguration = $shopConfiguration->getModuleConfiguration('endereco-oxid7-client');

        $sApiKy = $aModuleConfiguration->getModuleSetting('sAPIKEY')->getValue();
        $sEndpoint = $aModuleConfiguration->getModuleSetting('sSERVICEURL')->getValue();

        $moduleVersion = $aModuleConfiguration->getVersion();

        $sAgentInfo = "Endereco Oxid7 Client v" . $moduleVersion;

        $bAnyDoAccounting = false;

        if ($_POST) {
            foreach ($_POST as $sVarName => $sVarValue) {
                if ((strpos($sVarName, '_session_counter') !== false) && 0 < intval($sVarValue)) {
                    $sSessionIdName = str_replace('_session_counter', '', $sVarName) . '_session_id';
                    $sSessionId = $_POST[$sSessionIdName];
                    try {
                        $message = [
                            'jsonrpc' => '2.0',
                            'id' => 1,
                            'method' => 'doAccounting',
                            'params' => [
                                'sessionId' => $sSessionId
                            ]
                        ];
                        $client = new Client(['timeout' => 5.0]);

                        $newHeaders = [
                            'Content-Type' => 'application/json',
                            'X-Auth-Key' => $sApiKy,
                            'X-Transaction-Id' => $sSessionId,
                            'X-Transaction-Referer' => $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : __FILE__,
                            'X-Agent' => $sAgentInfo,
                        ];
                        $request = new Request('POST', $sEndpoint, $newHeaders, json_encode($message));
                        $client->send($request);
                        $bAnyDoAccounting = true;
                    } catch (\Exception $e) {
                        // Do nothing.
                    }
                }
            }
        }

        if ($bAnyDoAccounting) {
            try {
                $message = [
                    'jsonrpc' => '2.0',
                    'id' => 1,
                    'method' => 'doConversion',
                    'params' => []
                ];
                $client = new Client(['timeout' => 5.0]);
                $newHeaders = [
                    'Content-Type' => 'application/json',
                    'X-Auth-Key' => $sApiKy,
                    'X-Transaction-Id' => 'not_required',
                    'X-Transaction-Referer' => $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : __FILE__,
                    'X-Agent' => $sAgentInfo,
                ];
                $request = new Request('POST', $sEndpoint, $newHeaders, json_encode($message));
                $client->send($request);
            } catch (\Exception $e) {
                // Do nothing.
                echo $e->getMessage();
            }
        }
    }

    // phpcs:disable
    public function changeuser_testvalues()
    {
        // phpcs:enable
        $this->findAndCloseEnderecoSessions();

        $return = parent::changeuser_testvalues();

        // Hash signature. We assume this logic is executed only from the frontend.
        $oUser = $this->getUser();
        $billigAmsWasInitiated = isset($_POST['billing_ams_session_counter']);
        $billingAmsWasUsed = intval($_POST['billing_ams_session_counter']) > 0;
        if ($oUser && $billigAmsWasInitiated && $billingAmsWasUsed) {
            $hash = $this->calculateHash(
                $oUser->oxuser__oxcountryid->rawValue, // Country ID
                $oUser->oxuser__oxzip->rawValue, // Postal code
                $oUser->oxuser__oxcity->rawValue, // Locality
                $oUser->oxuser__oxstreet->rawValue, // Street name
                $oUser->oxuser__oxstreetnr->rawValue, // House number
                $oUser->oxuser__oxaddinfo->rawValue // Additional info
            );
            $oUser->oxuser__mojoaddresshash->rawValue = $hash;
            $oUser->save();
        }

        $aDelAddress = $this->getDelAddressData();
        $sAddressId = Registry::get(\OxidEsales\Eshop\Core\Request::class)->getRequestEscapedParameter('oxaddressid');
        $shippingAmsWasInitiated = isset($_POST['shipping_ams_session_counter']);
        $shippingAmsWasUsed = intval($_POST['shipping_ams_session_counter']) > 0;
        if ($aDelAddress && $sAddressId && $shippingAmsWasInitiated && $shippingAmsWasUsed) {
            $hash = $this->calculateHash(
                $aDelAddress['oxaddress__oxcountryid'], // Country ID
                $aDelAddress['oxaddress__oxzip'], // Postal code
                $aDelAddress['oxaddress__oxcity'], // Locality
                $aDelAddress['oxaddress__oxstreet'], // Street name
                $aDelAddress['oxaddress__oxstreetnr'], // House number
                $aDelAddress['oxaddress__oxaddinfo'] // Additional info
            );

            $oAddress = oxNew(\OxidEsales\Eshop\Application\Model\Address::class);
            $oAddress->setId($sAddressId);
            $oAddress->load($sAddressId);

            $oAddress->oxaddress__mojoaddresshash->rawValue = $hash;
            $oAddress->save();
        }

        return $return;
    }

    public function changeUser()
    {
        $this->findAndCloseEnderecoSessions();
        $return = parent::changeUser();

        // Hash signature. We assume this logic is executed only from the frontend.
        $oUser = $this->getUser();
        $billigAmsWasInitiated = isset($_POST['billing_ams_session_counter']);
        $billingAmsWasUsed = intval($_POST['billing_ams_session_counter']) > 0;
        if ($oUser && $billigAmsWasInitiated && $billingAmsWasUsed) {
            $hash = $this->calculateHash(
                $oUser->oxuser__oxcountryid->rawValue, // Country ID
                $oUser->oxuser__oxzip->rawValue, // Postal code
                $oUser->oxuser__oxcity->rawValue, // Locality
                $oUser->oxuser__oxstreet->rawValue, // Street name
                $oUser->oxuser__oxstreetnr->rawValue, // House number
                $oUser->oxuser__oxaddinfo->rawValue // Additional info
            );
            $oUser->oxuser__mojoaddresshash->rawValue = $hash;
            $oUser->save();
        }

        $aDelAddress = $this->getDelAddressData();
        $sAddressId = Registry::get(\OxidEsales\Eshop\Core\Request::class)->getRequestEscapedParameter('oxaddressid');
        $shippingAmsWasInitiated = isset($_POST['shipping_ams_session_counter']);
        $shippingAmsWasUsed = intval($_POST['shipping_ams_session_counter']) > 0;
        if ($aDelAddress && $sAddressId && $shippingAmsWasInitiated && $shippingAmsWasUsed) {
            $hash = $this->calculateHash(
                $aDelAddress['oxaddress__oxcountryid'], // Country ID
                $aDelAddress['oxaddress__oxzip'], // Postal code
                $aDelAddress['oxaddress__oxcity'], // Locality
                $aDelAddress['oxaddress__oxstreet'], // Street name
                $aDelAddress['oxaddress__oxstreetnr'], // House number
                $aDelAddress['oxaddress__oxaddinfo'] // Additional info
            );

            $oAddress = oxNew(\OxidEsales\Eshop\Application\Model\Address::class);
            $oAddress->setId($sAddressId);
            $oAddress->load($sAddressId);

            $oAddress->oxaddress__mojoaddresshash->rawValue = $hash;
            $oAddress->save();
        }

        return $return;
    }

    public function createUser()
    {
        $this->findAndCloseEnderecoSessions();

        $return = parent::createUser();

        // Hash signature. We assume this logic is executed only from the frontend.
        $oUser = $this->getUser();
        $billigAmsWasInitiated = isset($_POST['billing_ams_session_counter']);
        $billingAmsWasUsed = intval($_POST['billing_ams_session_counter']) > 0;
        if ($oUser && $billigAmsWasInitiated && $billingAmsWasUsed) {
            $hash = $this->calculateHash(
                $oUser->oxuser__oxcountryid->rawValue, // Country ID
                $oUser->oxuser__oxzip->rawValue, // Postal code
                $oUser->oxuser__oxcity->rawValue, // Locality
                $oUser->oxuser__oxstreet->rawValue, // Street name
                $oUser->oxuser__oxstreetnr->rawValue, // House number
                $oUser->oxuser__oxaddinfo->rawValue // Additional info
            );
            $oUser->oxuser__mojoaddresshash->rawValue = $hash;
            $oUser->save();
        }

        return $return;
    }

    /**
     * Calculates a hash based on the provided address components.
     * This is used to ensure the address integrity.
     *
     * @param string $countryCode Country code of the address.
     * @param string $postalCode Postal code of the address.
     * @param string $locality Locality (city) of the address.
     * @param string $streetName Street name of the address.
     * @param string $buildingNumber Building number of the address.
     * @param string $additionalInfo Additional information of the address.
     * @return string The calculated hash.
     */
    private function calculateHash(
        $countryCode,
        $postalCode,
        $locality,
        $streetName,
        $buildingNumber,
        $additionalInfo
    ) {
        $hashBody = [
            $countryCode,
            $postalCode,
            $locality,
            $streetName,
            $buildingNumber,
            $additionalInfo
        ];
        return hash('sha256', implode('', $hashBody));
    }
}
