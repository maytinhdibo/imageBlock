{**
 * plugins/blocks/homeImage/settingsForm.tpl
 *
 *
 *}
<script>
	$(function() {ldelim}
		// Attach the form handler.
		$('#imageBlockSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

<form class="pkp_form" id="imageBlockSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="blocks" plugin=$pluginName verb="settings" save=true}">
	{csrf}
	{include file="controllers/notification/inPlaceNotification.tpl" notificationId="gaSettingsFormNotification"}

	<div id="description">{translate key="plugins.block.imageBlock.manager.settings.description"}</div>

{*	{fbvFormArea id="webFeedSettingsFormArea"}*}
{*		{fbvElement type="text" id="tittle" value=$tittle label="plugins.block.imageBlock.manager.settings.tittle"}*}
{*	{/fbvFormArea}*}

	{fbvFormArea id="webFeedSettingsFormArea"}
		{fbvElement type="textarea" id="content" value=$content label="plugins.block.imageBlock.manager.settings.content" rich=true height=$fbvStyles.height.TALL variables=$allowedVariables}
	{/fbvFormArea}

	{fbvFormButtons}

	<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</form>
