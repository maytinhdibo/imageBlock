<?php


import('lib.pkp.classes.plugins.BlockPlugin');

class ImageBlockPlugin extends BlockPlugin {
    /**
     * @copydoc Plugin::register()
     */
    function register($category, $path, $mainContextId = null) {
        if (parent::register($category, $path, $mainContextId)) {
            if ($this->getEnabled($mainContextId)) {
                // Register the static pages DAO.
                import('plugins.blocks.imageBlock.classes.ImageBlocksDAO');
                $imageBlocksDAO = new ImageBlocksDAO();
                DAORegistry::registerDAO('ImageBlocksDAO', $imageBlocksDAO);
            }
            return true;
        }
        return false;
    }

	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return __('plugins.block.imageBlock.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.block.imageBlock.description');
	}

	/**
	 * @copydoc BlockPlugin::getContents()
	 */
	function getContents($templateMgr, $request = null) {
		$context = $request->getContext();
		if (!$context) {
			return '';
		}
        $imageBlocksDao = DAORegistry::getDAO('ImageBlocksDAO');
        $content = $imageBlocksDao->getLastestByContextId($context->getId())==null?"No content":$imageBlocksDao->getLastestByContextId($context->getId())->getLocalizedContent();
        $title = $imageBlocksDao->getLastestByContextId($context->getId())==null?"Image Block":$imageBlocksDao->getLastestByContextId($context->getId())->getLocalizedTitle();
        $templateMgr->assign(array(
            'content' => $content,
            'title' => $title,
        ));
		return parent::getContents($templateMgr);
	}

    /**
     * @copydoc Plugin::getActions()
     */
    function getActions($request, $verb) {
        $router = $request->getRouter();
        import('lib.pkp.classes.linkAction.request.AjaxModal');
        return array_merge(
            $this->getEnabled()?array(
                new LinkAction(
                    'settings',
                    new AjaxModal(
                        $router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'blocks')),
                        $this->getDisplayName()
                    ),
                    __('manager.plugins.settings'),
                    null
                ),
            ):array(),
            parent::getActions($request, $verb)
        );
    }

    /**
     * @copydoc Plugin::manage()
     */
    function manage($args, $request) {
        switch ($request->getUserVar('verb')) {
            case 'settings':
                $context = $request->getContext();

                AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON,  LOCALE_COMPONENT_PKP_MANAGER);
                $templateMgr = TemplateManager::getManager($request);
                $templateMgr->registerPlugin('function', 'plugin_url', array($this, 'smartyPluginUrl'));

                $this->import('ImageBlockSettingsForm');
                $imageBlocksDao = DAORegistry::getDAO('ImageBlocksDAO');
                //$imageBlock = $imageBlocksDao->getById(1)==null?$imageBlocksDao->newDataObject():$imageBlocksDao->getById(1);

                $form = new ImageBlockSettingsForm($this, $context->getId(),$imageBlocksDao);

                if ($request->getUserVar('save')) {
                    $form->readInputData();
                    if ($form->validate()) {
                        $form->execute();
                        return new JSONMessage(true);
                    }
                } else {
                    $form->initData();
                }
                return new JSONMessage(true, $form->fetch($request));
        }
        return parent::manage($args, $request);
    }

    /**
     * Get the filename of the ADODB schema for this plugin.
     * @return string Full path and filename to schema descriptor.
     */
    function getInstallSchemaFile() {
        return $this->getPluginPath() . '/schema.xml';
    }
}
