[{assign var="sitepath" value=$oViewConf->getBaseDir()}]
yyyyyyyyyyyyyy
[{if
    !$enderecoclient.bAllowControllerFilter ||
    $enderecoclient.sControllerClass|in_array:$enderecoclient.aAllowedControllerClasses ||
    ('order' == $enderecoclient.sControllerClass && $enderecoclient.sCHECKALL)
}]
    [{assign var="popUpHeadline" value="ENDERECOOXID6CLIENT_POPUP_HEADLINE"|oxmultilangassign}]
    [{assign var="popUpSubline" value="ENDERECOOXID6CLIENT_POPUP_SUBLINE"|oxmultilangassign}]

    [{assign var="mistakeNoPredictionSubline" value="ENDERECOOXID6CLIENT_mistakeNoPredictionSubline"|oxmultilangassign}]
    [{assign var="notFoundSubline" value="ENDERECOOXID6CLIENT_notFoundSubline"|oxmultilangassign}]
    [{assign var="confirmMyAddressCheckbox" value="ENDERECOOXID6CLIENT_confirmMyAddressCheckbox"|oxmultilangassign}]

    [{assign var="yourInput" value="ENDERECOOXID6CLIENT_POPUP_INPUT"|oxmultilangassign}]
    [{assign var="editYourInput" value="ENDERECOOXID6CLIENT_POPUP_EDITINPUT"|oxmultilangassign}]
    [{assign var="ourSuggestions" value="ENDERECOOXID6CLIENT_POPUP_SUGGESTIONS"|oxmultilangassign}]
    [{assign var="useSelected" value="ENDERECOOXID6CLIENT_POPUP_USE"|oxmultilangassign}]

    [{assign var="confirmAddress" value="ENDERECOOXID6CLIENT_confirmAddress"|oxmultilangassign}]
    [{assign var="editAddress" value="ENDERECOOXID6CLIENT_editAddress"|oxmultilangassign}]
    [{assign var="warningText" value="ENDERECOOXID6CLIENT_warningText"|oxmultilangassign}]

    [{assign var="generalAddress" value="ENDERECOOXID6CLIENT_POPUP_HEADLINE_GENERAL_ADDRESS"|oxmultilangassign}]
    [{assign var="billingAddress" value="ENDERECOOXID6CLIENT_POPUP_HEADLINE_BILLING_ADDRESS"|oxmultilangassign}]
    [{assign var="shippingAddress" value="ENDERECOOXID6CLIENT_POPUP_HEADLINE_SHIPPING_ADDRESS"|oxmultilangassign}]
    [{assign var="emailNotCorrect" value="ENDERECOOXID6CLIENT_STATUS_EMAIL_NOT_CORRECT"|oxmultilangassign}]
    [{assign var="emailVantReceive" value="ENDERECOOXID6CLIENT_STATUS_EMAIL_CANT_RECEIVE"|oxmultilangassign}]
    [{assign var="emailSyntaxError" value="ENDERECOOXID6CLIENT_STATUS_EMAIL_SYNTAX_ERROR"|oxmultilangassign}]
    [{assign var="emailNoMx" value="ENDERECOOXID6CLIENT_STATUS_EMAIL_NO_MX"|oxmultilangassign}]

    [{assign var="buildingNumberIsMissing" value="ENDERECOOXID6CLIENT_STATUS_buildingNumberIsMissing"|oxmultilangassign}]
    [{assign var="buildingNumberNotFound" value="ENDERECOOXID6CLIENT_STATUS_buildingNumberNotFound"|oxmultilangassign}]
    [{assign var="streetNameNeedsCorrection" value="ENDERECOOXID6CLIENT_STATUS_streetNameNeedsCorrection"|oxmultilangassign}]
    [{assign var="localityNeedsCorrection" value="ENDERECOOXID6CLIENT_STATUS_localityNeedsCorrection"|oxmultilangassign}]
    [{assign var="postalCodeNeedsCorrection" value="ENDERECOOXID6CLIENT_STATUS_postalCodeNeedsCorrection"|oxmultilangassign}]
    [{assign var="countryCodeNeedsCorrection" value="ENDERECOOXID6CLIENT_STATUS_countryCodeNeedsCorrection"|oxmultilangassign}]

    <script>
        function enderecoLoadAMSConfig() {
            window.EnderecoIntegrator.config.apiUrl = "[{$oViewConf->getModuleUrl('endereco-oxid7-client', 'proxy/io.php')}]";
            window.EnderecoIntegrator.config.apiKey = '[{$enderecoclient.sAPIKEY}]';
            window.EnderecoIntegrator.config.remoteApiUrl = '[{$enderecoclient.sSERVICEURL}]';
            window.EnderecoIntegrator.themeName = '[{$enderecoclient.sThemeName}]';
            window.EnderecoIntegrator.defaultCountry = '[{$enderecoclient.sPreselectableCountries}]';
            window.EnderecoIntegrator.defaultCountrySelect = [{if $enderecoclient.bPreselectCountry }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.showDebugInfo = [{if $enderecoclient.bShowDebug }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.trigger.onblur = [{if $enderecoclient.sAMSBLURTRIGGER }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.trigger.onsubmit = [{if $enderecoclient.sAMSSubmitTrigger }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.ux.resumeSubmit = [{if $enderecoclient.sAMSResumeSubmit }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.ux.smartFill = [{if $enderecoclient.sSMARTFILL }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.ux.checkExisting = [{if $enderecoclient.sCHECKALL }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.ux.changeFieldsOrder = [{if $enderecoclient.bChangeFieldsOrder }]true[{else}]false[{/if}];;
            window.EnderecoIntegrator.config.ux.showEmailStatus = [{if $enderecoclient.bShowEmailserviceErrors }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.ux.useStandardCss = [{if $enderecoclient.bUseCss }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.ux.allowCloseModal = [{if $enderecoclient.bAllowCloseModal }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.ux.confirmWithCheckbox = [{if $enderecoclient.bConfirmWithCheckbox }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.ux.correctTranspositionedNames = [{if $enderecoclient.bCorrectTranspositionedNames }]true[{else}]false[{/if}];
            window.EnderecoIntegrator.config.templates.primaryButtonClasses = 'btn btn-primary';
            window.EnderecoIntegrator.config.templates.secondaryButtonClasses = 'btn btn-secondary';
            window.EnderecoIntegrator.config.texts = {
                'popUpHeadline': '[{$popUpHeadline|escape:quotes}]',
                'popUpSubline': '[{$popUpSubline|escape:quotes}]',
                'mistakeNoPredictionSubline': '[{$mistakeNoPredictionSubline|escape:quotes}]',
                'notFoundSubline': '[{$notFoundSubline|escape:quotes}]',
                'confirmMyAddressCheckbox': '[{$confirmMyAddressCheckbox|escape:quotes}]',
                'yourInput': '[{$yourInput|escape:quotes}]',
                'editYourInput': '[{$editYourInput|escape:quotes}]',
                'ourSuggestions': '[{$ourSuggestions|escape:quotes}]',
                'useSelected': '[{$useSelected|escape:quotes}]',
                'confirmAddress': '[{$confirmAddress|escape:quotes}]',
                'editAddress': '[{$editAddress|escape:quotes}]',
                'warningText': '[{$warningText|escape:quotes}]',
                'popupHeadlines': {
                    'general_address': '[{$generalAddress|escape:quotes}]',
                    'billing_address': '[{$billingAddress|escape:quotes}]',
                    'shipping_address': '[{$shippingAddress|escape:quotes}]'
                },
                'statuses': {
                    'email_not_correct': '[{$emailNotCorrect|escape:quotes}]',
                    'email_cant_receive': '[{$emailVantReceive|escape:quotes}]',
                    'email_syntax_error': '[{$emailSyntaxError|escape:quotes}]',
                    'email_no_mx': '[{$emailNoMx|escape:quotes}]',
                    'building_number_is_missing': '[{$buildingNumberIsMissing|escape:quotes}]',
                    'building_number_not_found': '[{$buildingNumberNotFound|escape:quotes}]',
                    'street_name_needs_correction': '[{$streetNameNeedsCorrection|escape:quotes}]',
                    'locality_needs_correction': '[{$localityNeedsCorrection|escape:quotes}]',
                    'postal_code_needs_correction': '[{$postalCodeNeedsCorrection|escape:quotes}]',
                    'country_code_needs_correction': '[{$countryCodeNeedsCorrection|escape:quotes}]'
                }
            };
            window.EnderecoIntegrator.activeServices = {
                ams: [{if $enderecoclient.sUSEAMS }]true[{else}]false[{/if}],
                emailService: [{if $enderecoclient.bUseEmailservice }]true[{else}]false[{/if}],
                personService: [{if $enderecoclient.bUsePersonalService }]true[{else}]false[{/if}]
            }

            window.EnderecoIntegrator.countryCodeToNameMapping = JSON.parse('[{$enderecoclient.sCountries|escape:quotes}]');
            window.EnderecoIntegrator.countryMapping = JSON.parse('[{$enderecoclient.oCountryMapping|escape:quotes}]');
            window.EnderecoIntegrator.countryMappingReverse = JSON.parse('[{$enderecoclient.oCountryMappingReverse|escape:quotes}]');

            window.EnderecoIntegrator.subdivisionCodeToNameMapping = JSON.parse('[{$enderecoclient.oSubdivisions|escape:quotes}]');
            window.EnderecoIntegrator.subdivisionMapping = JSON.parse('[{$enderecoclient.oSubdivisionMapping|escape:quotes}]');
            window.EnderecoIntegrator.subdivisionMappingReverse = JSON.parse('[{$enderecoclient.oSubdivisionMappingReverse|escape:quotes}]');

            window.EnderecoIntegrator.checkAllCallback = function(EAO) {
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

            window.EnderecoIntegrator.onLoad.forEach(function (callback) {
                callback();
            });

            window.EnderecoIntegrator.ready = true;
        }
    </script>

    <script async defer src="[{$oViewConf->getModuleUrl('endereco-oxid7-client', 'out/assets/js/endereco.min.js')}]?ver=[{$enderecoclient.sModuleVersion}]"></script>
    [{oxid_include_widget cl="enderecocolor"}]
[{/if}]
