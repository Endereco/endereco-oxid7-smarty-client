[{if $enderecoclient.sMainColor}]
    <style>
        .endereco-predictions-wrapper .endereco-span--neutral {
            border-bottom: 1px dotted [{$enderecoclient.sMainColor}]!important;
            color: [{$enderecoclient.sMainColor}] !important;
        }
        .endereco-predictions .endereco-predictions__item.endereco-predictions__item.endereco-predictions__item:hover,
        .endereco-predictions .endereco-predictions__item.endereco-predictions__item.endereco-predictions__item.active {
            background-color: [{$enderecoclient.sMainColorBG}] !important;
        }
    </style>
[{/if}]

[{if $enderecoclient.sErrorColor}]
    <style>
        .endereco-modal__header-main {
            color: [{$enderecoclient.sErrorColor}] !important;
        }

        .endereco-address-predictions--original .endereco-address-predictions__label {
            border-color: [{$enderecoclient.sErrorColor}] !important;
        }

        .endereco-address-predictions--original .endereco-span--remove {
            background-color: [{$enderecoclient.sErrorColorBG}] !important;
            border-bottom: 1px solid [{$enderecoclient.sErrorColor}] !important;
        }
    </style>
[{/if}]

[{if $enderecoclient.sSelectionColor}]
    <style>
        .endereco-address-predictions__radio:checked ~ .endereco-address-predictions__label,
        .endereco-address-predictions__item.active .endereco-address-predictions__label {
            border-color: [{$enderecoclient.sSelectionColor}] !important;
        }

        .endereco-address-predictions__radio:checked ~ .endereco-address-predictions__label::before,
        .endereco-address-predictions__item.active .endereco-address-predictions__label::before {
            border-color: [{$enderecoclient.sSelectionColor}] !important;
        }

        .endereco-address-predictions__label::after {
            background-color: [{$enderecoclient.sSelectionColor}] !important;
        }

        .endereco-address-predictions--suggestions .endereco-span--add {
            border-bottom: 1px solid [{$enderecoclient.sSelectionColor}];
            background-color:  [{$enderecoclient.sSelectionColorBG}] !important;
        }
    </style>
[{/if}]

