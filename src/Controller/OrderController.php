<?php

namespace Endereco\Oxid7Client\Controller;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Application\Model\Address;
use OxidEsales\Eshop\Application\Model\Country;
use Endereco\Oxid7Client\Component\EnderecoService;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Core\Di\ContainerFacade;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ShopConfigurationDaoBridgeInterface;

class OrderController extends OrderController_parent
{
    /**
     * Renders the order page and checks the addresses if necessary.
     * This method performs address validation for billing and delivery addresses
     * during the order process, especially for PayPal Express checkout.
     *
     * @return string The rendered template name.
     */
    public function render()
    {

        $shopConfiguration = ContainerFacade::get(ShopConfigurationDaoBridgeInterface::class)->get();
        $aModuleConfiguration = $shopConfiguration->getModuleConfiguration('endereco-oxid7-client');

        $bCheckExisting = $aModuleConfiguration->getModuleSetting('sCHECKALL')->getValue();
        $bCheckExistingPayPalExpress = $aModuleConfiguration->getModuleSetting('sCHECKPAYPAL')->getValue();

        if ($this->getIsOrderStep()) {
            // Do we need to check delivery address?

            $oUser = $this->getUser();
            $oDeliveryAddress = $this->getDelAddress();

            $shouldCheck = false;
            $payment = $this->getPayment();

            // Check if its a PayPal Express checkout user
            if (
                ('0000-00-00 00:00:00' === $oUser->oxuser__oxregister->rawValue)
                && ('oxidpaypal' === $payment->getId() || 'oscpaypal_express' === $payment->getId())
                && $bCheckExistingPayPalExpress
            ) {
                $shouldCheck = true;
            } elseif (
                ('0000-00-00 00:00:00' !== $oUser->oxuser__oxregister->rawValue)
                && $bCheckExisting
            ) {
                $shouldCheck = true;
            }

            if ($shouldCheck) {
                $EnderecoService = new EnderecoService();
                $oLang = \OxidEsales\Eshop\Core\Registry::getLang();
                $localLanguage = $oLang->getLanguageAbbr();

                // Check invoice address.
                if ($oUser && $this->isBillingHashMismatch($oUser)) {
                    $oCountry = oxNew(Country::class);
                    $oCountry->load($oUser->oxuser__oxcountryid->rawValue);
                    $countryCode = strtolower($oCountry->oxcountry__oxisoalpha2->rawValue);

                    $billingAddress = array(
                        'countryCode' => $countryCode,
                        '__language' => $localLanguage,
                        'additionalInfo' => $oUser->oxuser__oxaddinfo->rawValue,
                        'postalCode' => $oUser->oxuser__oxzip->rawValue,
                        'locality' => $oUser->oxuser__oxcity->rawValue,
                        'streetName' => $oUser->oxuser__oxstreet->rawValue,
                        'buildingNumber' => $oUser->oxuser__oxstreetnr->rawValue,
                        '__status' => (
                        ('' !== $oUser->oxuser__mojoamsstatus->rawValue)
                            ? $oUser->oxuser__mojoamsstatus->rawValue
                            : ''
                        ),
                        '__predictions' => '',
                        '__timestamp' => '',
                    );

                    // Check.
                    $checkedBillingAddress = $EnderecoService->checkAddress($billingAddress);

                    // Save.
                    if (!empty($checkedBillingAddress['__status'])) {
                        $oUser->oxuser__mojoamsstatus->rawValue = $checkedBillingAddress['__status'];
                        $oUser->oxuser__mojoamsts->rawValue = $checkedBillingAddress['__timestamp'];
                        $oUser->oxuser__mojoamspredictions->rawValue = $checkedBillingAddress['__predictions'];
                        $oUser->oxuser__mojoaddresshash->rawValue = $this->calculateHash(
                            $oUser->oxuser__oxcountryid->rawValue,
                            $oUser->oxuser__oxzip->rawValue,
                            $oUser->oxuser__oxcity->rawValue,
                            $oUser->oxuser__oxstreet->rawValue,
                            $oUser->oxuser__oxstreetnr->rawValue,
                            $oUser->oxuser__oxaddinfo->rawValue
                        );
                        $oUser->save();
                    }
                }

                // Check invoice address.
                if ($oDeliveryAddress && $this->isDeliveryHashMismatch($oDeliveryAddress)) {
                    $oCountry = oxNew(Country::class);
                    $oCountry->load($oDeliveryAddress->oxaddress__oxcountryid->rawValue);
                    $countryCode = strtolower($oCountry->oxcountry__oxisoalpha2->rawValue);

                    $shippingAddress = array(
                        'countryCode' => $countryCode,
                        '__language' => $localLanguage,
                        'additionalInfo' => $oDeliveryAddress->oxaddress__oxaddinfo->rawValue,
                        'postalCode' => $oDeliveryAddress->oxaddress__oxzip->rawValue,
                        'locality' => $oDeliveryAddress->oxaddress__oxcity->rawValue,
                        'streetName' => $oDeliveryAddress->oxaddress__oxstreet->rawValue,
                        'buildingNumber' => $oDeliveryAddress->oxaddress__oxstreetnr->rawValue,
                        '__status' => (
                        ('' !== $oDeliveryAddress->oxaddress__mojoamsstatus->rawValue)
                            ? $oDeliveryAddress->oxaddress__mojoamsstatus->rawValue
                            : ''
                        ),
                        '__predictions' => '',
                        '__timestamp' => '',
                    );

                    // Check.
                    $checkedShippingAddress = $EnderecoService->checkAddress($shippingAddress);

                    // Save.
                    if (!empty($checkedShippingAddress['__status'])) {
                        $oDeliveryAddress->oxaddress__mojoamsstatus->rawValue
                            = $checkedShippingAddress['__status'];
                        $oDeliveryAddress->oxaddress__mojoamsts->rawValue
                            = $checkedShippingAddress['__timestamp'];
                        $oDeliveryAddress->oxaddress__mojoamspredictions->rawValue
                            = $checkedShippingAddress['__predictions'];
                        $oDeliveryAddress->oxaddress__mojoaddresshash->rawValue = $this->calculateHash(
                            $oDeliveryAddress->oxaddress__oxcountryid->rawValue,
                            $oDeliveryAddress->oxaddress__oxzip->rawValue,
                            $oDeliveryAddress->oxaddress__oxcity->rawValue,
                            $oDeliveryAddress->oxaddress__oxstreet->rawValue,
                            $oDeliveryAddress->oxaddress__oxstreetnr->rawValue,
                            $oDeliveryAddress->oxaddress__oxaddinfo->rawValue
                        );
                        $oDeliveryAddress->save();
                    }
                }
            }
        }

        return parent::render();
    }

