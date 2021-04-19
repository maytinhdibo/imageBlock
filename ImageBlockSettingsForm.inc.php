<?php

/**
 * @file plugins//blocks/homeImage/ImageBlockSettingsForm.inc.php
 *
 * @class ImageBlockSettingsForm
 * @ingroup plugins_block_ImageBlock
 *
 * @brief Form for journal managers to modify Google Analytics plugin settings
 */

import('lib.pkp.classes.form.Form');

class ImageBlockSettingsForm extends Form {

	/** @var int */
	var $_journalId;

	/** @var object */
	var $_plugin;

	/**
	 * Constructor
	 * @param $plugin ImageBlockPlugin
	 * @param $journalId int
	 */
	function __construct($plugin, $journalId) {
		$this->_journalId = $journalId;
		$this->_plugin = $plugin;

		parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));

		$this->addCheck(new FormValidator($this, 'content', 'required', 'plugins.block.imageBlock.manager.settings.FieldRequired'));
        $this->addCheck(new FormValidator($this, 'tittle', 'required', 'plugins.block.imageBlock.manager.settings.FieldRequired'));
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$this->_data = array(
			'content' => $this->_plugin->getSetting($this->_journalId, 'content'),
            'tittle' => $this->_plugin->getSetting($this->_journalId, 'tittle'),
		);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('content'));
        $this->readUserVars(array('tittle'));
	}

	/**
	 * @copydoc Form::fetch()
	 */
	function fetch($request, $template = null, $display = false) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		return parent::fetch($request, $template, $display);
	}

	/**
	 * @copydoc Form::execute()
	 */
	function execute(...$functionArgs) {
		$this->_plugin->updateSetting($this->_journalId, 'content', trim($this->getData('content'), "\"\';"), 'string');
        $this->_plugin->updateSetting($this->_journalId, 'tittle', trim($this->getData('tittle'), "\"\';"), 'string');
		parent::execute(...$functionArgs);
	}
}

