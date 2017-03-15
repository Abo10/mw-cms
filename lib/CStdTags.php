<?php

class CStdTags{
	protected $pid = null;
	protected $data = array();
	protected $tbl_name = "std_tags";
	
	
	function __construct($pid=null){
		if($pid){
			Cmwdb::$db->where('pid', $pid);
			Cmwdb::$db->groupBy('lang');
			$res = Cmwdb::$db->get($this->tbl_name);
			if(!empty($res)){
				foreach ($res as $value){
					$this->pid = $value['pid'];
					$this->data[$value['lang']] = $value;
				}
			}
		}
	}
	
	function GetAsArray(){
		return $this->data;
	}
	
	function GetArray_JSON(){
		return json_encode($this->GetAsArray());
	}
	
	function LoadByPID($pid){
		Cmwdb::$db->where('pid', $pid);
		Cmwdb::$db->groupBy('lang');
		$res = Cmwdb::$db->get($this->tbl_name);
		if(!empty($res)){
			foreach ($res as $value){
				$this->pid = $value['pid'];
				$this->data[$value['lang']] = $value;
			}
			return true;
		}
		return false;
	}
	
	function GetAsArrayPID($pid){
		if($this->LoadByPID($pid))return $this->GetAsArray();
		return false;
	}
	
	function CreateTag($argv){
		Cmwdb::$db->startTransaction();
		$pid = Cmwdb::$db->getOne($this->tbl_name, 'max(pid) pid');
		if ($pid['pid']) $pid['pid']++;
		else $pid['pid'] = 1;
		
		$pid = $pid['pid'];
		$slugs = array();
		while ($cur_slug = current($argv)) {
			$slugs[key($argv)] = CSlug::GetSlug($cur_slug['tag_name']);
			next($argv);
		}
		
		reset($argv);
		$slugs = CSlug::GetVerifiedSlugs($slugs, $this->tbl_name, "tag_slug");
		//         var_dump($slugs);
		while ($current = current($argv)) {
			$queryData = array();
			//verify important datas
			if (!isset($current['tag_name'])) {
				$this->db->rollback();
				return false;
			}
			$queryData['tag_name'] = CSecurity::FilterString($current['tag_name']);
			$queryData['tag_slug'] = $slugs[key($argv)];
			$queryData['pid'] = $pid;
			$queryData['lang'] = key($argv);
			$queryData['tag_descr'] = CSecurity::FilterString($current['tag_descr']);
			if (!Cmwdb::$db->insert($this->tbl_name, $queryData)) {
				Cmwdb::$db->rollback();
				return false;
			}
			next($argv);
		}
		Cmwdb::$db->commit();
		return true;
	}
	
	function UpdateDetails($argv, $edit_id){
		Cmwdb::$db->startTransaction();
		foreach ($argv as $key => $value){
			Cmwdb::$db->where('pid', $edit_id);
			Cmwdb::$db->where('lang', $key);
			foreach ($value as $key=>$val2){
				$value['tag_name'] = CSecurity::FilterString($value['tag_name']);
				$value['tag_descr'] = CSecurity::FilterString($value['tag_descr']);
			}
			if(!Cmwdb::$db->update($this->tbl_name, $value)){
				Cmwdb::$db->rollback();
				return false;
			}
		}
		Cmwdb::$db->commit();
		return true;
	}
	
	function UpdateSlug($id, $lang, $new_slug, $type=null){
		try {
			Cmwdb::$db->where('pid', $id);
			Cmwdb::$db->where('lang', $lang);
			if(!$type)$type = 'tag';
			$old_slug = Cmwdb::$db->getValue($this->tbl_name, 'tag_slug');
				
			$new_slug = CSlug::ConvertToEnglish($new_slug);
			if(is_numeric($new_slug))$new_slug = 'stdslug-'.$new_slug;
			Cmwdb::$db->where('tag_slug', $new_slug);
			if(Cmwdb::$db->getValue($this->tbl_name, 'tag_slug'))throw new Exception('The new slug exists in db.',1);
			if($old_slug){
	
				Cmwdb::$db->startTransaction();
				if(!CStdRedirects::AddRedirect($type, $id, $old_slug, $new_slug)){
					Cmwdb::$db->rollback();
					throw new Exception("Cant create redirect",2);
				}
				Cmwdb::$db->where('pid', $id);
				Cmwdb::$db->where('lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['tag_slug'=>$new_slug])){
					Cmwdb::$db->rollback();
					throw new Exception("Error, cant insert new slug into post table",3);
				}
				Cmwdb::$db->commit();
				return ['status'=>1,'message'=>$new_slug];
			}
			else {
				Cmwdb::$db->where('pid', $id);
				Cmwdb::$db->where('lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['tag_slug'=>$new_slug]))throw new Exception('Error: Cant insert new slug into post table');
				return ['status'=>1,'message'=>$new_slug];
			}
		}
		catch (Exception $error){
			return ['status'=>0,'message'=>$error->getMessage()];
		}
	}
	
	
}
?>