<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/Model.php');

class Trip extends Model {

	public function tableName(){
		return 'vi_trip';
	}

	public function map(){
		return [
			'id' => ['type' => 'primary'],
			'region_id' => ['type' => 'int'],
			'courier_id' => ['type' => 'int'],
			'date_start' => ['type' => 'date'],
			'date_end' => ['type' => 'date'],
		];
	}
	
}