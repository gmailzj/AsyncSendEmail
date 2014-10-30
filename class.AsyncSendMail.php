<?php
/*
 * 异步邮件发送
 * 在一个无限循环中，取队列头部元素，如果队列为空，等待5s再执行
 */
class AsyncSendMail {


	//构造函数
	function __construct($redis, $key) {
		$this->redis = $redis;

		//字符串或者数组
		$this->keyArr = $key;
	}

	function init($data){

	}

	//每一封要发送的邮件数据
	function quenePush($data){
		if(empty($data)){
			return false;
		}
		$data = serialize($data);
		$this->redis->rPush($this->keyArr, $data);

	}


	function daemon($callback){

		$params = func_get_args();

		//var_dump($params);

		array_shift($params);


		while(true){


			$param_arr = $params;

			$data =$this->redis->bLpop($this->keyArr, 5);

			//如果data为真，就发送邮件
			if($data){

				//反序列化以后的真实php数据
				$phpData = unserialize($data[1]);

				//插入队列数据
				array_unshift( $param_arr, $phpData);

				var_dump($param_arr);

				call_user_func_array($callback,  $param_arr);

			}
		}
	}

}