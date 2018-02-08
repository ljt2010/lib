<?php
/**
 * Created by PhpStorm.
 * User: huizhi
 * Date: 2018/1/26
 * Time: 18:25
 */

namespace libcn\lib;



class Input
{
    static public $get = [];
    static public $post = [];
    static public $file = [];
    static public $cookie = [];
    static public $server = [];

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->file = $_FILES;
        $this->cookie = $_COOKIE;
        $this->server = $_SERVER;
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
        } elseif (is_null($sKeyName)){
            return array_merge( self::$get, self::$post );
        }

        if( !$bUseDefault || 0 < $aCheck ){
            $checkResult = self::typeCheck( $value, $aCheck );
        }

        return $value;
    }


    public static function typeCheck( $value, $checkParameter )
    {
        $bType = false;
        $bLength = false;
        if( array_key_exists( 'type',$checkParameter ) && !$checkParameter['type'] == self::getType( $value ) ){
            $bType = false;
        }

        if( array_key_exists( 'len',$checkParameter ) ){
            $checkParameter['len'] = str_replace(['|','-','~'],':',$checkParameter['len']);
            list(  $minLength, $maxLength ) = explode(':', $checkParameter['len']);
            if( is_string( $value ) && $minLength<=strlen( $value ) && $maxLength <= strlen( $value )){
                $bLength = true;
            }else if ( is_array($value) && $minLength>count( $value )|| $maxLength> count( $value ) ){
                $bLength = true;
            }
        }else{
            $bLength = true;
        }



        return $bType && $bLength;
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
        return false;
    }
}