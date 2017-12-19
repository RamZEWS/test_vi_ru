<?
class DB {
    public function __construct(){
        $config = require($_SERVER["DOCUMENT_ROOT"].'/config/DB.php');
        print_r($config);
    }
}
?>