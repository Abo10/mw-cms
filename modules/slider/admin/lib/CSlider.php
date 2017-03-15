<?php

class CSlider{
	static protected $tbl_name;
	static protected $tbl_sl_name;
	
	function __construct(){
		self::Initial();
	}
	
	static function Initial(){
		self::$tbl_name = "sliders";
		self::$tbl_sl_name = "std_sliders";
	}
	
	static function AddSliderElements($slider, $args_lang, $args){
		if(empty($args) || empty($args_lang))return false;
		$id = null;
		$slider_name = null;
		if(is_numeric($slider)){
			$id = $slider;
			Cmwdb::$db->where('slider_id', $id);
			$slider_name = Cmwdb::$db->getValue(self::$tbl_sl_name, 'slider_name');
		}
		if(is_string($slider)){
			Cmwdb::$db->where('slider_name', $slider);
			$id = Cmwdb::$db->getValue(self::$tbl_sl_name, 'slider_id');
			$slider_name = $slider;
		}
		Cmwdb::$db->where('s_group', $id);
		Cmwdb::$db->delete(self::$tbl_name);
		$query_data = array();
		$langs = CLanguage::getInstance()->get_lang_keys_user();
// 		var_dump($args);
// 		echo '<hr>';
// 		var_dump($args_lang);die;
		foreach ($args['first_attach_id'] as $order=>$attach){
			//Error, attach can't miss, so delete ather index
			if($attach==""){
				unset($args['first_attach_id'][$order]);
				unset($args['is_active'][$order]);
				foreach ($langs as $d_lang=>$unneed){
					unset($args_lang[$d_lang][$order]);
				}
				continue;
			}
			$query_data['s_group'] = $id;
			$query_data['base_img'] = $attach;
			$query_data['s_order'] = $order;
			$query_data['is_active'] = $args['is_active'][$order];
			foreach ($langs as $lang){
				$query_data['ext_img'] = $args_lang[$lang]['second_attach_id'][$order];
				$query_data['s_url'] = $args_lang[$lang]['url'][$order];
				$query_data['s_lang'] = $lang;
				if(!Cmwdb::$db->insert(self::$tbl_name, $query_data)){
					return json_encode(['status'=>0]);
				}
// 				var_dump($query_data);echo '<hr>';
			}
			
			
		}
		return json_encode(['slider_name'=>$slider_name, 'slider_id'=>$id, 'status'=>1]);
		
	}
	
	static function CreateSlider($slider_name, $args_lang=null, $args=null){
// 		echo 'Try to create slider '.$slider_name.'<br>';
// 		var_dump($args_lang);
// 		var_dump($args);
// 		Cmwdb::$db->where('slider_name', $slider_name);
		$id = null;
		if(is_string($slider_name)){
			Cmwdb::$db->where('slider_name', $slider_name);
			$id=Cmwdb::$db->getValue(self::$tbl_sl_name, 'slider_id');
			if(!empty($id))$id = $id['slider_id'];
		}
		else $id = $slider_name;
// 		$id = Cmwdb::$db->getValue(self::$tbl_sl_name, ['slider_id']);
		if(!$id){
			if(Cmwdb::$db->insert(self::$tbl_sl_name, ['slider_name'=>$slider_name])){
				$id = Cmwdb::$db->getInsertId();
				if(!$args)
					return json_encode(['slider_id'=>$id, 'slider_name'=>$slider_name, 'status'=>1]);
			}
			else return json_encode(['status'=>0]);
				
		}
		return self::AddSliderElements($id, $args_lang, $args);
		
	}
	
	static function GetSlidersNames(){
		$res = Cmwdb::$db->get(self::$tbl_sl_name, null);
		$ret = array();
		if(!empty($res)){
			foreach ($res as $vals)$ret[$vals['slider_id']] = $vals;
		}
		return $ret;
	}
	
	static function GetSlider($slider){
		$id = null;
		$slider_name = null;
		if(is_numeric($slider)){
			$id = $slider;
			Cmwdb::$db->where('slider_id', $id);
			$slider_name = Cmwdb::$db->getValue(self::$tbl_sl_name, 'slider_name');
		}
		else{
			$slider_name = $slider;
			Cmwdb::$db->where('slider_id', $slider);
			$id = Cmwdb::$db->getValue(self::$tbl_sl_name, 'slider_id');
		}
		Cmwdb::$db->where('s_group', $id);
		$res = Cmwdb::$db->get(self::$tbl_name);
// 		echo Cmwdb::$db->getLastQuery();
// 		var_dump($res);
		if(empty($res))return ['slider_name'=>$slider_name, 'slider_id'=>$id,['lang'=>[],'main'=>[]]];
		$lang = array();
		$main = array();
		foreach ($res as $values){
			$lang[$values['s_lang']]['url'][$values['s_order']] = $values['s_url'];
			$lang[$values['s_lang']]['second_attach_id'][$values['s_order']] = $values['ext_img'];
			if($values['ext_img']){
				$at = new CAttach($values['ext_img']);
				$lang[$values['s_lang']]['attach_url'][$values['s_order']] = $at->GetURL();
			}
			else $lang[$values['s_lang']]['attach_url'][$values['s_order']] = "";
			$main['first_attach_id'][$values['s_order']] = $values['base_img'];
			$main['is_active'][$values['s_order']] = $values['is_active'];
			if($values['base_img']){
				$at = new CAttach($values['base_img']);
				$main['attach_url'][$values['s_order']] = $at->GetURL('original');
			}
			else $main['attach_url'][$values['s_order']] = "";
				
		}
		return ['slider_name'=>$slider_name, 'slider_id'=>$id, 'main'=>$main, 'lang'=>$lang];
		
	}
	
	static function GetSliderFront($slider, $base_size="medium", $ext_size="medium"){
		$id = null;
		$slider_name = null;
		if(is_numeric($slider)){
			$id = $slider;
			Cmwdb::$db->where('slider_id', $id);
			$slider_name = Cmwdb::$db->getValue(self::$tbl_sl_name, 'slider_name');
		}
		else{
			$slider_name = $slider;
			Cmwdb::$db->where('slider_name', $slider);
			$id = Cmwdb::$db->getValue(self::$tbl_sl_name, 'slider_id');
		}
		Cmwdb::$db->where('s_group', $id);
		Cmwdb::$db->where('s_lang', CLanguage::getInstance()->getCurrentUser());
		$res = Cmwdb::$db->get(self::$tbl_name);
		// 		echo Cmwdb::$db->getLastQuery();
// 				var_dump($id);
		if(empty($res))return [];
		$base = array();
		$ext = array();
		foreach ($res as $values){
			$ext[$values['s_order']]['url'] = $values['s_url'];
			if($values['ext_img']){
				$at = new CAttach($values['ext_img']);
				$ext[$values['s_order']]['img_url'] = $at->GetURL($ext_size);
			}
			else $ext[$values['s_order']]['img_url'] = "";
			if($values['base_img']){
				$at = new CAttach($values['base_img']);
				$base[$values['s_order']] = $at->GetURL($base_size);;
			}
			else $base[$values['s_order']] = "";
		}
		return ['base'=>$base, 'ext'=>$ext];
	
	}
	
}
?>