<?php

class CCart{
	static $tbl_name = 'module_cart';

	static function AddToCart($subject_id, $subject_type){
// 		var_dump($subject_id);
		CErrorHandling::RegisterHandle("test_cart");
		self::InitCart();
		
		if(is_array($subject_id)){
			if(isset($_SESSION['cart']))
				$cur_order = count($_SESSION['cart'])+1;
			else{
				$_SESSION['cart'] = array();
				$cur_order = 1;
			}
			foreach ($subject_id as $index=>$content){
				foreach ($content as $id=>$count){
					$is_finded = false;
					foreach ($_SESSION['cart'] as $order=>$values){
						if(in_array($id, $values) && in_array($subject_type, $values)){
							$_SESSION['cart'][$order]['count']+=$count;
							$is_finded = true;
						}						
					}
					if(!$is_finded){
						$_SESSION['cart'][$cur_order]['count']=$count;
						$_SESSION['cart'][$cur_order]['type']=$subject_type;
						$_SESSION['cart'][$cur_order]['id']=$id;;
						$cur_order++;
						$is_finded = false;
					}
				}
			}
			if (CUser::GetUserID()) {
				Cmwdb::$db->where('uid', CUser::GetUserID());
				Cmwdb::$db->update(self::$tbl_name, ['data' => json_encode($_SESSION['cart'])]);
			}
			
		}


		return 1;
	}
	
	static function SingleAddToCart($subject_id, $subject_type){
		self::InitCart();
	

		if(isset($_SESSION['cart']))
			$cur_order = count($_SESSION['cart'])+1;
		else{
			$_SESSION['cart'] = array();
			$cur_order = 1;
		}
		$is_finded = false;
		try {
			foreach ($_SESSION['cart'] as $order=>$values){
				if(in_array($subject_id, $values) && in_array($subject_type, $values)){
					$_SESSION['cart'][$order]['count']+=1;
					$is_finded = true;
					break;
				}
			}
			if(!$is_finded)throw new Exception("Product in cart miss");
		}catch (Exception $error){
			echo $error->getMessage();
			return false;
		}
	
		if (CUser::GetUserID()) {
			Cmwdb::$db->where('uid', CUser::GetUserID());
			Cmwdb::$db->update(self::$tbl_name, ['data' => json_encode($_SESSION['cart'])]);
		}
		return 1;
		
	}
	
	static function RemoveOneFromCart($subject_id, $subject_type){
		// 		var_dump($subject_id);
// 		CErrorHandling::RegisterHandle("test_cart");
		self::InitCart();
	

		if(isset($_SESSION['cart']))
			$cur_order = count($_SESSION['cart'])+1;
		else{
			$_SESSION['cart'] = array();
			$cur_order = 1;
		}
		$is_finded = false;
		try {
			foreach ($_SESSION['cart'] as $order=>$values){
				if(in_array($subject_id, $values) && in_array($subject_type, $values)){
					$_SESSION['cart'][$order]['count']-=1;
					$is_finded = true;
					break;
				}
			}
			if(!$is_finded)throw new Exception("Product in cart miss");
		}catch (Exception $error){
			echo $error->getMessage();
			return false;
		}
	
		if (CUser::GetUserID()) {
			Cmwdb::$db->where('uid', CUser::GetUserID());
			Cmwdb::$db->update(self::$tbl_name, ['data' => json_encode($_SESSION['cart'])]);
		}
		return 1;
	}
	
	static function IncCount($subject_id, $subject_type){
		self::SingleAddToCart($subject_id, $subject_type);
		$ret = self::GetCart();
		try {
			foreach ($ret as $values){
				if($values['id']==$subject_id && $values['type']==$subject_type)
					return json_encode($values);
			}
			throw new Exception("Error: Added to cart, but then missing in list");
		}catch (Exception $error){
			echo $error->getMessage();
			//TODO: Do any action, if error is exists
			return false;
		}

	}
	
	static function DecCount($subject_id, $subject_type){
		if(self::RemoveOneFromCart($subject_id, $subject_type)){
			$ret = self::GetCart();
			try {
				foreach ($ret as $values){
					if($values['id']==$subject_id && $values['type']==$subject_type)
						return json_encode($values);
				}
				throw new Exception("Error: Added to cart, but then missing in list");
			}catch (Exception $error){
				echo $error->getMessage();
				//TODO: Do any action, if error is exists
				return false;
			}
		}
		return json_encode([]);
	}
	
