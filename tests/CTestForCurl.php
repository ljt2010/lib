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

require_once( dirname( __DIR__ ) . "/src/CRequest.php" );
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
	public function testForGetSomething()
	{
        print "\r";
		$nNumber	= 1;
		$arrTestData	=
			[
				[ false, '' ],
				[ false, [] ],
				[ false, [ [] ] ],
				[ false, [ [ '' ] ] ],
				[ false, [ 'key1' ] ],
                [ false,  '://www.baidu.com' ],
                [ false,  'http://www.baidussssss.com' ],
                [ true,  'www.baidu.com' ],
                [ true,  'https:/www.baidu.com' ],
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
			//	...
            $curl = lib\CRequest::GetInstance($arrItem[ 1 ])->requestSend();

			$bSuccess	= is_string($curl) === $arrItem[ 0 ];
			$sTitle		= sprintf
			(
				"testForGetSomething case NO.%d - ( %s ) '%s'",
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