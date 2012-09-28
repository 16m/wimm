<?php

/**
 * @class	Retriever 
 * @brief	A class to retrieve data on ratp.fr
 * @author	Lotfi Bentouati
 * @version	1.0
 * @copyright	Do What the Fuck You Want To Public License
 *
 * Giving a station name, a line number and a direction, the retrieve method
 * will fetch and save the data concerning the next trains. The data are pulled
 * from ratp.fr. The getNextPassages method will give a reference to an array
 * containing the saved data
 */
class Retriever
{
  /** Constructor.
   *
   * @param object $Curl
   *  a Curl object
   * @param object $Dom
   *  a Dom object
   */
  public function __construct(&$Curl, &$Dom)
  {
    $this->_Curl = $Curl;
    $this->_Dom = $Dom;
    $this->_next = array();
  }

  /** Retrieve the data concenring the next trains
   *
   * @param string $station
   *  the name of the station
   * @param integer $line
   *  the number of the line
   * @param string $direction
   *  the direction (DIRECTION1 or DIRECTION2 / 'A' or 'R')
   */
  public function retrieve($station, $line, $direction)
  {
    $this->_line = $line;
    $html = $this->_Curl->get(self::BASE.$station.'/'.$line.'/'.$direction);
    @$this->_Dom->loadHTML($html);
    $next_passages = $this->_Dom->getElementById('prochains_passages');
    if (!$next_passages)
      throw new Exception(self::MISMATCH);
    $this->retrieveTime($next_passages);
    $this->retrievePassages($next_passages);
    $this->normaliseNext();
  }

  /** Getter for the data
   *
   * @retval array the next trains
   */
  public function &getNextPassages()
  {
    return $this->_next;
  }

  /** Getter for the time
   *
   * @retval string the real time from ratp.fr
   */
  public function &getTime()
  {
    return $this->_time;
  }

  /** Getter for the line number
   *
   * @retval integer the line number
   */
  public function &getLine()
  {
    return $this->_line;
  }

  /* #################### INTERNAL MECHANISMS ############################### */

  private function normaliseNext()
  {
    foreach ($this->_next as $direction => &$next)
      while (count($next) < 4)
	$next[] = '';
  }

  private function retrievePassages(&$next_passages)
  {
    $trs = $next_passages->getElementsByTagName('tr');
    foreach ($trs as $tr)
      {
	$tds = $tr->getElementsByTagName('td');
	if ($tds->length == 2)
	  $this->_next[utf8_decode($tds->item(0)->textContent)][] =
	    $tds->item(1)->textContent;
      }
  }

  private function retrieveTime(&$next_passages)
  {
    $spans = $next_passages->getElementsByTagName('span');
    foreach ($spans as $span)
      if ($span->hasAttribute('class') &&
	  $span->getAttribute('class') == 'time')
	$this->_time = str_replace('h', ':', $span->textContent);
  }

  /* #################### MESSAGES ########################################## */

  const BASE = 'http://www.ratp.fr/horaires/fr/ratp/metro/prochains_passages/PP/';
  const MISMATCH = 'Unable to find a combination of station and line that matches';

  /* #################### PROPERTIES ######################################## */

  private $_Curl;
  private $_Dom;
  private $_line;
  private $_next;
  private $_time;
}