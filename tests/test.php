<?php
/**
 * Created by PhpStorm.
 * User: huizhi
 * Date: 2018/1/22
 * Time: 12:16
 */

require_once( dirname( __DIR__ ) . "/src/CLib.php" );


$re  = call_user_func_array("libcn\lib\Clib::getRand",[5,'numeric']);

var_dump($re);