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
	{include file="controllers/notification/inPlaceNotification.tpl" notificationId="ibSettingsFormNotification"}

	<div id="description">{translate key="plugins.block.imageBlock.manager.settings.description"}</div>
	{fbvFormArea id="staticPagesFormArea" class="border"}
		{fbvFormSection label="plugins.block.imageBlock.manager.settings.title"}
		    {fbvElement multilingual=true type="text" id="title" value=$title label="plugins.block.imageBlock.manager.settings.title"}
		{/fbvFormSection}

		{fbvFormSection label="plugins.block.imageBlock.manager.settings.content" for="content"}
		    {fbvElement type="textarea" multilingual=true name="content" id="content" value=$content rich=true height=$fbvStyles.height.TALL variables=$allowedVariables}
		{/fbvFormSection}
	{/fbvFormArea}
    {fbvFormButtons}
</form>
