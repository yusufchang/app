<?php

/**
 * Class TimeMachineController
 */
class TimeMachineController extends WikiaController {

	/**
	 *
	 */
	public function index() {
		$view = $this->getVal( 'view', 'activation' );

		if ( strtolower( $view ) == 'status' ) {
			$tm = new TimeMachine();
			$this->timestamp = $tm->getTimestamp();
			$this->season = $tm->getSeason();
			$this->episode = $tm->getEpisode();

			$content = $this->sendSelfRequest( 'statusBar', [] )->toString();
		} else {
			$content = $this->sendSelfRequest( 'activationBar', [] )->toString();
		}

		$this->showData = $this->getShowData();
		$this->content = $content;
		$this->result = "ok";
		$this->msg = '';
	}

	/**
	 * The bar users see when they are using the Time Machine to browse the site
	 */
	public function statusBar() {

	}

	/**
	 * The bar users see when coming from a google search, prompting them to use Time Machine
	 */
	public function activationBar() {

	}

	public function getShowData() {
		$tm = new TimeMachine();

		$url = 'http://services.tvrage.com/feeds/search.php?show=' . $tm->getSubdomain();

		$resp = Http::get( $url );
		if ( $resp === false ) {
			wfDebug( __METHOD__ . ": failed!\n" );
			return null;
		}

		$p = xml_parser_create();
		xml_parse_into_struct( $p, $resp, $vals, $index );
		xml_parser_free( $p );

		$showName = $this->tagValue( $vals, 'name' );
		$showId = $this->tagValue( $vals, 'showid' );
		$seasons = $this->tagValue( $vals, 'seasons' );

		$showData = [
			'name'    => $showName,
			'id'      => $showId,
			'seasons' => $seasons,
		];

		$url = 'http://services.tvrage.com/feeds/episode_list.php?sid=' . $showId;
		$resp = Http::get( $url );
		if ( $resp === false ) {
			wfDebug( __METHOD__ . ": failed!\n" );
			return null;
		}

		$p = xml_parser_create();
		xml_parse_into_struct( $p, $resp, $vals, $index );
		xml_parser_free( $p );

		$episodes = [];
		$curSeasonNumber = 0;
		$curEpisodeNumber = 0;
		$curAirDate = '';
		foreach ( $vals as $node ) {
			if ( strtolower( $node['tag'] ) == 'season' && $node['type'] == 'open' ) {
				$curSeasonNumber = $node['attributes']['NO'];
				$curEpisodeNumber = 1;
			}

			if ( strtolower( $node['tag'] ) == 'airdate' && $node['type'] == 'complete' ) {
				$curAirDate = $node['value'];
			}

			if ( strtolower( $node['tag'] ) == 'title' && $node['type'] == 'complete' ) {
				$episodes[$curSeasonNumber][] = [
					$curEpisodeNumber++,
					$node['value'],
					strtotime($curAirDate)
				];
			}
		}

		$showData['episodes'] = $episodes;

		return json_encode( $showData );
	}

	public function tagValue( $vals, $tag ) {
		foreach ( $vals as $node ) {
			if ( strtolower( $node['tag'] ) == strtolower( $tag ) ) {
				if ( $node['type'] != 'complete' ) {
					continue;
				}

				return $node['value'];
			}
		}
	}
}
