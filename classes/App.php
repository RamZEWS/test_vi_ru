<?
class App {
	public static $instance = null;

	public static function getInstance(){
		if(!self::$instance) {
			self::$instance = new self(); 
		}
		return self::$instance;
	}
	
    function includeBlock($name) {
        if($name = trim($name)) {
            require_once($_SERVER["DOCUMENT_ROOT"]."/parts/".$name.".php");
        }
    }
}