	static function GetCart(){
		$multyprice = array();
		$products = array();
		self::InitCart();
// 		var_dump($_SESSION);die;
		if(isset($_SESSION['cart'])){
			foreach ($_SESSION['cart'] as $vals){
				if($vals['type']=="multiprice")$multyprice[] = $vals['id'];
				if($vals['type']=="product")$products[] = $vals['id'];
			}

			$multylist = CFrontProduct::GetByMultyprice($multyprice);
			$needProducts = array();
			//complate products list from multyprice
			foreach ($multylist as $prod_id){
				if(in_array($prod_id, $needProducts))
					continue;
				$needProducts[] = $prod_id;
			}
			//add single products
			foreach ($products as $prod_id){
				if(in_array($prod_id, $needProducts))
					continue;
				$needProducts[] = $prod_id;
				
			}
			//Take all needed products
			$prods = CFrontProduct::GetDatas($needProducts,['attributika'=>1]);
			$ret = array();
			$tmp = array();
// 			var_dump($prods);die;
			foreach ($_SESSION['cart'] as $order=>$values){
				if($values['type']=='multiprice'){
					if(isset($multylist[$values['id']])){
						$prod_id = $multylist[$values['id']];
						$tmp[$order]['product_title'] = $prods['product'][$prod_id]['product_title'];
						$tmp[$order]['product_img'] = $prods['product'][$prod_id]['multyprice'][$values['id']]['o_img'] ;
						$tmp[$order]['product_price'] = $prods['product'][$prod_id]['multyprice'][$values['id']]['price'];
						$tmp[$order]['type'] = 'multiprice';
						$tmp[$order]['id'] = $values['id'];
						$tmp[$order]['count'] = $values['count'];
						$multydetails = $prods['product'][$prod_id]['multyprice'][$values['id']];
						$product_detail = CModule::LoadModule('product');
	// 					var_dump($multydetails);
						if(is_object($product_detail)){
							$prod_det = $product_detail->GetDatas($prod_id);
							$tmp[$order]['attr_group1'] = $prod_det['attributes'][$multydetails['attr_group1']]['text'];
							if($multydetails['attr_group2'])
								$tmp[$order]['attr_group2'] = $prod_det['attributes'][$multydetails['attr_group2']]['text'];
							else $tmp[$order]['attr_group2'] = "";
							if(isset($prod_det['attributes'][$multydetails['attr_group1']]['vals'][$multydetails['attr_value1']]['unit_value']))
								$tmp[$order]['attr_value1'] = $prod_det['attributes'][$multydetails['attr_group1']]['vals'][$multydetails['attr_value1']]['unit_value'];
							if($multydetails['attr_group2'])
								$tmp[$order]['attr_value2'] = $prod_det['attributes'][$multydetails['attr_group2']]['vals'][$multydetails['attr_value2']]['unit_value'];
							else $tmp[$order]['attr_value2'] = "";
						}
						//Take discount if have and resumm
						if(isset($prods['discount'][$prod_id])){
	// 						var_dump($prods);die;
							
							$discount = $prods['discount'][$prod_id];
							$cur_percent = 0;
							foreach ($discount['count'] as $index=>$cur_count){
								if($cur_count<$values['count'])
									$cur_percent = $discount['percent'][$index];
							}
// 							echo $cur_percent;die;
							if($cur_percent){
								$tmp[$order]['dis_price'] = round($tmp[$order]['product_price']*(1-$cur_percent/100),2);
								
							}
							else $tmp[$order]['dis_price'] = $tmp[$order]['product_price'];
						}
						$tmp[$order]['cur_summ'] = round($values['count']*$tmp[$order]['dis_price'],2);
					}
				}
				if($values['type']=='product'){
					$prod_id = $values['id'];
					if(isset($prods['product'][$prod_id])){
						$tmp[$order]['product_title'] = $prods['product'][$prod_id]['product_title'];
						$tmp[$order]['product_img'] = $prods['product'][$prod_id]['product_image'];
						if($tmp[$order]['product_img']){
							$at = new CAttach($tmp[$order]['product_img']);
							$tmp[$order]['product_img'] = $at->GetURL();
						}
						$tmp[$order]['product_price'] = $prods['product'][$prod_id]['product_price'];
						$tmp[$order]['type'] = 'product';
						$tmp[$order]['id'] = $values['id'];
						$tmp[$order]['count'] = $values['count'];
						//Take discount if have and resumm
						if(isset($prods['discount'][$prod_id])){
							$discount = $prods['discount'][$prod_id];
							$cur_percent = 0;
							foreach ($discount['count'] as $index=>$cur_count){
								if($cur_count<$values['count'])
									$cur_count = $discount['percent'][$index];
							}
							if($cur_percent){
								$tmp[$order]['dis_price'] = round($tmp[$order]['product_price']*(1-$cur_percent/100),2);
							}
							else $tmp[$order]['dis_price'] = $tmp[$order]['product_price'];
						}
						$tmp[$order]['cur_summ'] = round($values['count']*$tmp[$order]['dis_price'],2);
					}
				}
				$ret = $tmp;

			}
			return $ret;
		}
		return [];	
	}
	
	static function DeleteFromCart($subject_id, $subject_type){
		if(isset($_SESSION['cart'])){
			foreach ($_SESSION['cart'] as $order=>$vals){
				if($vals['id']==$subject_id && $vals['type']==$subject_type){
					unset($_SESSION['cart'][$order]);
					if (CUser::GetUserID()) {
						Cmwdb::$db->where('uid', CUser::GetUserID());
						Cmwdb::$db->update(self::$tbl_name, ['data' => json_encode($_SESSION['cart'])]);
					}
					return true;
				}
			}
		}

		return false;
	}

