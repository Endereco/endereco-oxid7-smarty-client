[{include file="headitem.tpl" title="ENDERECO_ADDRESSCHECK"|oxmultilangassign}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="order_overview">
</form>



<table cellspacing="0" cellpadding="0" border="0" width="500px">
    <tr>
        <td valign="top" class="edittext">
            [{oxmultilang ident="ENDERECO_CHECK_STATUS" suffix="COLON"}]
        </td>
        <!-- Anfang rechte Seite -->
        <td valign="top" class="edittext" align="left" width="50%">
            [{foreach from=$mojoamsstatus item=status}]
                [{$status}]<br>
            [{/foreach}]
        </td>
        <!-- Ende rechte Seite -->
    </tr>
    <tr>
        <td colspan="2" height="5px"></td>
    </tr>
    <tr>
        <td valign="top" class="edittext">
            [{oxmultilang ident="ENDERECO_CHECK_TS" suffix="COLON"}]
        </td>
        <!-- Anfang rechte Seite -->
        <td valign="top" class="edittext" align="left" width="50%">
            [{$mojoamsts}]
        </td>
        <!-- Ende rechte Seite -->
    </tr>
    <tr>
        <td colspan="2" height="5px"></td>
    </tr>
    <tr>
        <td valign="top" class="edittext">
            [{oxmultilang ident="ENDERECO_CHECK_PREDICTIONS" suffix="COLON"}]
        </td>
        <!-- Anfang rechte Seite -->
        <td valign="top" class="edittext" align="left" width="50%">
            [{if $mojoamspredictions}]
                [{foreach from=$mojoamspredictions item=prediction}]
                    [{$prediction}]<br>
                [{/foreach}]
            [{/if}]
        </td>
        <!-- Ende rechte Seite -->
    </tr>
    <tr>
        <td colspan="2" height="5px"></td>
    </tr>
    <tr>
        <td valign="top" class="edittext">
            [{oxmultilang ident="ENDERECO_CHECK_NAMESCORE" suffix="COLON"}]
        </td>
        <!-- Anfang rechte Seite -->
        <td valign="top" class="edittext" align="left" width="50%">
            [{$mojonamescore}]
        </td>
        <!-- Ende rechte Seite -->
    </tr>
</table>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
