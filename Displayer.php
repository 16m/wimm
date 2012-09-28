<?php

/**
 * @class	Displayer
 * @brief	A class to display what is stored on the Retriever
 * @author	Lotfi Bentouati
 * @version	1.0
 * @copyright	Do What the Fuck You Want To Public License
 *
 * Handle the display of the next trains.
 */
class Displayer
{
  /** Contructor
   *
   * @param object $Retriever
   *  a Retriever object
   */
  public function __construct(&$Retriever)
  {
    $this->_next = $Retriever->getNextPassages();
    $this->_time = $Retriever->getTime();
    $this->_line = $Retriever->getLine();
    $this->blinkTime();
  }

  /** Display the pretty box
   */
  public function display()
  {
    $this->displayTop();
    foreach ($this->_next as $direction => $passages)
      $this->displayNext($direction, $passages);
    $this->displayBottom();
  }

  /* #################### INTERNAL MECHANISMS ############################### */

  private function displayNext(&$direction, &$passages)
  {
    $direction = substr($direction, 0, 25);
    echo '| '.$direction;
    echo str_repeat(' ', 26 - strlen($direction));
    $line = '';
    foreach ($passages as $passage)
      {
	$this->formatPassage($passage);
	$line .= $passage.str_repeat(' ', 8);
      }
    $line = substr($line, 0, -2).'|';
    echo $line;
    echo PHP_EOL;
  }

  private function formatPassage(&$passage)
  {
    $passage = str_replace(' mn', '', $passage);
    if ($passage == "Train a l'approche" ||
	$passage == 'Train a quai')
      $passage = "\033[5m\033[31m00\033[0m";
    else if (strlen($passage) == 1)
      $passage = "\033[33m".'0'.$passage."\033[0m";
    else if (strlen($passage) == 0)
      {
	$passage = 'XX   ';
	return;
      }
    else
      $passage = "\033[33m".$passage."\033[0m";
    $passage .= ' mn';
  }

  private function displayTop()
  {
    if (strlen($this->_line) == 1)
      $this->_line .= ' ';
    echo '+'.str_repeat('-', 77).'+'.PHP_EOL;
    echo '| M '.$this->_line.str_repeat(' ', 66).$this->_time.' |'.PHP_EOL;
    echo '|'.str_repeat(' ', 77).'|'.PHP_EOL;
    echo '|'.str_repeat(' ', 27).'Train 1      Train 2      Train 3      Train 4    |'.PHP_EOL;
  }

  private function displayBottom()
  {
    echo '|'.str_repeat(' ', 77).'|'.PHP_EOL;
    echo '+'.str_repeat('-', 77).'+'.PHP_EOL;
  }

  private function blinkTime()
  {
    $tab = explode(':', $this->_time);
    $time = "\033[33m".$tab[0]."\033[5m".':';
    $time .= "\033[0m\033[33m".$tab[1]."\033[0m";
    $this->_time = $time;
  }

  /* #################### PROPERTIES ######################################## */

  private $_line;
  private $_next;
  private $_time;
}