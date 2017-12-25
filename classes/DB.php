<?
class DB {
	public $config;
	public $mysqli;
	public $is_connected = false;
	public static $instance = null;

	public static function getInstance(){
		if(!self::$instance) {
			self::$instance = new self(); 
		}
		return self::$instance;
	}

    public function __construct(){
        $this->config = require($_SERVER["DOCUMENT_ROOT"].'/config/DB.php');
        if($this->config) {
        	$this->mysqli = new mysqli($this->config['ip'], $this->config['login'], $this->config['password']);
            $this->mysqli->set_charset("utf8");
        	$this->query('CREATE DATABASE IF NOT EXISTS `'.$this->config['dbname'].'` CHARACTER SET utf8 COLLATE utf8_general_ci');
        	$this->mysqli->select_db($this->config['dbname']);
        	if(!mysqli_connect_errno()) {
        		$this->is_connected = true;
        	} else {
			    echo mysqli_connect_errno() . PHP_EOL;
			}
        }
    }

    public function query($sql){
    	$result = $this->mysqli->query($sql);
    	if(!$result) {
            throw new Exception('DB ERROR: ' . $this->mysqli->error);
    	}
    	return $result;
    }
}
?>