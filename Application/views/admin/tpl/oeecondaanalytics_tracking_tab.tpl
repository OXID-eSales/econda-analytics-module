[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

<script type="text/javascript">
    if (parent.parent)
    {   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
        parent.parent.sMenuItem    = "[{oxmultilang ident="mxecondaanalytics"}]";
        parent.parent.sMenuSubItem = "[{oxmultilang ident="tbcloeecondaanalyticstracking"}]";
        parent.parent.sWorkArea    = "[{$_act}]";
        parent.parent.setTitle();
    }
</script>

<script type="text/javascript">
    <!--
    window.onload = function ()
    {
        top.reloadEditFrame();
        [{if $updatelist == 1}]
        top.oxid.admin.updateList('[{$oxid}]');
        [{/if}]
    }
    //-->
</script>

<br/>

<div class="messagebox">
    [{if $oView->getTrackingScriptMessageIfEnabled()}]
    [{$oView->getTrackingScriptMessageIfEnabled()}]
    [{/if}]
    [{if $oView->getTrackingScriptMessageIfDisabled()}]
    <p class="warning">
        [{$oView->getTrackingScriptMessageIfDisabled()}]
    </p>
    [{/if}]
</div>

<form action="[{$oViewConf->getSelfLink()}]" method="post" enctype="multipart/form-data">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="upload">
    <input type="file" name="file_to_upload">
    <input type="submit" value="[{oxmultilang ident="OEECONDAANALYTICS_UPLOAD_BUTTON_TITLE"}]">
</form>
<br>
<form action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="save">
    <div>
        <input type=hidden name="confbools[blOeEcondaAnalyticsTracking]" value=false>
        <input type="checkbox" name="confbools[blOeEcondaAnalyticsTracking]" value=true [{if ($blOeEcondaAnalyticsTracking)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOeEcondaAnalyticsEnableTracking"}]
    </div>
    <h4>[{oxmultilang ident="SHOP_MODULE_sOeEcondaAnalyticsTrackingShowNote"}]</h4>
    <div class="messagebox">
        [{oxmultilang ident="OEECONDAANALYTICS_MESSAGE_CMS_SNIPPETS"}]
    </div>
    <div>
        <select size="1" name="confstrs[sOeEcondaAnalyticsTrackingShowNote]">
            <option value="no"[{if $sOeEcondaAnalyticsTrackingShowNote == 'no'}] selected[{/if}]>[{oxmultilang ident="SHOP_MODULE_sOeEcondaAnalyticsTrackingShowNoteNo"}]</option>
            <option value="opt_in"[{if $sOeEcondaAnalyticsTrackingShowNote == 'opt_in'}] selected[{/if}]>[{oxmultilang ident="SHOP_MODULE_sOeEcondaAnalyticsTrackingShowNoteOptIn"}]</option>
            <option value="opt_out"[{if $sOeEcondaAnalyticsTrackingShowNote == 'opt_out'}] selected[{/if}]>[{oxmultilang ident="SHOP_MODULE_sOeEcondaAnalyticsTrackingShowNoteOptOut"}]</option>
        </select>
    </div>
    <br/>
    <div>
        <input type="submit" value="[{oxmultilang ident="GENERAL_SAVE"}]">
    </div>
</form>

[{include file="bottomitem.tpl"}]

