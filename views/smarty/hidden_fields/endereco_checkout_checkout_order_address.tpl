[{$smarty.block.parent}]
[{assign var="sitepath" value=$oViewConf->getBaseDir()}]
<div style="display: none!important">
    <div>
        <input
                type="text"
                id="endereco-billing-country-code"
                value="[{if $oxcmp_user->oxuser__oxcountryid->value}][{$oxcmp_user->oxuser__oxcountryid->value}][{/if}]"
        >
        <input
                type="text"
                id="endereco-billing-locality"
                value="[{if $oxcmp_user->oxuser__oxcity->value}][{$oxcmp_user->oxuser__oxcity->value}][{/if}]"
        >
        <input
                type="text"
                id="endereco-billing-postal-code"
                value="[{if $oxcmp_user->oxuser__oxzip->value}][{$oxcmp_user->oxuser__oxzip->value}][{/if}]"
        >
        <input
                type="text"
                id="endereco-billing-street-name"
                value="[{if $oxcmp_user->oxuser__oxstreet->value}][{$oxcmp_user->oxuser__oxstreet->value}][{/if}]"
        >
        <input
                type="text"
                id="endereco-billing-building-number"
                value="[{if $oxcmp_user->oxuser__oxstreetnr->value}][{$oxcmp_user->oxuser__oxstreetnr->value}][{/if}]"
        >
        <input
                type="text"
                id="endereco-billing-additional-info"
                value="[{if $oxcmp_user->oxuser__oxaddinfo->value}][{$oxcmp_user->oxuser__oxaddinfo->value}][{/if}]"
        >
        <input
                type="text"
                id="endereco-billing-status"
                value="[{if $oxcmp_user->oxuser__mojoamsstatus->value}][{$oxcmp_user->oxuser__mojoamsstatus->value}][{/if}]"
        >
        <input
                type="text"
                id="endereco-billing-timestamp"
                value="[{if $oxcmp_user->oxuser__mojoamsts->value}][{$oxcmp_user->oxuser__mojoamsts->value}][{/if}]"
        >
        <input
                type="text"
                id="endereco-billing-predictions"
                value="[{if $oxcmp_user->oxuser__mojoamspredictions->value}][{$oxcmp_user->oxuser__mojoamspredictions->value}][{/if}]"
        >
        <script>
            (function() {
                function afterCreateHandler(EAO) {
                    if (!EAO) {
                        return;
                    }

                    const handleAddressConfirmation = function(EAO) {
                        EAO.waitForAllPopupsToClose().then(function() {
                            EAO.waitUntilReady().then(function() {
                                if (window.EnderecoIntegrator && window.EnderecoIntegrator.globalSpace.reloadPage) {
                                    window.EnderecoIntegrator.globalSpace.reloadPage();
                                    window.EnderecoIntegrator.globalSpace.reloadPage = undefined;
                                }
                            }).catch();
                        }).catch();

                        EAO._awaits++;
                        EAO.util.axios({
                            method: 'post',
                            url: '[{$sitepath}]?cl=enderecosaveaddress',
                            data: {
                                method: 'editBillingAddress',
                                params: {
                                    addressId: '[{$oxcmp_user->oxuser__oxid->value}]',
                                    address: EAO.address,
                                    enderecometa: {
                                        ts: EAO.addressTimestamp,
                                        status: EAO.addressStatus,
                                        predictions: EAO.addressPredictions,
                                    }
                                }
                            }
                        }).then(function(response) {
                            window.EnderecoIntegrator.globalSpace.reloadPage = function() {
                                location.reload();
                            }
                        }).catch(function(error) {
                            console.log('Something went wrong.');
                        }).finally(function() {
                            EAO._awaits--;
                        });
                    };

                    EAO.waitForAllExtension().then(function() {
                        EAO.onEditAddress.push(function() {
                            document.querySelectorAll('#orderAddress form')[0].submit();
                        });

                        EAO.onAfterAddressCheckSelected.push(handleAddressConfirmation);
                        EAO.onConfirmAddress.push(handleAddressConfirmation);
                    }).catch();
                }

                enderecoInitAMS(
                    {
                        countryCode: '#endereco-billing-country-code',
                        postalCode: '#endereco-billing-postal-code',
                        locality: '#endereco-billing-locality',
                        streetName: '#endereco-billing-street-name',
                        buildingNumber: '#endereco-billing-building-number',
                        additionalInfo: '#endereco-billing-additional-info',
                        addressStatus: '#endereco-billing-status',
                        addressTimestamp: '#endereco-billing-timestamp',
                        addressPredictions: '#endereco-billing-predictions'
                    },
                    {
                        name: 'billing',
                        addressType: 'billing_address'
                    },
                    afterCreateHandler
                );
            })();
        </script>
    </div>
    <div>
        [{assign var="oDelAdress" value=$oView->getDelAddress()}]
        [{if $oDelAdress}]
        <div>
            <input
                    type="text"
                    id="endereco-shipping-country-code"
                    value="[{if $oDelAdress->oxaddress__oxcountryid->value}][{$oDelAdress->oxaddress__oxcountryid->value}][{/if}]"
            >
            <input
                    type="text"
                    id="endereco-shipping-locality"
                    value="[{if $oDelAdress->oxaddress__oxcity->value}][{$oDelAdress->oxaddress__oxcity->value}][{/if}]"
            >
            <input
                    type="text"
                    id="endereco-shipping-postal-code"
                    value="[{if $oDelAdress->oxaddress__oxzip->value}][{$oDelAdress->oxaddress__oxzip->value}][{/if}]"
            >
            <input
                    type="text"
                    id="endereco-shipping-street-name"
                    value="[{if $oDelAdress->oxaddress__oxstreet->value}][{$oDelAdress->oxaddress__oxstreet->value}][{/if}]"
            >
            <input
                    type="text"
                    id="endereco-shipping-building-number"
                    value="[{if $oDelAdress->oxaddress__oxstreetnr->value}][{$oDelAdress->oxaddress__oxstreetnr->value}][{/if}]"
            >
            <input
                    type="text"
                    id="endereco-shipping-additional-info"
                    value="[{if $oDelAdress->oxaddress__oxaddinfo->value}][{$oDelAdress->oxaddress__oxaddinfo->value}][{/if}]"
            >
            <input
                    type="text"
                    id="endereco-shipping-status"
                    value="[{if $oDelAdress->oxaddress__mojoamsstatus->value}][{$oDelAdress->oxaddress__mojoamsstatus->value}][{/if}]"
            >
            <input
                    type="text"
                    id="endereco-shipping-timestamp"
                    value="[{if $oDelAdress->oxaddress__mojoamsts->value}][{$oDelAdress->oxaddress__mojoamsts->value}][{/if}]"
            >
            <input
                    type="text"
                    id="endereco-shipping-predictions"
                    value="[{if $oDelAdress->oxaddress__mojoamspredictions->value}][{$oDelAdress->oxaddress__mojoamspredictions->value}][{/if}]"
            >
            <script>
                (function() {
                    function afterCreateHandler(EAO) {
                        if (!EAO) {
                            return;
                        }

                        const handleAddressConfirmation = function(EAO) {
                            EAO.waitForAllPopupsToClose().then(function() {
                                EAO.waitUntilReady().then(function() {
                                    if (window.EnderecoIntegrator && window.EnderecoIntegrator.globalSpace.reloadPage) {
                                        window.EnderecoIntegrator.globalSpace.reloadPage();
                                        window.EnderecoIntegrator.globalSpace.reloadPage = undefined;
                                    }
                                }).catch();
                            }).catch();

                            EAO._awaits++;
                            EAO.util.axios({
                                method: 'post',
                                url: '[{$sitepath}]?cl=enderecosaveaddress',
                                data: {
                                    method: 'editShippingAddress',
                                    params: {
                                        addressId: '[{$oDelAdress->oxaddress__oxid->value}]',
                                        address: EAO.address,
                                        enderecometa: {
                                            ts: EAO.addressTimestamp,
                                            status: EAO.addressStatus,
                                            predictions: EAO.addressPredictions,
                                        }
                                    }
                                }
                            }).then(function(response) {
                                window.EnderecoIntegrator.globalSpace.reloadPage = function() {
                                    location.reload();
                                }
                            }).catch(function(error) {
                                console.log('Something went wrong.');
                            }).finally(function() {
                                EAO._awaits--;
                            });
                        };

                        EAO.waitForAllExtension().then(function() {
                            EAO.onEditAddress.push(function() {
                                document.querySelectorAll('#orderAddress form')[1].submit();
                            });

                            EAO.onAfterAddressCheckSelected.push(handleAddressConfirmation);
                            EAO.onConfirmAddress.push(handleAddressConfirmation);
                        }).catch();
                    }

                    enderecoInitAMS(
                        {
                            countryCode: '#endereco-shipping-country-code',
                            postalCode: '#endereco-shipping-postal-code',
                            locality: '#endereco-shipping-locality',
                            streetName: '#endereco-shipping-street-name',
                            buildingNumber: '#endereco-shipping-building-number',
                            addressStatus: '#endereco-shipping-status',
                            addressTimestamp: '#endereco-shipping-timestamp',
                            addressPredictions: '#endereco-shipping-predictions',
                            additionalInfo: '#endereco-shipping-additional-info',
                        },
                        {
                            name: 'shipping',
                            addressType: 'shipping_address'
                        },
                        afterCreateHandler,
                        true
                    );
                })();
            </script>
        </div>
        [{/if}]
    </div>
</div>
