<?php
class BliSubmitterPlugin_Utils{
	public static function plugin_url($path){
		return BLI_SUBMITTER_PLUGIN_URL.$path;

	}

	public static function array_to_dropdown($name, $array){
		$output = '';
		$output .= '<select name="'.$name.'">';
		foreach($array as $k => $o){
			$output .= '<option value="'.$k.'">'.$o.'</option>';
		}
		$output .= '</select>';
		return $output;
	}

	public static function mysql_date($date =''){
		if($date == ''){
			$ts = time();
		}else{
			$ts = strtotime($date);
		}
		return date('Y-m-d', $ts);
	}

	public static function mysql_before_date_by_days($day=''){
		if($day == ''){
			$ts = time();
		}else{
			$ts = time() - ($day * 24 * 60 * 60);
		}
		return date('Y-m-d', $ts);
	}

	public static function mysql_datetime($datetime = ''){
		if($datetime == ''){
			$ts = time();
		}else{
			$ts = strtotime($datetime);
		}

		return date('Y-m-d H:i:s', $ts);
	}

	public static function mysql_datetime_before($num_month = ''){
		if($num_month == '' || $num_month == 0){
			$ts = time();
		}
		else if($num_month == 1){
			$ts = strtotime('-'.$num_month.' month');
		}

		else {
			$ts = strtotime('-'.$num_month.' months');
		}

		return date('Y-m-d H:i:s', $ts);
	}
}