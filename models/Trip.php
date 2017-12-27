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

	public static function getExist($courier_id, $date_start, $date_end){
		global $db;
		$sql = 'SELECT * FROM `' . self::tableName() . '` WHERE courier_id = ' . $courier_id . ' AND
				(
					(date_end <= "' . $date_end . '" AND date_start >= "' . $date_start . '") OR
					("' . $date_end . '" BETWEEN date_start AND date_end) OR
					("' . $date_start . '" BETWEEN date_start AND date_end)
				) LIMIT 0, 1';
		$res = $db->query($sql);
		return $res->num_rows > 0;
	}
	
}