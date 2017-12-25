<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/Model.php');

class Courier extends Model {

	public function tableName(){
		return 'vi_courier';
	}

	public function map(){
		return [
			'id' => ['type' => 'primary'],
			'fio' => ['type' => 'string']
		];
	}
}