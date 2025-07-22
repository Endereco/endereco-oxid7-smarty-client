<?php

namespace Endereco\Oxid7Client\Controller;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Application\Model\Address;

class AddressController extends \OxidEsales\Eshop\Application\Controller\FrontendController
{
    /**
     * @return string
     */
    public function render()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ('editBillingAddress' == $data['method']) {
            // Save billing address.
            $oUser = oxNew(User::class);
            if ($oUser->load($data['params']['addressId'])) {
                $oUser->oxuser__oxzip->rawValue
                    = $data['params']['address']['postalCode']
                    ? $data['params']['address']['postalCode']
                    : $oUser->oxuser__oxzip->rawValue;

                $oUser->oxuser__oxcity->rawValue
                    = $data['params']['address']['locality']
                    ? $data['params']['address']['locality']
                    : $oUser->oxuser__oxcity->rawValue;

                $oUser->oxuser__oxstreet->rawValue
                    = $data['params']['address']['streetName']
                    ? $data['params']['address']['streetName']
                    : $oUser->oxuser__oxstreet->rawValue;

                $oUser->oxuser__oxstreetnr->rawValue
                    = $data['params']['address']['buildingNumber']
                    ? $data['params']['address']['buildingNumber']
                    : $oUser->oxuser__oxstreetnr->rawValue;

                $oUser->oxuser__oxaddinfo->rawValue
                    = $data['params']['address']['additionalInfo']
                    ? $data['params']['address']['additionalInfo']
                    : $oUser->oxuser__oxaddinfo->rawValue;

                $status = implode(',', $data['params']['enderecometa']['status']);
                $oUser->oxuser__mojoamsstatus->rawValue = $status;
                $oUser->oxuser__mojoamsts->rawValue = $data['params']['enderecometa']['ts'];
                $predictions = json_encode($data['params']['enderecometa']['predictions']);
                $oUser->oxuser__mojoamspredictions->rawValue = $predictions;

                // When writing address through endereco modal, always recalculate hash.
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
        }

        if ('editShippingAddress' == $data['method']) {
            // Save shipping address.
            $oAddress = oxNew(Address::class);
            if ($oAddress->load($data['params']['addressId'])) {
                $oAddress->oxaddress__oxzip->rawValue
                    = $data['params']['address']['postalCode']
                    ? $data['params']['address']['postalCode']
                    : $oAddress->oxaddress__oxzip->rawValue;

                $oAddress->oxaddress__oxcity->rawValue
                    = $data['params']['address']['locality']
                    ? $data['params']['address']['locality']
                    : $oAddress->oxaddress__oxcity->rawValue;

                $oAddress->oxaddress__oxstreet->rawValue
                    = $data['params']['address']['streetName']
                    ? $data['params']['address']['streetName']
                    : $oAddress->oxaddress__oxstreet->rawValue;

                $oAddress->oxaddress__oxstreetnr->rawValue
                    = $data['params']['address']['buildingNumber']
                    ? $data['params']['address']['buildingNumber']
                    : $oAddress->oxaddress__oxstreetnr->rawValue;

                $oAddress->oxaddress__oxaddinfo->rawValue
                    = $data['params']['address']['additionalInfo']
                    ? $data['params']['address']['additionalInfo']
                    : $oAddress->oxaddress__oxaddinfo->rawValue;

                $status = implode(',', $data['params']['enderecometa']['status']);
                $oAddress->oxaddress__mojoamsstatus->rawValue = $status;
                $oAddress->oxaddress__mojoamsts->rawValue = $data['params']['enderecometa']['ts'];
                $predictions = json_encode($data['params']['enderecometa']['predictions']);
                $oAddress->oxaddress__mojoamspredictions->rawValue = $predictions;

                // When writing address through endereco modal, always recalculate hash.
                $hash = $this->calculateHash(
                    $oAddress->oxaddress__oxcountryid->rawValue, // Country ID
                    $oAddress->oxaddress__oxzip->rawValue, // Postal code
                    $oAddress->oxaddress__oxcity->rawValue, // Locality
                    $oAddress->oxaddress__oxstreet->rawValue, // Street name
                    $oAddress->oxaddress__oxstreetnr->rawValue, // House number
                    $oAddress->oxaddress__oxaddinfo->rawValue // Additional info
                );
                $oAddress->oxaddress__mojoaddresshash->rawValue = $hash;
                $oAddress->save();
            }
        }

        \OxidEsales\Eshop\Core\Registry::getUtils()->showMessageAndExit('');

        return '';
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
