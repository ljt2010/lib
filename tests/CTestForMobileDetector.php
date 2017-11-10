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
require_once( dirname( __DIR__ ) . "/src/CMobileDetector.php" );


use libcn\lib\CMobileDetector;




/**
 *	class
 */
class CTestForMobileDetector extends PHPUnit_Framework_TestCase
{
	/**
	 * @runInSeparateProcess
	 */
	public function testHeader()
	{
		print( "\r\n" . __CLASS__ . "::" . __FUNCTION__ . "\r\n" );
		print( "--------------------------------------------------------------------------------\r\n" );

		return true;
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testForMobileDetector()
	{
		$_SERVER	= [];
		$_SERVER[ 'HTTP_USER_AGENT' ]	= 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.23 Mobile Safari/537.36';
		$_SERVER[ 'HTTP_USER_AGENT' ]	= 'Mozilla/5.0 (iPad; CPU OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';

		$cMd	= CMobileDetector::GetInstance();

		//	...
		$this->_OutputResult( __FUNCTION__, 'CLib::IsMobileDevice', \libcn\lib\CLib::IsMobileDevice() ? 0 : -1, true );
		$this->_OutputResult( __FUNCTION__, 'isMobile', $cMd->isMobile() ? 0 : -1, true );
		$this->_OutputResult( __FUNCTION__, 'isTablet', $cMd->isTablet() ? 0 : -1, true );
	}

	protected function _OutputResult( $sFuncName, $sCallMethod, $nErrorId, $bAssert )
	{
		printf( "\r\n# %s->%s\r\n", $sFuncName, $sCallMethod );
		printf( "# ErrorId : %6d, result : [%s]", $nErrorId, ( $bAssert ? "OK" : "ERROR" ) );
		printf( "\r\n" );

		//$this->assertTrue( $bAssert );
	}
}