    /**
     * Checks if the billing address hash is mismatched.
     *
     * @param User $billingAddress The billing address to check.
     * @return bool True if the hash is mismatched, false otherwise.
     */
    private function isBillingHashMismatch($billingAddress)
    {
        $hash = $this->calculateHash(
            $billingAddress->oxuser__oxcountryid->rawValue, // Country ID
            $billingAddress->oxuser__oxzip->rawValue, // Postal code
            $billingAddress->oxuser__oxcity->rawValue, // Locality
            $billingAddress->oxuser__oxstreet->rawValue, // Street name
            $billingAddress->oxuser__oxstreetnr->rawValue, // House number
            $billingAddress->oxuser__oxaddinfo->rawValue // Additional info
        );

        return $hash !== $billingAddress->oxuser__mojoaddresshash->rawValue;
    }

    /**
     * Checks if the delivery address hash is mismatched.
     *
     * @param Address $deliveryAddress The delivery address to check.
     * @return bool True if the hash is mismatched, false otherwise.
     */
    private function isDeliveryHashMismatch($deliveryAddress)
    {
        $hash = $this->calculateHash(
            $deliveryAddress->oxaddress__oxcountryid->rawValue, // Country ID
            $deliveryAddress->oxaddress__oxzip->rawValue, // Postal code
            $deliveryAddress->oxaddress__oxcity->rawValue, // Locality
            $deliveryAddress->oxaddress__oxstreet->rawValue, // Street name
            $deliveryAddress->oxaddress__oxstreetnr->rawValue, // House number
            $deliveryAddress->oxaddress__oxaddinfo->rawValue // Additional info
        );

        return $hash !== $deliveryAddress->oxaddress__mojoaddresshash->rawValue;
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
