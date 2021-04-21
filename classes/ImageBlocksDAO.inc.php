<?php

/**
 * @file classes/ImageBlocksDAO.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @package plugins.blocks.imageBlock.classes.ImageBlocksDAO
 * @class ImageBlocksDAO
 * Operations for retrieving and modifying ImageBlocks objects.
 */

import('lib.pkp.classes.db.DAO');
import('plugins.blocks.imageBlock.classes.ImageBlock');

class ImageBlocksDAO extends DAO {

	/**
	 * Get a image block by ID
	 * @param $imageBlockId int image block ID
	 * @param $contextId int Optional context ID
	 */
	function getById($imageBlockId, $contextId = null) {
		$params = array((int) $imageBlockId);
		if ($contextId) $params[] = $contextId;

		$result = $this->retrieve(
			'SELECT * FROM image_blocks WHERE image_block_id = ?'
			. ($contextId?' AND context_id = ?':''),
			$params
		);

		$returner = null;
		if ($result->RecordCount() != 0) {
			$returner = $this->_fromRow($result->GetRowAssoc(false));
		}
		$result->Close();
		return $returner;
	}

	/**
	 * Get a set of image blocks by context ID
	 * @param $contextId int
	 * @param $rangeInfo Object optional
	 * @return DAOResultFactory
	 */
	function getByContextId($contextId, $rangeInfo = null) {
		$result = $this->retrieveRange(
			'SELECT * FROM image_blocks WHERE context_id = ?',
			(int) $contextId,
			$rangeInfo
		);

		return new DAOResultFactory($result, $this, '_fromRow');
	}

    /**
     * Get a set of image blocks by context ID
     * @param $contextId int
     * @param $rangeInfo Object optional
     * @return ImageBlock
     */
    function getLastestByContextId($contextId, $rangeInfo = null) {
        $result = $this->retrieveRange(
            'SELECT * FROM image_blocks WHERE context_id = ? ORDER BY image_block_id DESC LIMIT 0, 1',
            (int) $contextId,
            $rangeInfo
        );

        $returner = null;
        if ($result->RecordCount() != 0) {
            $returner = $this->_fromRow($result->GetRowAssoc(false));
        }
        $result->Close();
        return $returner;
    }

	/**
	 * Insert a image block.
	 * @param $imageBlock ImageBlock
	 * @return int Inserted image block ID
	 */
	function insertObject($imageBlock) {
		$this->update(
			'INSERT INTO image_blocks (context_id) VALUES (?)',
			array(
				(int) $imageBlock->getContextId()
			)
		);

		$imageBlock->setId($this->getInsertId());
		$this->updateLocaleFields($imageBlock);

		return $imageBlock->getId();
	}

	/**
	 * Update the database with a image block object
	 * @param $imageBlock ImageBlock
	 */
	function updateObject($imageBlock) {
		$this->update(
			'UPDATE	image_blocks
			SET	context_id = ?
			WHERE	image_block_id = ?',
			array(
				(int) $imageBlock->getContextId(),
				(int) $imageBlock->getId()
			)
		);
		$this->updateLocaleFields($imageBlock);
	}

	/**
	 * Delete a image block by ID.
	 * @param $imageBlockId int
	 */
	function deleteById($imageBlockId) {
		$this->update(
			'DELETE FROM image_blocks WHERE image_block_id = ?',
			(int) $imageBlockId
		);
		$this->update(
			'DELETE FROM image_block_settings WHERE image_block_id = ?',
			(int) $imageBlockId
		);
	}

	/**
	 * Delete a image block object.
	 * @param $imageBlock ImageBlock
	 */
	function deleteObject($imageBlock) {
		$this->deleteById($imageBlock->getId());
	}

	/**
	 * Generate a new image block object.
	 * @return ImageBlock
	 */
	function newDataObject() {
		return new ImageBlock();
	}

	/**
	 * Return a new image blocks object from a given row.
	 * @return ImageBlock
	 */
	function _fromRow($row) {
		$imageBlock = $this->newDataObject();
		$imageBlock->setId($row['image_block_id']);
		$imageBlock->setContextId($row['context_id']);

		$this->getDataObjectSettings('image_block_settings', 'image_block_id', $row['image_block_id'], $imageBlock);
		return $imageBlock;
	}

	/**
	 * Get the insert ID for the last inserted image block.
	 * @return int
	 */
	function getInsertId() {
		return $this->_getInsertId('image_blocks', 'image_block_id');
	}

	/**
	 * Get field names for which data is localized.
	 * @return array
	 */
	function getLocaleFieldNames() {
		return array('title', 'content');
	}

	/**
	 * Update the localized data for this object
	 * @param $author object
	 */
	function updateLocaleFields(&$imageBlock) {
		$this->updateDataObjectSettings('image_block_settings', $imageBlock, array(
			'image_block_id' => $imageBlock->getId()
		));
	}
}

