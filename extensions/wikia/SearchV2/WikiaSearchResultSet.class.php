<?php

class WikiaSearchResultSet implements Iterator {
	private $position = 0;
	private $resultsPerPage = 25;
	private $currentPage = false;
	private $isComplete = false;

	protected $resultsFound = 0;
	protected $resultsStart = 0;
	protected $header = null;
	protected $results = array();

	public function __construct(Array $results = array(), $resultsFound = 0, $resultsStart = 0, $isComplete = false) {
		$this->setResults($results);
		$this->setResultsFound($resultsFound);
		$this->setResultsStart($resultsStart);

		if($isComplete || ($this->getResultsNum() == $resultsFound)) {
			$this->markAsComplete();
		}
	}

	/**
	 * set result documents
	 * @param array $results list of WikiaResult or WikiaResultSet (for result grouping) objects
	 */
	public function setResults(Array $results) {
		foreach($results as $result) {
			$this->addResult($result);
		}
	}

	public function setResultsFound($value) {
		$this->resultsFound = $value;
	}

	public function addResult($result) {
		if($this->isValidResult($result)) {
			$this->results[] = $result;
		}
		else {
			throw new WikiaException( 'Invalid result in set' );
		}
	}

	public function getResultsFound() {
		return $this->isComplete() ? $this->getResultsNum() : $this->resultsFound;
	}

	public function incrResultsFound($value = 1) {
		$this->resultsFound += $value;
	}

	public function markAsComplete() {
		$this->isComplete = true;
	}

	public function isComplete() {
		return $this->isComplete;
	}

	public function getResultsStart() {
		return $this->resultsStart;
	}

	public function setResultsStart($value) {
		$this->resultsStart = $value;
	}

	public function getResultsNum() {
		return count($this->results);
	}

	public function getResultsPerPage() {
		return $this->resultsPerPage;
	}

	public function setResultsPerPage($value) {
		$this->resultsPerPage = $value;
		$this->resetPosition();
	}

	public function getCurrentPage() {
		return $this->currentPage;
	}

	public function setCurrentPage($value) {
		$this->currentPage = $value;
		$this->resetPosition();
	}

	public function next() {
		$result = $this->current();
		$this->position++;
		return $result;
	}

	public function rewind() {
		$this->resetPosition();
	}

	public function current() {
		if($this->valid()) {
			return $this->results[$this->position];
		}
		else {
			return false;
		}
	}

	public function key() {
		return $this->position;
	}

	public function valid() {
		if($this->getCurrentPage() === false) {
			$maxPosition = $this->getResultsNum();
		}
		else {
			$maxPosition = ( ( $this->getCurrentPage() - 1) * $this->getResultsPerPage() ) + $this->getResultsPerPage();
		}

		if(($this->position < $maxPosition) && isset($this->results[$this->position])) {
			return true;
		}
		else {
			return false;
		}
	}

	public function setHeader($key, $value) {
		if($this->header === null) {
			$this->header = array();
		}
		$this->header[$key] = $value;
	}

	public function getHeader($key = null) {
		return ( empty($key) ? $this->header : ( isset($this->header[$key]) ? $this->header[$key] : null ) );
	}

	private function isValidResult($result) {
		return (($result instanceof WikiaSearchResult) || ($result instanceof WikiaSearchResultSet)) ? true : false;
	}

	private function resetPosition() {
		$this->position = $this->getStartPosition();
	}

	public function getStartPosition() {
		if($this->getCurrentPage() === false) {
			return 0;
		}
		else {
			return ($this->getCurrentPage() - 1) * $this->getResultsPerPage();
		}
	}

	public function __sleep() {
		return array( 'header', 'results', 'resultsFound', 'resultsStart', 'isComplete' );
	}

	public function __wakeup() {
		$this->position = 0;
		$this->resultsPerPage = 25;
		$this->currentPage = false;
	}

}
