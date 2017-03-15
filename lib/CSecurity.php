<?php

class CSecurity{
	
	static function FilterString($str){
		//Filtering string and remove <script>...</script>
		$start_pos = -1;
		$end_pos = -1;
		while(($start_pos=stripos($str, "<script"))!==false){
			$end_pos = stripos($str, "</script");
			if($end_pos===false){
				$end_pos = strlen($str);
			}
			$str = substr_replace($str, "", $start_pos, ($end_pos+9-$start_pos));
		}
		return $str;
	}
}
?>