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

require_once( dirname( __DIR__ ) . "/src/CLib.php" );
require_once( dirname( __DIR__ ) . "/vendor/autoload.php" );


use libcn\lib;
use PHPUnit\Framework\TestCase;

/**
 *	class
 */
class CTestForIsValidUrl extends TestCase
{
	/**
	 * @runInSeparateProcess
	 */
	public function testHeader()
	{
	    print "\r";
        print( "*********************************************************************************\r\n" );
		print( "*****************************  " . __CLASS__ . "  ******************************\r\n" );
		print( "*********************************************************************************\r\n" );
		$this->assertTrue(true);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testForIsValidUrl()
	{
        print "\r";
		$nNumber	= 1;
		$arrTestData	=
			[
				[ false, null ],
				[ false, false ],
				[ false, [] ],
				[ false, [ [] ] ],
				[ false, [ [ '' ] ] ],
				[ false, [ 'key1' ] ],
                [ false,  'www.baidu.com' ],
                [ false,  '://www.baidu.com' ],
                [ false,  'https:/www.baidu.com' ],
                [ false,  'http://www.baidussssss.com',true ],
                [ true,  'ftp://www.baidu.com' ],
                [ true,  'http://www.baidu.com' ],
                [ true,  'https://www.baidu.com' ],
                [ true,  'https://www.baidu.com/test' ],
                [ true,  'https://www.baidu.com/test.html' ],
                [ true,  'https://www.baidu.com/test?1=2' ],
			];

		foreach ( $arrTestData as $arrItem )
		{
			$bGoal		= $arrItem[ 0 ];
			$vUrl		= $arrItem[ 1 ];
			$bCheckDNS	= array_key_exists( 2, $arrItem ) ? $arrItem[ 2 ] : false;
			//	...
			$bSuccess	= ( $bGoal === libcn\lib\CLib::IsValidUrl( $vUrl, $bCheckDNS ) );
			$sTitle		= sprintf
			(
				"IsValidEMail case NO.%d - ( %s ) '%s'",
				$nNumber,
				$bGoal ? "true " : "false",
				is_string( $vUrl ) ? $vUrl : 'NULL'
			);
			$nNumber ++;

			$this->_OutputResult( __FUNCTION__, $sTitle, null, $bSuccess );
		}
	}

	protected function _OutputResult( $sFuncName, $sCallMethod, $nErrorId, $bAssert )
	{
		printf( "\r\n# %s->%s\r\n", $sFuncName, $sCallMethod );
		printf( "# ErrorId : %6d, result : [%s]", $nErrorId, ( $bAssert ? "OK" : "ERROR" ) );
		printf( "\r\n" );

		$this->assertTrue( $bAssert );
	}
}




?>