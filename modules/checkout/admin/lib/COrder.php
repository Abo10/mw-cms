<?php

class COrder{
	static protected $tbl_order_products = "module_product_order_products";
	static protected $tbl_order_details = "module_product_order_details";
	static protected $tbl_addressing_bridge = "module_order_addressing_bridge";
	static protected $tbl_shipping = "module_order_shipping";
	static protected $tbl_billing = "module_order_billing";
	static function CreateOrder(){
		try {
			if(isset($_SESSION['checkout'])){
				$queryData = array();
				//Prepare neccasary datas to insert as first step of order
				$user_datas = GetUserDatas();
				if($user_datas['status']){
					$queryData['uid'] = $user_datas['result']['uid'];
					$queryData['u_type'] = $user_datas['result']['type'];
				}
				else $queryData['u_type'] = 'token';
				$queryData['u_token'] = CreateToken();
				if(isset($_SESSION['checkout']['shipping']['delivary_date']))
					$queryData['order_data'] = $_SESSION['checkout']['shipping']['delivary_date'];
				else $queryData['order_data'] = date("Y/m/d H:i:s");
				$queryData['order_status'] = 0;//Its meen, that order just created
				if(isset($_SESSION['checkout']['order_datas']['order_notes']))
					$queryData['order_notes'] = $_SESSION['checkout']['order_datas']['order_notes'];
				if(!Cmwdb::$db->insert(self::$tbl_order_details, $queryData))
					throw new Exception('Error: cant insert into db');
				$order_id = Cmwdb::$db->getInsertId();
				//now creating shipping and billing for this order only
				$datas = array();
				if(isset($_SESSION['checkout']['shipping']))$datas['shipping'] = $_SESSION['checkout']['shipping'];
				if(isset($_SESSION['checkout']['billing']))$datas['billing'] = $_SESSION['checkout']['billing'];
				$sh_ret = self::CreateShippingBilling($order_id, $datas);
				if(!$sh_ret['status'])throw new Exception("Error: failed to create billin/shipping datas");
				//TODO: Must change function CModule::LinkModule to return array
				//		with status and result
				CModule::LinkModule('cart');
				$cart = CCart::GetCart();
// 				return $cart;
				if(empty($cart))throw new Exception('Error: empty or missing shopping cart');
				Cmwdb::$db->startTransaction();
				$productDatas['order_id'] = $order_id;
				$shipping_datas = array();
				$module_product = CModule::LoadModule('product');
				$product_counts = array();
				$order_price = 0;
				foreach ($cart as $cart_elements){
					$productDatas['product_id'] = $cart_elements['id'];
					$productDatas['product_type'] = $cart_elements['type'];
					$productDatas['product_count'] = $cart_elements['count'];
					$product_counts[$cart_elements['id']] = $cart_elements['count'];
					$productDatas['product_price'] = $cart_elements['dis_price'];
					$order_price+=$cart_elements['count']*$cart_elements['dis_price'];
					if(!Cmwdb::$db->insert(self::$tbl_order_products, $productDatas)){
						Cmwdb::$db->rollback();
						throw new Exception('Error: cant insert product to db'.Cmwdb::$db->getLastQuery());
					}
					if(is_object($module_product))
						$shipping_datas[$productDatas['product_id']] = $module_product->GetShippingNeeded($productDatas['product_id']);
				}
				$cart_res =CCart::EmptyCart(); 
				if(!$cart_res['status']){
					Cmwdb::$db->rollback();
					throw new Exception($cart_res['result']);
				}
				//Do commit, if all steps done, we must add products and shipping
				$ret = [
					'status'=>1,
					'result'=>['order_id'=>$order_id],
					'has_notice'=>0,
					'notice'=>""	
				];
				Cmwdb::$db->commit();
				if(isset($_SESSION['checkout']['shipping'])){
					$shipping = CModule::LinkModule('shipping');
// 					if(!$shipping['status']){
// 						return [
// 									'status'=>1,
// 									'result'=>$order_id,
// 									'has_notice'=>1,
// 									'notice'=>"No shipping module"
// 						];
// 					}
					$shipping_method = "local";
					$send_to_shipping = [];
					if(isset($_SESSION['checkout']['shipping']['shipping_method']))
						$shipping_method = $_SESSION['checkout']['shipping']['shipping_method'];
					$send_to_shipping['shipping_cart'] = self::GetShipping($order_id);
					$send_to_shipping['shipping_datas'] = $shipping_datas;
					foreach ($send_to_shipping['shipping_datas'] as $index=>$unneed)
						$send_to_shipping['shipping_datas'][$index]['count'] = $product_counts[$index];
					$send_to_shipping['shipping_method'] = $shipping_method;
					$shipping_answer = CShipping::AddShipping($send_to_shipping, $order_id,$shipping_method);
					$update_prices = [];
					$update_prices['order_price'] = $order_price;
					$shipping_price = 0;
					$shipping_id = 0;
					if($shipping_answer['status']){
						$shipping_price = $shipping_answer['result']['shipping_price'];
						$shipping_id = $shipping_answer['result']['shipping_id'];
					}
					$update_prices['shipping_price'] = $shipping_price;
					$update_prices['shiping_id'] = $shipping_id;
					Cmwdb::$db->where('id', $order_id);
					if(!Cmwdb::$db->update(self::$tbl_order_details, $update_prices))
						throw new Exception('Error: cant update prices after creating simple order');
					$ret['result']['order_price'] = $order_price;
					$ret['result']['shipping_price'] = $shipping_price;
					$ret['result']['total_price'] = $shipping_price+$order_price;
					if(isset($_SESSION['checkout']['payment']['payment'])){
						CModule::LinkModule('banking');
						$banking_datas = [];
						$banking_datas['amount'] = $ret['result']['total_price'];
						$banking_datas['order_description'] = "Invoice number ".$order_id;
						$banking_answer = CBanking::AddTransaction($order_id, $banking_datas, $_SESSION['checkout']['payment']['payment']);
						if($banking_answer['status']){
							$banking_update = [];
							$banking_update['banking_type'] = $_SESSION['checkout']['payment']['payment'];
							$banking_update['banking_id'] = $banking_answer['result'];
							Cmwdb::$db->where('id', $order_id);
							Cmwdb::$db->update(self::$tbl_order_details, $banking_update);
						}

					}

					//TODO: correct billing and shipping for storing in order details
					//TODO: correct shipping id in order details
					return $ret;
				}
								
				throw new Exception('Error: no shipping datas was founted');
			}
			throw new Exception('Error: no neccasery datas was fount');
		}
		catch (Exception $error){
			return ['status'=>0, 'result'=>$error->getMessage()];
		}
	}
	
// 	static function CreateOrder(){
// 		try {
// 			$args = array();
// 			if(isset($_SESSION['checkout']['shipping']))$args['shipping'] = $_SESSION['checkout']['shipping'];
// 			if(isset($_SESSION['checkout']['billing']))$args['billing'] = $_SESSION['checkout']['billing'];
			
// 		}
// 		catch (Exception $error){
// 			return ['status'=>0, 'result'=>$error->getMessage()];
// 		}
// 	}
	
