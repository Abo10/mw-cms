<?php

class CCurrency{
	protected $configs = array();
	protected $currencies = array();
	function __construct(){
		$this->currencies = CConfig::GetBlock();
		$this->configs['web_module_dir'] = CConfig::GetKey('web_module_dir');
		$this->configs['currency_default'] = CConfig::GetKey('currency_default');
		$this->configs['rank_currency'] = CConfig::GetKey('rank_currency');
		
// 		echo '<pre>';
// 		echo "Currencies is: <br>";
// 		var_dump($this->configs);
// 		echo '<hr>';
// 		foreach ($this->configs as $key=>$value)
// 			echo $key.' : '.$value.'<br>';
// 		echo '</pre>';
	}
	
	function GetPrice($price, $price_currency, $need_currency){
		if(isset($this->currencies[$price_currency]) && isset($this->currencies[$need_currency])){
			$cur_rank = $this->currencies[$price_currency]['rank'];
			if($need_currency===$this->configs['rank_currency']){
				return $price/$cur_rank;
			}
			$cur_price = $price/$cur_rank;
			return $this->currencies[$need_currency]['rank']*$cur_price;
		}
		return false;
	}
	
	function GetDefaultCurrency(){
		$config = CConfig::GetKey('currency_default');
		return $config;
	}
	
	function GetCurrentCurrency(){
		if(isset($_SESSION['config']['currency']))
			return $_SESSION['config']['currency'];
		$_SESSION['config']['currency'] = CConfig::GetKey('currency_default');
		return $_SESSION['config']['currency'];
	}
	
	function SetCurrentCurrency($currency=null){
		if(!$currency)$currency = CConfig::GetKey('currency_default');
		$currencies = CConfig::GetBlock();
		if(!array_key_exists($currency, $currencies))
			return $this->GetCurrentCurrency();
		$_SESSION['config']['currency'] = $currency;
		return $currency;
	}
	
	function GetAllCurrencies(){
		return $this->currencies;
	}
	
	function GetCurrency($currency){
		if(isset($this->currencies[$currency]))
			return $this->currencies[$currency];
		return false;
	}
	
	function GetSymbol($currency){
		$ret = $this->GetCurrency($currency);
		if($ret)return $ret['symbol'];
		return false;
	}

	function GetDesc($currency){
		$ret = $this->GetCurrency($currency);
		if($ret)return $ret['desc'];
		return false;
	}
	
	function GetImg($currency){
		$ret = $this->GetCurrency($currency);
		if($ret)return $ret['icon'];
		return false;
	}
	
	function GetRank($currency){
		$ret = $this->GetCurrency($currency);
		if($ret)return $ret['rank'];
		return false;
	}
	
	
	
}
?>