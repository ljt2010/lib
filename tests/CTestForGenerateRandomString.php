<?php

@ ini_set( 'date.timezone', 'Etc/GMT＋0' );
@ date_default_timezone_set( 'Etc/GMT＋0' );

@ ini_set( 'display_errors',	'on' );
@ ini_set( 'max_execution_time',	'60' );
@ ini_set( 'max_input_time',	'0' );
@ ini_set( 'memory_limit',	'512M' );

//	mb 环境定义
mb_internal_encoding( "UTF-8" );

//	Turn on output buffering
ob_start();


require_once( dirname( __DIR__ ) . "/vendor/autoload.php" );
require_once( dirname( __DIR__ ) . "/src/CLib.php" );


use xscn\xslib\CLib;



class CTestForEnv extends PHPUnit_Framework_TestCase
{
	public function testEnv()
	{
		$arrMap	= [];
		for ( $i = 0; $i < 100000; $i ++ )
		{
			//	...
			$sRandom = CLib::GenerateRandomString( 20 );

			if ( empty( $sRandom ) ||
				array_key_exists( $sRandom, $arrMap ) )
			{
				$this->assertTrue( false );
				exit();
			}

			$arrMap[ $sRandom ]	= 1;

			echo sprintf( "%05d - %s\t[OK]\r\n", $i, strval( $sRandom ) );
		}

	}

	public function testEnvNumeric()
	{
		$arrMap	= [];
		for ( $i = 0; $i < 100000; $i ++ )
		{
			//	...
			$sRandom = CLib::GenerateRandomString( 20, true );

			if ( empty( $sRandom ) ||
				array_key_exists( $sRandom, $arrMap ) )
			{
				$this->assertTrue( false );
				exit();
			}

			$arrMap[ $sRandom ]	= 1;

			echo sprintf( "%05d - %s\t[OK]\r\n", $i, strval( $sRandom ) );
		}

	}
}