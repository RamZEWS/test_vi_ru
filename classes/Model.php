<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/init.php');

class Model {

	public function tableName(){
		return null;
	}

	public function map(){
		return [];
	}

	public static function insert($row = []){
		$map = static::map();
		$table = static::tableName();
		if($table) {
			if($map) {
				unset($map['id']);

				global $db;

				$values = [];
				foreach($map as $k => $info) {
					$values[] = isset($row[$k]) ? (in_array($info['type'], ['string', 'date', 'datetime']) ? '"' . $row[$k] . '"' : $row[$k]) : 'NULL';
				}

				$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', array_keys($map)) . ') VALUES (' . implode(', ', $values) . ')';
				$res = $db->query($sql);
				if($res) {
					return $db->mysqli->insert_id;
				} else {
					throw new Exception('INSERT ERROR: ' . $db->mysqli->error);
				}
			} else {
				throw new Exception('MODEL ERROR: Table map is not found');
			}
		} else {
			throw new Exception('MODEL ERROR: Table name is not found');
		}
	}

	public static function update($id, $data = []){
		$table = static::tableName();
		if($table) {

			global $db;

			$values = [];
			foreach($data as $k => $v) {
				$values[] = $k . ' = ' . ($v ? '"'.$v.'"': 'NULL');
			}

			$sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $values) . ' WHERE id = ' . $id;
			$res = $db->query($sql);
			if($res) {
		        $res->close();
				return true;
			} else {
				throw new Exception('UPDATE ERROR: ' . $db->mysqli->error);
			}
		} else {
			throw new Exception('MODEL ERROR: Table name is not found');
		}
	}

	public static function delete($id) {
		$table = static::tableName();
		if($table) {

			global $db;

			$sql = 'DELETE FROM ' . $table . ' WHERE id = ' . $id;
			$res = $db->query($sql);
			if($res) {
		        $res->close();
				return true;
			} else {
				throw new Exception('DELETE ERROR: ' . $db->mysqli->error);
			}
		} else {
			throw new Exception('MODEL ERROR: Table name is not found');
		}
	}

	public static function getAll($filter = [], $sort = '', $limit = null, $offset = 0) {
		$table = static::tableName();
		if($table) {

			global $db;

			$arrF = []; 
			$filter_str = '';
			if(isset($filter['OR']) || isset($filter['AND'])) {
				if(isset($filter['OR']) && is_array($filter['OR'])) {
					$arrF[] = '('.implode(' OR ', self::getWhere($filter['OR'])).')';
				}
				if(isset($filter['AND']) && is_array($filter['AND'])) {
					$arrF[] = '('.implode(' AND ', self::getWhere($filter['AND'])).')';
				}
			} else {
				$arrF = self::getWhere($filter);
			}
			$filter_str = $arrF ? implode(' AND ', $arrF) : '1=1';

			$sql = 'SELECT * FROM ' . $table . ' WHERE ' . $filter_str. ' ORDER BY ' . ($sort ?: 'id asc');
			if(!is_null($limit)) {
				$sql = $sql . ' LIMIT ' . implode(', ', [$limit, $offset]);
			}
			$res = $db->query($sql);
			if($res) {
				$result = [];
				while($row = $res->fetch_assoc()) {
		            $result[$row['id']] = $row;
		        }
		        $res->close();
				return $result;
			} else {
				throw new Exception('SELECT ERROR: ' . $db->mysqli->error);
			}
		} else {
			throw new Exception('MODEL ERROR: Table name is not found');
		}
	}

	public static function getOne($filter = [], $sort = '') {
		$result = static::getAll($filter, $sort, 0, 1);
		return current($result);
	}

	public static function getWhere($filter){
		$arrF = [];
		foreach ($filter as $key => $value) {
			if(is_array($value)) {
				$tmp = '';
				foreach ($value as $v) $tmp[] = '"'.$v.'"';
				$value = 'IN ('.implode(',', $tmp).')';
			}
			if(!is_int($key)) {
				$arrF[] = $key.'"'.$value.'"';
			} else {
				$arrF[] = '"'.$value.'"';
			}
		}
		return $arrF;
	}
}