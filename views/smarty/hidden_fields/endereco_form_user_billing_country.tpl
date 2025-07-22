<input type="hidden" name="invadr[oxuser__mojoamsts]" value="[{if isset( $invadr.oxuser__mojoamsts )}][{$invadr.oxuser__mojoamsts}][{else}][{$oxcmp_user->oxuser__mojoamsts->value}][{/if}]">
<input type="hidden" name="invadr[oxuser__mojoamsstatus]" value="[{if isset( $invadr.oxuser__mojoamsstatus )}][{$invadr.oxuser__mojoamsstatus}][{else}][{$oxcmp_user->oxuser__mojoamsstatus->value}][{/if}]">
<input type="hidden" name="invadr[oxuser__mojoamspredictions]" value="[{if isset( $invadr.oxuser__mojoamspredictions )}][{$invadr.oxuser__mojoamspredictions}][{else}][{$oxcmp_user->oxuser__mojoamspredictions->value}][{/if}]">

<input type="hidden" name="invadr[oxuser__mojonamescore]" value="[{if isset( $invadr.oxuser__mojonamescore )}][{$invadr.oxuser__mojonamescore}][{else}][{$oxcmp_user->oxuser__mojonamescore->value}][{/if}]">
[{$smarty.block.parent}]

<script>
    ( function() {
        var $interval = setInterval( function() {
            if (window.EnderecoIntegrator && window.EnderecoIntegrator.ready) {
                var EAO = window.EnderecoIntegrator.initAMS(
                    'invadr[oxuser__',
                    {
                        name: 'billing',
                        addressType: 'billing_address'
                    }
                );

                if (EAO) {
                    EAO.waitForAllExtension().then( function(EAO) {
                        EAO.onAfterModalRendered.push(function(EAO) {
                            if (!document.querySelector('[name="invadr[oxuser__oxzip]"]').offsetParent) {
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
                }

                window.EnderecoIntegrator.initPersonServices(
                    'invadr[oxuser__',
                    {
                        name: 'billing',
                    }
                );
                clearInterval($interval);
            }
        }, 100);
    })();
</script>

