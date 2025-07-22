<input type="hidden" name="deladr[oxaddress__mojoamsts]" value="[{if isset( $deladr.oxaddress__mojoamsts )}][{$deladr.oxaddress__mojoamsts}][{else}][{$delivadr->oxaddress__mojoamsts->value}][{/if}]">
<input type="hidden" name="deladr[oxaddress__mojoamsstatus]" value="[{if isset( $deladr.oxaddress__mojoamsstatus )}][{$deladr.oxaddress__mojoamsstatus}][{else}][{$delivadr->oxaddress__mojoamsstatus->value}][{/if}]">
<input type="hidden" name="deladr[oxaddress__mojoamspredictions]" value="[{if isset( $deladr.oxaddress__mojoamspredictions )}][{$deladr.oxaddress__mojoamspredictions}][{else}][{$delivadr->oxaddress__mojoamspredictions->value}][{/if}]">

<input type="hidden" name="deladr[oxaddress__mojonamescore]" value="[{if isset( $deladr.oxaddress__mojonamescore )}][{$deladr.oxaddress__mojonamescore}][{else}][{$delivadr->oxaddress__mojonamescore->value}][{/if}]">
[{$smarty.block.parent}]

<script>
    enderecoInitAMS(
        {
            countryCode: '[name="deladr[oxaddress__oxcountryid]"]',
            postalCode: '[name="deladr[oxaddress__oxzip]"]',
            locality: '[name="deladr[oxaddress__oxcity]"]',
            streetName: '[name="deladr[oxaddress__oxstreet]"]',
            buildingNumber: '[name="deladr[oxaddress__oxstreetnr]"]',
            additionalInfo: '[name="deladr[oxaddress__oxaddinfo]"]',
            addressStatus: '[name="deladr[oxaddress__mojoamsstatus]"]',
            addressTimestamp: '[name="deladr[oxaddress__mojoamsts]"]',
            addressPredictions: '[name="deladr[oxaddress__mojoamspredictions]"]'
            
        }, 
        {
            name: 'shipping',
            addressType: 'shipping_address'
        }, 
        function(EAO) {
            EAO.waitForAllExtension().then( function(EAO) {
                EAO.onAfterModalRendered.push(function(EAO) {
                    if (!document.querySelector('[name="deladr[oxaddress__oxzip]"]').offsetParent) {
                        if ('billing_address' === EAO.addressType) {
                            if (document.querySelector('#userChangeAddress')) {
                                document.querySelector('#userChangeAddress').click();
                            }
                        } else if ('shipping_address' === EAO.addressType) {
                            if (document.querySelector('.dd-available-addresses .dd-edit-shipping-address')) {
                                document.querySelector('.dd-available-addresses .dd-edit-shipping-address').click();
                            }
                        }
                    }
                })
            }).catch();
        }, 
        true
    );
</script>
<script>
    ( function() {
        var $interval = setInterval( function() {
            if (window.EnderecoIntegrator && window.EnderecoIntegrator.ready) {
                console.log('EnderecoIntegrator.ready');
                window.EnderecoIntegrator.initPersonServices(
                    'deladr[oxaddress__',
                    {
                        name: 'shipping'
                    }
                );
                clearInterval($interval);
            }
        }, 100);
    })();
</script>
