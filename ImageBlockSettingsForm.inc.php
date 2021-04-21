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

    /** @var object */
    var $_imageBlock;

    /** @var object */
    var $_imageBlocksDao;

	/**
	 * Constructor
	 * @param $plugin ImageBlockPlugin
	 * @param $journalId int
	 */
	function __construct($plugin, $journalId,$imageBlocksDao) {
		$this->_journalId = $journalId;
		$this->_plugin = $plugin;

        $this->_imageBlock = $imageBlocksDao->getLastestByContextId($this->_journalId)==null?$imageBlocksDao->newDataObject():$imageBlocksDao->getLastestByContextId($this->_journalId);

        $this->_imageBlocksDao = $imageBlocksDao;

		parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));

		$this->addCheck(new FormValidator($this, 'content', 'required', 'plugins.block.imageBlock.manager.settings.FieldRequired'));
        $this->addCheck(new FormValidator($this, 'title', 'required', 'plugins.block.imageBlock.manager.settings.FieldRequired'));
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$this->_data = array(
			'title' => $this->_imageBlock->getTitle(null),
            'content' => $this->_imageBlock->getContent(null),
		);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('content'));
        $this->readUserVars(array('title'));
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
        $this->_imageBlock->setTitle((array) $this->getData('title'), null);
        $this->_imageBlock->setContent((array) $this->getData('content'), null);
        $this->_imageBlock->setContextId($this->_journalId);
        if ($this->_imageBlocksDao->getLastestByContextId($this->_journalId)!=null) {
            $this->_imageBlocksDao->updateObject($this->_imageBlock);
        } else {
            $this->_imageBlocksDao->insertObject($this->_imageBlock);
        }
        parent::execute(...$functionArgs);
	}
}

