<?php
/*
 * 异步邮件发送
 * 在一个无限循环中，取队列头部元素，如果队列为空，等待5s再执行
 */
error_reporting(-1);

$redis = new Redis();
$redis->connect("127.0.0.1");
$keyArr = array("queneSignupMail", "queneMailAccountActive", "queneMailProgress");

require_once("/home/jian/php/class.AsyncSendMail.php");
$mailQuene = new AsyncSendMail($redis, $keyArr);

$mailQuene->daemon("sendMail",1,2);


function sendMail($data){


}