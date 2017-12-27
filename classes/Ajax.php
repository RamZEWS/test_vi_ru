<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/models/Courier.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/models/Region.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/models/Trip.php');

class Ajax {
	public function CheckRoute($need_add = false){
		$result = ['status' => false, 'msg' => 'Неизвестная ошибка'];
		$courier_id = isset($_REQUEST['courier_id']) ? intval($_REQUEST['courier_id']) : false;
		$courier = $courier_id ? Courier::getOne(['id=' => $courier_id]) : false;
		if($courier) {
			$region_id = isset($_REQUEST['region_id']) ? intval($_REQUEST['region_id']) : false;
			$region = $region_id ? Region::getOne(['id=' => $region_id]) : false;
			if($region) {
				$date_start = isset($_REQUEST['date_start']) ? date('Y-m-d', strtotime($_REQUEST['date_start'])) : false;
				$date_end = date('Y-m-d', strtotime('+ ' . $region['duration'] . ' days', strtotime($date_start)));
				if($date_start && $date_end) {
					$exist = Trip::getExist($courier['id'], $date_start, $date_end);
    				if(!$exist) {
    					if($need_add) {
    						if(Trip::insert([
	    						'courier_id' => $courier['id'],
	    						'region_id' => $region['id'],
	    						'date_start' => $date_start,
	    						'date_end' => $date_end
	    					])) {
    							$result = ['status' => true, 'msg' => 'Поездка добавлена'];
    						}
    					} else {
    						$result = ['status' => true, 'msg' => 'Поездка может быть добавлена'];
    					}
    				} else {
    					$result['msg'] = $courier['fio'] . ' не может поехать в поездку в ' . $region['name'] . ' c ' . $date_start . ' по ' . $date_end;	
    				}
				} else {
					$result['msg'] = 'Не выбрана дата поездки';
				}
			} else {
				$result['msg'] = 'Не выбран регион';
			}
		} else {
			$result['msg'] = 'Не выбран курьер';
		}
		return $result;
	}

	public function AddRoute(){
		return self::CheckRoute(true);
	}
}