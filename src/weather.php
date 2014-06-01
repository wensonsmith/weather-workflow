<?php

require_once('workflows.php');

class Weather{

	private $url = "http://api.map.baidu.com/telematics/v3/weather?output=json&ak=Gy7SGUigZ4HxGYDaq9azWy09&location=";

	public function getWeather($query){
		$workflows = new Workflows();
		$api = $this->url.$query;
		$res = $workflows->request($api);
		$res = json_decode( $res );
		if ($res->error === 0) {
			$forecast = $res->results[0]->weather_data;
			foreach($forecast as $key=>$value) {
				$date = mb_substr($value->date,0,6);
				$workflows->result( $key,
									$query,
									$date."      ".$value->weather,
									$value->wind.", 温度：".$value->temperature,
									$value->weather.".png");
			}
		}else{
			$workflows->result(	'',
		  						'',
					  			'没查到呀', 
					  			'没找到你所查询城市的天气',
					  			'unknown.png' );
		}

		echo $workflows->toxml();
	}
}