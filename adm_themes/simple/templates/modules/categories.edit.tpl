<form {foreach $attributes as $attribute}
        {$attribute@key}="{$attribute}"
    {/foreach}>
    <div class="admidio-form-required-notice"><span>{$l10n->get('SYS_REQUIRED_INPUT')}</span></div>

    {include 'sys-template-parts/form.input.tpl' data=$elements['admidio-csrf-token']}
    {include 'sys-template-parts/form.input.tpl' data=$elements['mode']}
    {include 'sys-template-parts/form.input.tpl' data=$elements['uuid']}
    {include 'sys-template-parts/form.input.tpl' data=$elements['type']}
    {include 'sys-template-parts/form.input.tpl' data=$elements['cat_name']}
    {if $categoryType != 'ROL' && ($categorySystem == 0 || $countOrganizations == 1)}
        {include 'sys-template-parts/form.select.tpl' data=$elements['adm_categories_view_right']}
        {if $categoryType != 'USF'}
            {include 'sys-template-parts/form.select.tpl' data=$elements['adm_categories_edit_right']}
        {/if}
    {/if}
    {if $categoryType != 'ROL' && $categorySystem == 0 && $countOrganizations > 1}
        {include 'sys-template-parts/form.input.tpl' data=$elements['adm_administrators']}
        {include 'sys-template-parts/form.checkbox.tpl' data=$elements['show_in_several_organizations']}
    {/if}
    {include 'sys-template-parts/form.checkbox.tpl' data=$elements['cat_default']}
    {include 'sys-template-parts/form.button.tpl' data=$elements['btn_save']}
    <div class="form-alert" style="display: none;">&nbsp;</div>
    {include file="sys-template-parts/system.info-create-edit.tpl"}
</form>
