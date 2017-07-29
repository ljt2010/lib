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


use xscn\xslib;




/**
 *	class
 */
class CTestForIsValidIp extends PHPUnit_Framework_TestCase
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
	public function testForIsValidIp()
	{
		$nNumber	= 1;
		$arrTestData	=
			[
				[ false, null ],
				[ false, false ],
				[ false, [] ],
				[ false, [ [] ] ],
				[ false, [ [ '' ] ] ],
				[ false, [ 'key1' ] ],
				[ false, '127.0.0.1' ],

				//	FILTER_FLAG_NO_RES_RANGE
				[ false, '0.0.0.0' ],
				[ false, '169.254.0.0' ],
				[ false, '192.0.2.0' ],
				[ false, '224.0.0.0' ],

				[ false, '256.168.0.1' ],

				[ false,  '2001:0DB8:0000:0000:0000:0000:1428:0000' ],
				[ false,  '2001:0DB8:0000:0000:0000::1428:0000' ],
				[ false,  '2001:0DB8:0:0:0:0:1428:0000' ],
				[ false,  '2001:0DB8:0::0:0:1428:0000' ],
				[ false,  '2001:0DB8::1428:0000' ],

				//	FILTER_FLAG_NO_PRIV_RANGE
				[ false, '10.0.0.0' ],
				[ false, '172.16.0.0' ],
				[ false, '192.168.0.0' ],
				[ false, '192.168.0.1' ],
				[ false, '192.168.0.199' ],

				[ false, 'FD01:DB8:2de::e13' ],
				[ false, 'FC01:DB8:2de::e13' ],

				//	...
				[ false,  '2001:0DB8:02de::0e13' ],
				[ true,   '2001:DB8:2de::e13' ],

				[ false, '2001:0DB8::1428::' ],


				//
				//	check for internal addresses
				//
				[ true,  '127.0.0.1', false ],

				//	FILTER_FLAG_NO_RES_RANGE
				[ true,  '0.0.0.0', false ],
				[ true,  '169.254.0.0', false ],
				[ true,  '192.0.2.0', false ],
				[ true,  '224.0.0.0', false ],

				//	FILTER_FLAG_NO_PRIV_RANGE
				[ true,  '10.0.0.0', false ],
				[ true,  '172.16.0.0', false ],
				[ true,  '192.168.0.0', false ],
				[ true,  '192.168.0.1', false ],
				[ true,  '192.168.0.199', false ],
			];

		foreach ( $arrTestData as $arrItem )
		{
			$bGoal		= $arrItem[ 0 ];
			$vIp		= $arrItem[ 1 ];
			$bMustBePublic	= array_key_exists( 2, $arrItem ) ? $arrItem[ 2 ] : true;

			//	...
			$bSuccess	= ( $bGoal === xslib\CLib::IsValidIP( $vIp, $bMustBePublic ) );
			$sTitle		= sprintf
			(
				"IsValidIp case NO.%d - ( %s ) %s",
				$nNumber,
				$bGoal ? "true " : "false",
				is_string( $vIp ) ? $vIp : 'NULL'
			);
			$nNumber ++;

			$this->_OutputResult( __FUNCTION__, $sTitle, -1, $bSuccess );
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