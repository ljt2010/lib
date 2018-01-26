<?php
/**
 * Created by PhpStorm.
 * User: huizhi
 * Date: 2018/1/26
 * Time: 18:25
 */

namespace libcn\lib;


use function PHPSTORM_META\type;

class Input
{
    static public $get = [];
    static public $post = [];
    static public $file = [];
    static public $cookie = [];

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->file = $_FILES;
        $this->cookie = $_COOKIE;
    }

    public static function get( $sKeyName, $defaultValue = null, $aCheck = [], &$checkResult = "" )
    {
        $value = null;
        $bUseDefault = false;
        if( is_string( $sKeyName )){
            if( array_key_exists( $sKeyName,self::$get ) ){
                $value = self::$get[$sKeyName];
            }elseif( array_key_exists( $sKeyName,self::$post ) ){
                $value = self::$post[$sKeyName];
            } else {
                $value = $defaultValue;
                $bUseDefault = true;
            }
        }

        if( !$bUseDefault || 0 < $aCheck ){
            $checkResult = self::typeCheck( $value, $aCheck );
        }

        return $value;
    }


    public static function typeCheck( $value, $checkParameter )
    {
        if( self::getType( $value ) ){

        }
    }


    public static function getType( $value )
    {
        if( is_float( $value ) ){
            return "float";
        }
        if( is_bool( $value ) ){
            return "bool";
        }
        if( is_int( $value ) ){
            return "int";
        }
        if( is_string( $value ) ){
            return $value;
        }
        if( is_array( $value ) ){
            return 'array';
        }
        if( is_resource( $value ) ){
            return 'resource';
        }
        if( is_object( $value ) ){
            return 'object';
        }
        return "unknown type";
    }
}