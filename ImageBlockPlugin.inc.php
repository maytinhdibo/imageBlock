<?php


import('lib.pkp.classes.plugins.BlockPlugin');

class ImageBlockPlugin extends BlockPlugin {
	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return __('plugins.block.indexing.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.block.indexing.description');
	}

	/**
	 * @copydoc BlockPlugin::getContents()
	 */
	function getContents($templateMgr, $request = null) {
		$context = $request->getContext();
		if (!$context) {
			return '';
		}
		return parent::getContents($templateMgr);
	}
}