	static function InitCart()
	{
		if(isset($_SESSION['user'])){
			Cmwdb::$db->where('uid', CUser::GetUserID());
			$data = Cmwdb::$db->getValue(self::$tbl_name, 'data');
			if ($data) {
				//we must firstly check, if in session we have cart datas and if it
				//is not loaded from db, then its datas must merged with db datas and updated,
				//else nothing to do
				if(isset($_SESSION['cart'])){
					//Do verifications for merge or not
					//if isset ['cart']['from_db'] and its true, just erase session datas and rewrite it
					if(isset($_SESSION['state']['from_db'])){
						if($_SESSION['state']['from_db']){
							$_SESSION['cart'] = json_decode($data, true);
						}
						else{
							//in session we have add any products to cart before login
							//so we need firtly merge all datas
							$data = json_decode($data, true);
							$temp = $_SESSION['cart'];
							$finded = array();
							$is_finded = false;
							foreach ($data as $order=>$elements){
								$is_finded = false;
								foreach ($_SESSION['cart'] as $cur_order=>$cur_elements){
									if(($elements['type']==$cur_elements['type'] && $elements['id']==$cur_elements['id'])){
										$finded[$elements['type']][] = $elements['id'];
										$temp[$cur_order]['count']+=$elements['count'];
										$temp[$cur_order]['id'] = $elements['id'];
										$is_finded = true;
									}
								}
								if(!$is_finded){
									$finded[$elements['type']][] = $elements['id'];
									$temp[] = $elements;
									
								}
							}
							$_SESSION['cart'] = $temp;
							Cmwdb::$db->where('uid', CUser::GetUserID());
							Cmwdb::$db->update(self::$tbl_name, ['data'=>json_encode($_SESSION['cart'])]);
								
							$_SESSION['state']['from_db'] = true;
						}
						
					}
					else{
						//in session we have add any products to cart before login
						//so we need firtly merge all datas
						$data = json_decode($data, true);
						$temp = $_SESSION['cart'];
						$finded = array();
						$is_finded = false;
						foreach ($data as $order=>$elements){
							$is_finded = false;
							foreach ($_SESSION['cart'] as $cur_order=>$cur_elements){
								if(($elements['type']==$cur_elements['type'] && $elements['id']==$cur_elements['id'])){
									$finded[$elements['type']][] = $elements['id'];
									$temp[$cur_order]['count']+=$elements['count'];
									$temp[$cur_order]['id'] = $elements['id'];
									$is_finded = true;
								}
							}
							if(!$is_finded){
								$finded[$elements['type']][] = $elements['id'];
								$temp[] = $elements;
								
							}
						}
						$_SESSION['cart'] = $temp;
						Cmwdb::$db->where('uid', CUser::GetUserID());
						Cmwdb::$db->update(self::$tbl_name, ['data'=>json_encode($_SESSION['cart'])]);
							
						$_SESSION['state']['from_db'] = true;
					}
				}
				else{
					//Cart firsty initializing directly from db, so nothing to do
					$_SESSION['cart'] = json_decode($data, true);
					$_SESSION['state']['from_db'] = true;
				}
			} 
			else {
				if(isset($_SESSION['cart']))
					Cmwdb::$db->insert(self::$tbl_name, ['data' => json_encode($_SESSION['cart']),'uid'=>CUser::GetUserID()]);
				else Cmwdb::$db->insert(self::$tbl_name, ['data' => json_encode([]),'uid'=>CUser::GetUserID()]);
			}
		}
	}

	static function DeleteFromCartInDB($ids, $subject_type){
		$likeString = "";
		if((is_array($ids) || is_numeric($ids)) && !empty($ids)){
			foreach ($ids as $id){
				$likeString = '"type":"'.$subject_type.'","id":'.$id;
				Cmwdb::$db->where('data', '%'.$likeString.'%', "like");
				$res = Cmwdb::$db->get(self::$tbl_name); 
				//if result is not empty, foreach it and take jsons,
				//then modify what we need and update
				if(!empty($res)){
					foreach ($res as $any_cart){
						//In any_cart we have id and json cart, we use it
						$cart_id = $any_cart['id'];
						$cart = json_decode($any_cart['data'], true);
						//verify, if content of cart is not empty, 
						//find and remove deleted muyltyprice or product
						if(!empty($cart)){
							foreach ($cart as $order=>$values){
								if($values['id']==$id && $values['type']==$subject_type){
									unset($cart[$order]);	
								}
								
							}
						}
						Cmwdb::$db->where('id', $cart_id);
						Cmwdb::$db->update(self::$tbl_name, ['data'=>json_encode($cart)]);
						
					}
				}
			}
		}
	}
	
	static function EmptyCart(){
		try {
			if(!isset($_SESSION['cart']))throw new Exception('Error: no defined shopping cart');
			$_SESSION['cart'] = [];
			$uid = CUser::GetUserID();
			Cmwdb::$db->where('uid', $uid);
			if(!Cmwdb::$db->update(self::$tbl_name, ['data'=>json_encode([])]))
				throw new Exception('Error: cant update db');
			return [
				'status'=>1,
				'result'=>""	
			];
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
}
?>