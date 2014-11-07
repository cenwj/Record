<?php
// 包含 memcached 类文件
//包含队列
require_once('memcached-client.php');
//require_once('memcacheQueue.php');
// 选项设置 
$options = array(
    'servers' => array('192.168.1.10:11211'), //memcached 服务的地址、端口，可用多个数组元素表示多个 memcached 服务
    'debug' => false, //是否打开 debug
    'compress_threshold' => 10240, //超过多少字节的数据时进行压缩
    'persistant' => false //是否使用持久连接
);
// 创建 memcached 对象实例 
$mc = new memcached($options);
// 设置此脚本使用的唯一标识符 
$key = 'mykey';
// 往 memcached 中写入对象 
$mc->add($key, 'some random strings');
$val = $mc->get($key);
//echo "n" . str_pad('$mc->add() ', 60, '_') . "n";
echo '<pre>';
var_dump($val);
$key = "cenwj";
$v = serialize(array(1,2,3,4,5));
$val = $mc->add($key,$v);
$str = $mc->get($key);
$str = $mc->delete($key);
var_dump($str);

//$obj = new memcacheQueue('duilie');
//$obj->add('1asdf');
//$obj->getQueueLength();
//$obj->read(11);
//$dl = $obj->get(8);
//var_dump($dl);
//// 替换已写入的对象数据值
//$mc->replace($key, array('some' => 'haha', 'array' => 'xxx'));
//$val = $mc->get($key);
//echo "n" . str_pad('$mc->replace() ', 60, '_') . "n";
//var_dump($val);
//// 删除 memcached 中的对象
//$mc->delete($key);
//$val = $mc->get($key);
//echo "n" . str_pad('$mc->delete() ', 60, '_') . "n";
//var_dump($val);
?> 