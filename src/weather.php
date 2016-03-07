<?php

require_once('workflows.php');

class Weather{

	private $url = "http://api.map.baidu.com/telematics/v3/weather?output=json&ak=Gy7SGUigZ4HxGYDaq9azWy09&location=";
	
	private $locationUrl = "http://api.map.baidu.com/location/ip?ak=ZmjUrFm4QT13mrgUrHcYXRIt";
	
	function __construct() {
		$this->workflows = new Workflows();
	}

	public function getWeather($query){
		if ( empty($query) ) {
			$query = $this->getDefaultCity();
		}
		$api = $this->url.$query;
		$res = $this->workflows->request($api);
		$res = json_decode( $res );
		if ($res->error === 0) {
			$forecast = $res->results[0]->weather_data;
			foreach($forecast as $key=>$value) {
				$date = mb_substr($value->date,0,6);
				$this->workflows->result( $key,
									$query,
									$query."      ".$date."      ".$value->weather,
									$value->wind.", 温度：".$value->temperature,
									$value->weather.".png");
			}
		}else{
			$this->workflows->result(	'',
		  						'',
					  			'没查到呀', 
					  			'没找到你所查询城市的天气',
					  			'unknown.png' );
		}

		echo $this->workflows->toxml();
	}
	
	private function getDefaultCity(){
		$res = $this->workflows->request($this->locationUrl);
		$location = json_decode($res, true);
		
		return !empty($location['content']['address']) ? $location['content']['address'] : '北京市';
	} 
	
}