<?
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/DB.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/App.php');

$GLOBALS['db'] = new DB();
$GLOBALS['app'] = new App(); 