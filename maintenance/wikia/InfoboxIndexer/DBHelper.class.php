<?php
/**
 * Created by adam
 * Date: 19.12.13
 */

class DBHelper {

	protected $input;
	protected $output;

	public function __construct() {
		$this->input = $this->initWikiConnection();
		$this->output = $this->initSharedConnection();
	}

	public function deleteTemplates( $wikiId ) {
		$this->output->delete(
			'info_templates',
			'wid = ' . $wikiId
		);
		$this->output->commit();
	}

	public function deleteKeys( $wikiId ) {
		$this->output->delete(
			'info_data',
			'wid = ' . $wikiId
		);
		$this->output->commit();
	}

	public function setKeys( $wikiId, $keys ) {
		$rows = [];
		foreach( $keys as $id => $data ) {
			$title = Title::newFromID( $id );
			foreach( $data as $template => $values ) {
				foreach ( $values as $val ) {
					//wid int, id int, title varchar(255), info_key varchar(255), value varchar(255), template varchar(255)
					$row = [
						'wid' => $wikiId,
						'id' => $id,
						'title' => $title->getText(),
						'info_key' => $val['key'],
						'value' => $val['val'],
						'template' => $template,
						'additional' => ''
					];
					if( isset( $val['add'] ) ) {
						$row['additional'] = $val['add'];
					}
					$rows[] = $row;
				}
			}
		}
		if ( !empty( $rows ) ) {
			$this->output->insert( 'info_data', $rows );
			$this->output->commit();
		}
	}

	public function setTemplates( $wikiId, $templates ) {
		$insert = [];
		foreach( $templates as $temp ) {
			$insert[] = [ 'wid' => $wikiId, 'template' => $this->normalizeString( $temp ) ];
		}
		if ( !empty( $insert ) ) {
			$this->output->insert( 'info_templates', $insert );
			$this->output->commit();
		}
	}

	public function getTemplates( $wikidId ) {
		$result = [];
		$res = $this->output->select(
			'info_templates',
			'*',
			'wid = ' . $wikidId
		);
		while( $row = $res->fetchRow() ) {
			$result[] = $row['template'];
		}
		return $result;
	}

	public function getTemplatesList() {
		$result = [];
		$res = $this->input->select(
			'templatelinks',
			'COUNT(*) as count, tl_namespace, tl_title',
			'tl_namespace = ' . NS_TEMPLATE,
			'',
			array( 'GROUP BY' => 'tl_namespace, tl_title', 'ORDER BY' => 'count DESC' )
		);
		while( $row = $res->fetchRow() ) {
			$result[] = $row['tl_title'];
		}
		return $result;
	}

	public function getArticleIdsForTemplates( $titles ) {
		$results = [];
		if ( !empty( $titles ) ) {
			$res = $this->input->select(
				'templatelinks',
				'*',
				[ 'tl_namespace = ' . NS_TEMPLATE, 'tl_title' => $titles ]
			);
			while ( $row = $res->fetchRow() ) {
				$results[] = $row[ 'tl_from' ];
			}
		}
		return array_unique( $results );
	}

	protected function initWikiConnection() {
		return wfGetDB(DB_SLAVE);
	}

	protected function initSharedConnection() {
		return wfGetDB(DB_MASTER, array(), F::app()->wg->ExternalSharedDB);
	}

	protected function normalizeString( $string ) {
		$normalized = trim( $string );
		return $normalized;
	}

}
