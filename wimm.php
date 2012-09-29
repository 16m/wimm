#!/usr/bin/php
<?php

if (!is_file(dirname(__FILE__).'/curl-class/Curl.php'))
  {
    echo '/!\ Missing Curl dependency. Run :'.PHP_EOL;
    echo '$ git submodule init'.PHP_EOL.'$ git submodule update'.PHP_EOL.PHP_EOL;
    return;
  }
require_once 'curl-class/Curl.php';
require_once 'Displayer.php';
require_once 'Retriever.php';
require_once 'stations.php';

try
{
  $Dom = new DOMDocument;

  // Create the Curl object and modify the user agent
  $Curl = new Curl;
  $Curl->setUserAgent('Wimm/1.0 (+https://github.com/skorpios/wimm)');

  // Create the retriever object and retrieve the next passages
  $Retriever = new Retriever($Curl, $Dom);
  $Retriever->retrieve(PLACE_MONGE, 7, DIRECTION1);

  // Create the displayer and display the next passages
  $Displayer = new Displayer($Retriever);
  $Displayer->display();
}
catch (Exception $e)
{
  echo $e->getMessage().PHP_EOL;
}