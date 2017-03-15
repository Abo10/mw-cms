<?php 
class CPostToTags extends CTypesLinks{
	
	function __construct(){
		$this->tbl_name = "tags_to_post";
		$this->m_l = "tag_pid";
		$this->s_l = "post_pid";
	}
	
	function GetBySLink($cid){
		Cmwdb::$db->where($this->m_l, $cid);
		$res = Cmwdb::$db->get($this->tbl_name);
		$ret = array();
		foreach ($res as $values)$ret[] = $values['post_pid'];
		return $ret;
	}
	
// 	function AddLinks($s_l_val, $m_l_vals){
// // 		parent::AddLinks($s_l_val, $m_l_vals);
// // 		CErrorHandling::RegisterHandle("In_tag_add");
// 	}
}
?>