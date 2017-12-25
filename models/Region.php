<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/Model.php');

class Region extends Model {

	public function tableName(){
		return 'vi_region';
	}

	public function map(){
		return [
			'id' => ['type' => 'primary'],
			'name' => ['type' => 'string'],
			'duration' => ['type' => 'int'],
		];
	}
	
}