	static function AddStep($step_name, $args){
		return $_SESSION['checkout'][$step_name] = $args;
	}
	
	static protected function ReadBillingShipping($type, $args){
		
	}
	
	static function CreateShippingBilling($order_id, $args){
		try {
			$res = self::AddAddrBridge($args);
			if(!$res['status'])return $res;
			$queryData = array();
			$queryData['order_id']=$order_id;
			$ret = array();
			$ret['status']=1;
			if(isset($res['result']['shipping'])){
				(isset($args['shipping']))?$queryData['shipping_datas']=json_encode($args['shipping']):$queryData['datas']=json_encode([]);
				$queryData['shipping_bridge_id'] = $res['result']['shipping'];
				if(!Cmwdb::$db->insert(self::$tbl_shipping, $queryData))
					throw new Exception('Error: cant insert into table');
					$ret['result']['shipping'] = Cmwdb::$db->getInsertId();
			}
			$queryData = array();
			$queryData['order_id']=$order_id;
				
			if(isset($res['result']['billing'])){
				(isset($args['billing']))?$queryData['billing_datas']=json_encode($args['billing']):$queryData['datas']=json_encode([]);
				$queryData['billing_bridge_id'] = $res['result']['billing'];
				if(!Cmwdb::$db->insert(self::$tbl_billing, $queryData))
					throw new Exception('Error: cant insert into table');
				$ret['result']['billing'] = Cmwdb::$db->getInsertId();
			}
			return $ret;
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}
	}
	static function AddAddrBridge($args){
		try {
			$ret = array();
			$ret['status'] = 1;
			if(isset($args['shipping'])){
				$ret['result']['shipping'] = self::AddShippingAddressToBridge($args['shipping']);
			}
			if(isset($args['billing'])){
				$ret['result']['billing'] = self::AddShippingAddressToBridge($args['billing']);
			}
			return $ret;
				
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
	
	static function AddShippingAddressToBridge($args){
// 		CModule::LinkModule('addressing');
// 		$mass = CAddressing::FindTree($args);
		$queryData = array();
// 		if($mass['status']){
			(isset($args['country']))?$queryData['b_country'] = $args['country']:$queryData['b_country']=null;
			(isset($args['state']))?$queryData['b_state'] = $args['state']:$queryData['b_state']=null;
			(isset($args['city']))?$queryData['b_city'] = $args['city']:$queryData['b_city']=null;
			(isset($args['community']))?$queryData['b_community'] = $args['community']:$queryData['b_community']=null;
// 		}
		$queryData['b_handles'] = json_encode($args);
		if(!Cmwdb::$db->insert(self::$tbl_addressing_bridge, $queryData))
			throw new Exception('Error: cant insert into table');
		$id = Cmwdb::$db->getInsertId();
		return $id;		
	}
	
	static function AddBillingAddressToBridge($args){
// 		CModule::LinkModule('addressing');
// 		$mass = CAddressing::FindTree($args);
		$queryData = array();
// 		if($mass['status']){
			(isset($args['country']))?$queryData['b_country'] = $args['country']:$queryData['b_country']=null;
			(isset($args['state']))?$queryData['b_state'] = $args['state']:$queryData['b_state']=null;
			(isset($args['city']))?$queryData['b_city'] = $args['city']:$queryData['b_city']=null;
			(isset($args['community']))?$queryData['b_community'] = $args['community']:$queryData['b_community']=null;
// 		}
		$queryData['b_handles'] = json_encode($args);
		if(!Cmwdb::$db->insert(self::$tbl_addressing_bridge, $queryData))
			throw new Exception('Error: cant insert into table');
		$id = Cmwdb::$db->getInsertId();
		return $id;
	}
	
	static function GetShipping($order_id){
		Cmwdb::$db->where(self::$tbl_shipping.'.order_id', $order_id);
		Cmwdb::$db->join(self::$tbl_billing, self::$tbl_billing.'.order_id='.self::$tbl_shipping.'.order_id');
		Cmwdb::$db->join(self::$tbl_addressing_bridge. ' b1', self::$tbl_billing.'.billing_bridge_id=b1.b_id');
		Cmwdb::$db->join(self::$tbl_addressing_bridge. ' b2', self::$tbl_shipping.'.shipping_bridge_id=b2.b_id');
				
		$res = Cmwdb::$db->getOne(self::$tbl_shipping,[
				self::$tbl_shipping.'.*',
				self::$tbl_billing.'.*',
				'b1.b_country billing_country',
				'b1.b_state billing_state',
				'b1.b_city billing_city',
				'b1.b_community billing_community',
				'b2.b_country shipping_country',
				'b2.b_state shipping_state',
				'b2.b_city shipping_city',
				'b2.b_community shipping_community',
				
		]);
		if($res){
			if($res['shipping_datas'])$res['shipping_datas'] = json_decode($res['shipping_datas'], true);
			if($res['billing_datas'])$res['billing_datas'] = json_decode($res['billing_datas'], true);
			$addr = self::VerifyShipping($res['shipping_datas']);
			if(CModule::HasModule('addressing')){
				CModule::LinkModule('addressing');
				$addr = CAddressing::GetTree($addr);
			}
			$res['address_ext'] = [];
			if(isset($addr['country']))$res['address_ext']['country']= $addr['country']['addr_datas']['text'][CLanguage::getInstance()->getCurrent()];
			else{
				(isset($res['shipping_datas']['h_country']))?
					$res['address_ext']['country'] = $res['shipping_datas']['h_country']:$res['address_ext']['country'] = null;
			}
			if(isset($addr['state']))$res['address_ext']['state']= $addr['state']['addr_datas']['text'][CLanguage::getInstance()->getCurrent()];
			else{
				(isset($res['shipping_datas']['h_state']))?
					$res['address_ext']['state'] = $res['shipping_datas']['h_state']:$res['address_ext']['state'] = null;
			}
			if(isset($addr['city']))$res['address_ext']['city']= $addr['city']['addr_datas']['text'][CLanguage::getInstance()->getCurrent()];
			else{
				(isset($res['shipping_datas']['h_city']))?
					$res['address_ext']['city'] = $res['shipping_datas']['h_city']:$res['address_ext']['city'] = null;
			}
			(isset($res['shipping_datas']['h_address']))?
				$res['address_ext']['address'] =$res['shipping_datas']['h_address']:$res['address_ext']['address'] = null; 
				
// 			if($res['b_handles'])$res['b_handles'] = json_decode($res['b_handles'], true);
// 			if($res['datas'])$res['datas'] = json_decode($res['datas'], true);
		}
		$res['country'] = $res['shipping_country'];
		$res['state'] = $res['shipping_state'];
		$res['city'] = $res['shipping_city'];
		$res['community'] = $res['shipping_community'];
		return $res;
	}
	
	static protected function VerifyShipping($args){
		$is_handled = false;
		if(isset($args['country']) && $args['country'])$is_handled = $args['country'];
		if(isset($args['state']) && $args['state'])$is_handled = $args['state'];
		if(isset($args['city']) && $args['city'])$is_handled = $args['city'];
		if(isset($args['community']) && $args['community'])$is_handled = $args['community'];
		return $is_handled;
	}
	
	static function DeleteByUsers($uid, $u_type="std_user"){
		try {
			Cmwdb::$db->where('uid', $uid);
			Cmwdb::$db->where('u_type', $u_type);
			$orders = Cmwdb::$db->get(self::$tbl_order_details, null, ['id']);
			if(empty($orders)){
				return [
					'status'=>1,
					'result'=>"Empty history of orders"	
				];
			}
			$temp = [];
			foreach ($orders as $ids)$temp[] = $ids['id'];
			$orders = $temp;
			Cmwdb::$db->startTransaction();
			//Delete all orders and order products
			Cmwdb::$db->where('uid', $uid);
			Cmwdb::$db->where('u_type', $u_type);
			if(!Cmwdb::$db->delete(self::$tbl_order_details)){
				Cmwdb::$db->rollback();
				throw new Exception("Error: cant delete orders");
			}
			Cmwdb::$db->where('order_id', $orders, "in");
			if(!Cmwdb::$db->delete(self::$tbl_order_products)){
				Cmwdb::$db->rollback();
				throw new Exception("Error: cant delete orders");
			}
			//Remove shipping request for this user
			if(CModule::HasModule('shipping')){
				CModule::LoadModule('shipping');
				$shipping_answer = CShipping::RemoveShipping($orders);
				if(!$shipping_answer['status']){
					Cmwdb::$db->rollback();
					throw new Exception("Error: cant clear shipping storage");
				}
			}
			//Remove shipping and billing datas for this user
			Cmwdb::$db->where('order_id', $orders, "in");
			if(!Cmwdb::$db->delete(self::$tbl_shipping)){
				Cmwdb::$db->rollback();
				throw new Exception("Error: cant delete shipping addresses");
			}
			if(!Cmwdb::$db->delete(self::$tbl_billing)){
				Cmwdb::$db->rollback();
				throw new Exception("Error: cant delete billing addresses");
			}
				
			Cmwdb::$db->commit();
			return [
				'status'=>1,
				'result'=>""	
			];
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage	
			];
		}
	}
	
	static function DeleteOrder($order_id){
		try {
			Cmwdb::$db->where('id', $order_id);
			Cmwdb::$db->startTransaction();
			//Delete all orders and order products
			if(!Cmwdb::$db->delete(self::$tbl_order_details)){
				Cmwdb::$db->rollback();
				throw new Exception("Error: cant delete orders");
			}
			Cmwdb::$db->where('order_id', $order_id);
			if(!Cmwdb::$db->delete(self::$tbl_order_products)){
				Cmwdb::$db->rollback();
				throw new Exception("Error: cant delete orders");
			}
			//Remove shipping request for this user
			if(CModule::HasModule('shipping')){
				CModule::LoadModule('shipping');
				$shipping_answer = CShipping::RemoveShippingByOrder($order_id);
				if(!$shipping_answer['status']){
					Cmwdb::$db->rollback();
					throw new Exception("Error: cant clear shipping storage");
				}
			}
			//Remove shipping and billing datas for this user
			Cmwdb::$db->where('order_id', $order_id);
			if(!Cmwdb::$db->delete(self::$tbl_shipping)){
				Cmwdb::$db->rollback();
				throw new Exception("Error: cant delete shipping addresses");
			}
			Cmwdb::$db->where('order_id', $order_id);
			if(!Cmwdb::$db->delete(self::$tbl_billing)){
				Cmwdb::$db->rollback();
				throw new Exception("Error: cant delete billing addresses");
			}
	
			Cmwdb::$db->commit();
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
	/*
	 * Function filter all address stack from argument then return 
	 * this array as result
	 * 1. country - selected from local module or hand entered value
	 * 2. state - selected from local module or hand entered value
	 * 3. city - selected from local module or hand entered value
	 * 4. address - configured string with format country, state, city, address
	 */
	static function GetFilteredAddressStack($args,$lang=null){
		try{
			$start_point = null;
			if(!$lang)$lang = CLanguage::getInstance()->getDefaultUser();
			if(isset($args['country']) && $args['country'])$start_point=$args['country'];
			if(isset($args['state']) && $args['state'])$start_point=$args['state'];
			if(isset($args['city']) && $args['city'])$start_point=$args['city'];
			CModule::LinkModule('addressing');
			$addr_datas = null;
			$ret = [
				'country'=>"",
				'country_id'=>null,	
				'state'=>"",
				'state_id'=>null,	
				'city'=>"",
				'city_id'=>null,
				'address'=>null,
				'address_ext'=>"",
				'manualy_created'=>false	
			];
			if($start_point){
				$addr_datas = CAddressing::GetTree($start_point);
				if(isset($addr_datas['country'])){
					$ret['country'] = $addr_datas['country']['addr_datas']['text'][$lang];
					$ret['country_id'] = $addr_datas['country']['addr_id'];
				}
				if(isset($addr_datas['state'])){
					$ret['state'] = $addr_datas['state']['addr_datas']['text'][$lang];
					$ret['state_id'] = $addr_datas['state']['addr_id'];
				}
				if(isset($addr_datas['city'])){
					$ret['city'] = $addr_datas['city']['addr_datas']['text'][$lang];
					$ret['city_id'] = $addr_datas['city']['addr_id'];
				}
			}
			else $ret['manualy_created'] = true;
			if(isset($args['h_country']) && $args['h_country'])$ret['country'] = $args['h_country'];
			if(isset($args['h_state']) && $args['h_state'])$ret['state'] = $args['h_state'];
			if(isset($args['h_city']) && $args['h_city'])$ret['city'] = $args['h_city'];
			if(isset($args['address']) && $args['address'])$ret['address'] = $args['address'];
			if(isset($args['h_address']) && $args['h_address'])$ret['h_address'] = $args['h_address'];
			if($ret['country'])$ret['address_ext'].=$ret['country'].', ';
			if($ret['state'])$ret['address_ext'].=$ret['state'].', ';
			if($ret['city'])$ret['address_ext'].=$ret['city'].', ';
			if($ret['address'])$ret['address_ext'].=$ret['address'];
			return [
				'status'=>1,
				'result'=>$ret
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