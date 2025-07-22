[{$smarty.block.parent}]

[{if $mojoamsstatus}]
<div style="margin-top: 10px;margin-left: 10px;">
Endereco - letzte Pürfung:
<table cellspacing="0" cellpadding="1px" border="0px" width="500px">
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
</div>
[{/if}]