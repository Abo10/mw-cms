<?php

class CPostToCatPost extends CTypesLinks{
	
	function __construct(){
		$this->tbl_name = "post_to_postCategory_links";
		$this->m_l = "post_cat_id";
		$this->s_l = "post_id";
	}
}
?>