<?php
CModule::LinkModule('cart');
class CFrontCart extends CCart{
	
	
}
// 	static $tbl_name = 'module_cart';

// 	static function AddToCart($subject_id, $subject_type){
// // 		var_dump($subject_id);
// 		if(is_array($subject_id)){
// 			if(isset($_SESSION['cart']))
// 				$cur_order = count($_SESSION['cart'])+1;
// 			else{
// 				$_SESSION['cart'] = array();
// 				$cur_order = 1;
// 			}
// 			foreach ($subject_id as $index=>$content){
// 				foreach ($content as $id=>$count){
// 					$is_finded = false;
// 					foreach ($_SESSION['cart'] as $order=>$values){
// 						if(in_array($id, $values) && in_array($subject_type, $values)){
// 							$_SESSION['cart'][$order]['count']+=$count;
// 							$is_finded = true;
// 						}						
// 					}
// 					if(!$is_finded){
// 						$_SESSION['cart'][$cur_order]['count']=$count;
// 						$_SESSION['cart'][$cur_order]['type']=$subject_type;
// 						$_SESSION['cart'][$cur_order]['id']=$id;;
// 						$cur_order++;
// 						$is_finded = false;
// 					}
// 				}
// 			}
// 		}

// 		if(isset($_SESSION['cart'])){
// 			if (CUser::GetUserID()) {
// 				Cmwdb::$db->where('uid', CUser::GetUserID());
// 				Cmwdb::$db->update(self::$tbl_name, ['data' => json_encode($_SESSION['cart'])]);
// 			}
// 		}
// 		return 1;
// 	}
	
// 	static function GetCart(){
// 		$multyprice = array();
// 		$products = array();
// // 		var_dump($_SESSION);die;
// 		if(isset($_SESSION['cart'])){
// 			foreach ($_SESSION['cart'] as $vals){
// 				if($vals['type']=="multiprice")$multyprice[] = $vals['id'];
// 				if($vals['type']=="product")$products[] = $vals['id'];
// 			}

// 			$multylist = CFrontProduct::GetByMultyprice($multyprice);
// 			$needProducts = array();
// 			//complate products list from multyprice
// 			foreach ($multylist as $prod_id){
// 				if(in_array($prod_id, $needProducts))
// 					continue;
// 				$needProducts[] = $prod_id;
// 			}
// 			//add single products
// 			foreach ($products as $prod_id){
// 				if(in_array($prod_id, $needProducts))
// 					continue;
// 				$needProducts[] = $prod_id;
				
// 			}
// 			//Take all needed products
// 			$prods = CFrontProduct::GetDatas($needProducts,['attributika'=>1]);
// 			$ret = array();
// 			$tmp = array();
// // 			var_dump($prods);die;
// 			foreach ($_SESSION['cart'] as $order=>$values){
// 				if($values['type']=='multiprice'){
// 					$prod_id = $multylist[$values['id']];
// 					$tmp[$order]['product_title'] = $prods['product'][$prod_id]['product_title'];
// 					$tmp[$order]['product_img'] = $prods['product'][$prod_id]['multyprice'][$values['id']]['o_img'] ;
// 					$tmp[$order]['product_price'] = $prods['product'][$prod_id]['multyprice'][$values['id']]['price'];
// 					$tmp[$order]['type'] = 'multiprice';
// 					$tmp[$order]['id'] = $values['id'];
// 					$tmp[$order]['count'] = $values['count'];
// 					$multydetails = $prods['product'][$prod_id]['multyprice'][$values['id']];
// 					$product_detail = CModule::LoadModule('product');
// // 					var_dump($multydetails);
// 					if(is_object($product_detail)){
// 						$prod_det = $product_detail->GetDatas($prod_id);
// 						$tmp[$order]['attr_group1'] = $prod_det['attributes'][$multydetails['attr_group1']]['text'];
// 						if($multydetails['attr_group2'])
// 							$tmp[$order]['attr_group2'] = $prod_det['attributes'][$multydetails['attr_group2']]['text'];
// 						else $tmp[$order]['attr_group2'] = "";
// 						if(isset($prod_det['attributes'][$multydetails['attr_group1']]['vals'][$multydetails['attr_value1']]['unit_value']))
// 							$tmp[$order]['attr_value1'] = $prod_det['attributes'][$multydetails['attr_group1']]['vals'][$multydetails['attr_value1']]['unit_value'];
// 						if($multydetails['attr_group2'])
// 							$tmp[$order]['attr_value2'] = $prod_det['attributes'][$multydetails['attr_group2']]['vals'][$multydetails['attr_value2']]['unit_value'];
// 						else $tmp[$order]['attr_value2'] = "";
// 					}
// 					//Take discount if have and resumm
// 					if(isset($prods['discount'][$prod_id])){
// // 						var_dump($prods);die;
						
