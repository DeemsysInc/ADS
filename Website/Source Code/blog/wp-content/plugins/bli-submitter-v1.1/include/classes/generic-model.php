<?php
class BliSubmitterPlugin_Generic_Model {

	public static function get($table,$where = array() , $single = FALSE) {

		global $wpdb;
		$whereCount = count($where);
		$whereString = '';

		foreach ($where as $key => $value) {

			if($wpdb) {
				$whereString .= "`".$key."`='".esc_sql($value)."'";
			}
			if(--$whereCount > 0) $whereString .= ' AND ';
		}

		$sql = "SELECT * FROM `".$table."`";
		
		if(!empty($whereString))
			$sql .= " WHERE ".$whereString;

		if($single) {
			$sql .= " LIMIT 1";
			if($wpdb)
				$result = $wpdb->get_row($sql,ARRAY_A);
		}
		else {
			if($wpdb){
				$result = $wpdb->get_results($sql,ARRAY_A);
			}
		}

		return $result;
	}

	public static function insert($table,$data) {
		global $wpdb;
		if($wpdb){
			$res = $wpdb->insert($table,$data);
			if($res) {
				return $wpdb->insert_id;
			}
		}
		return false;
	}

	public static function update($table,$where = array() , $data = array()) {

		global $wpdb;

		$res = $wpdb->update($table,$data,$where);
		return $res;
	}

	public static function delete($table,$where = array()) {

		global $wpdb;
		$res = $wpdb->delete($table,$where);
		return $res;
	}

	public static function getById($table,$id) {

		global $wpdb;

		if(!$id) return false;
		$sql = "SELECT * FROM `".$table."` WHERE id=$id";
		if($wpdb){
			$result = $wpdb->get_row($sql,ARRAY_A);
		}

		if(empty($result)) return false;

		return $result;
	}
}
?>