// 						$discount = $prods['discount'][$prod_id];
// 						$cur_percent = 0;
// 						foreach ($discount['count'] as $index=>$cur_count){
// 							if($cur_count<$values['count'])
// 								$cur_percent = $discount['percent'][$index];
// 						}
// // 						echo $cur_percent;die;
// 						if($cur_percent){
// 							$tmp[$order]['dis_price'] = round($tmp[$order]['product_price']*(1-$cur_percent/100),2);
							
// 						}
// 						else $tmp[$order]['dis_price'] = $tmp[$order]['product_price'];
// 					}
// 					$tmp[$order]['cur_summ'] = round($values['count']*$tmp[$order]['dis_price'],2);
// 				}
// 				if($values['type']=='product'){
// 					$prod_id = $values['id'];
// 					$tmp[$order]['product_title'] = $prods['product'][$prod_id]['product_title'];
// 					$tmp[$order]['product_img'] = $prods['product'][$prod_id]['product_image'];
// 					if($tmp[$order]['product_img']){
// 						$at = new CAttach($tmp[$order]['product_img']);
// 						$tmp[$order]['product_img'] = $at->GetURL();
// 					}
// 					$tmp[$order]['product_price'] = $prods['product'][$prod_id]['product_price'];
// 					$tmp[$order]['type'] = 'product';
// 					$tmp[$order]['id'] = $values['id'];
// 					$tmp[$order]['count'] = $values['count'];
// 					//Take discount if have and resumm
// 					if(isset($prods['discount'][$prod_id])){
// 						$discount = $prods['discount'][$prod_id];
// 						$cur_percent = 0;
// 						foreach ($discount['count'] as $index=>$cur_count){
// 							if($cur_count<$values['count'])
// 								$cur_count = $discount['percent'][$index];
// 						}
// 						if($cur_percent){
// 							$tmp[$order]['dis_price'] = round($tmp[$order]['product_price']*(1-$cur_percent/100),2);
// 						}
// 						else $tmp[$order]['dis_price'] = $tmp[$order]['product_price'];
// 					}
// 					$tmp[$order]['cur_summ'] = round($values['count']*$tmp[$order]['dis_price'],2);
// 				}
// 				$ret = $tmp;

// 			}
// 			return $ret;
// 		}
// 		return [];	
// 	}
	
// 	static function DeleteFromCart($subject_id, $subject_type){
// 		if(isset($_SESSION['cart'])){
// 			foreach ($_SESSION['cart'] as $order=>$vals){
// 				if($vals['id']==$subject_id && $vals['type']==$subject_type){
// 					unset($_SESSION['cart'][$order]);
// 					if (CUser::GetUserID()) {
// 						Cmwdb::$db->where('uid', CUser::GetUserID());
// 						Cmwdb::$db->update(self::$tbl_name, ['data' => json_encode($_SESSION['cart'])]);
// 					}
// 					return true;
// 				}
// 			}
// 		}

// 		return false;
// 	}

// 	static function InitCart()
// 	{
// 		Cmwdb::$db->where('uid', CUser::GetUserID());
// 		$data = Cmwdb::$db->getValue(self::$tbl_name, 'data');
// 		if ($data) {
// 			$_SESSION['cart'] = json_decode($data, true);
// 		} else {
// 			Cmwdb::$db->insert(self::$tbl_name, ['data' => json_encode([]),'uid'=>CUser::GetUserID()]);
// 		}

// 	}

// 	static function DeleteFromCartInDB($ids, $subject_type){
		
// 	}
// }
?>