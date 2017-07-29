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
class CTestForGetClientIP extends PHPUnit_Framework_TestCase
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
	public function testForGetClientIP()
	{

		//
		//	http://php.net/manual/en/filter.filters.flags.php
		//
		//	FILTER_FLAG_NO_PRIV_RANGE
		//		Fails validation for the following private IPv4 ranges: 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16.
		//		Fails validation for the IPv6 addresses starting with FD or FC.
		//
		//	FILTER_FLAG_NO_RES_RANGE
		//		Fails validation for the following reserved IPv4 ranges:
		//		0.0.0.0/8, 169.254.0.0/16, 192.0.2.0/24 and 224.0.0.0/4.
		//		This flag does not apply to IPv6 addresses.
		//
		$arrDDD =
			[
				0	=> 1,
				'key1'	=> 1,
			];
		$arrTestData	=
			[
				[ 'HTTP_X_FORWARDED_FOR', false, null ],
				[ 'HTTP_X_FORWARDED_FOR', false, false ],
				[ 'HTTP_X_FORWARDED_FOR', false, [] ],
				[ 'HTTP_X_FORWARDED_FOR', false, [ [] ] ],
				[ 'HTTP_X_FORWARDED_FOR', false, [ [ '' ] ] ],
				[ 'HTTP_X_FORWARDED_FOR', false, [ 'key1' ] ],
				[ 'HTTP_X_FORWARDED_FOR', false, '127.0.0.1' ],

				//	FILTER_FLAG_NO_RES_RANGE
				[ 'HTTP_X_FORWARDED_FOR', false, '0.0.0.0' ],
				[ 'HTTP_X_FORWARDED_FOR', false, '169.254.0.0' ],
				[ 'HTTP_X_FORWARDED_FOR', false, '192.0.2.0' ],
				[ 'HTTP_X_FORWARDED_FOR', false, '224.0.0.0' ],

				[ 'HTTP_X_FORWARDED_FOR', false, '256.168.0.1' ],

				[ 'HTTP_X_FORWARDED_FOR', false,  '2001:0DB8:0000:0000:0000:0000:1428:0000' ],
				[ 'HTTP_X_FORWARDED_FOR', false,  '2001:0DB8:0000:0000:0000::1428:0000' ],
				[ 'HTTP_X_FORWARDED_FOR', false,  '2001:0DB8:0:0:0:0:1428:0000' ],
				[ 'HTTP_X_FORWARDED_FOR', false,  '2001:0DB8:0::0:0:1428:0000' ],
				[ 'HTTP_X_FORWARDED_FOR', false,  '2001:0DB8::1428:0000' ],

				//	FILTER_FLAG_NO_PRIV_RANGE
				[ 'HTTP_X_FORWARDED_FOR', false, '10.0.0.0' ],
				[ 'HTTP_X_FORWARDED_FOR', false, '172.16.0.0' ],
				[ 'HTTP_X_FORWARDED_FOR', false, '192.168.0.0' ],
				[ 'HTTP_X_FORWARDED_FOR', false, '192.168.0.1' ],
				[ 'HTTP_X_FORWARDED_FOR', false, '192.168.0.199' ],

				[ 'HTTP_X_FORWARDED_FOR', false, 'FD01:DB8:2de::e13' ],
				[ 'HTTP_X_FORWARDED_FOR', false, 'FC01:DB8:2de::e13' ],

				//	...
				[ 'HTTP_X_FORWARDED_FOR', false,  '2001:0DB8:02de::0e13' ],
				[ 'HTTP_X_FORWARDED_FOR', true,   '2001:DB8:2de::e13' ],

				[ 'HTTP_X_FORWARDED_FOR', false, '2001:0DB8::1428::' ],


				//
				//	check for internal addresses
				//
				[ 'HTTP_X_FORWARDED_FOR', true,  '127.0.0.1', false ],

				//	FILTER_FLAG_NO_RES_RANGE
				[ 'HTTP_X_FORWARDED_FOR', true,  '0.0.0.0', false ],
				[ 'HTTP_X_FORWARDED_FOR', true,  '169.254.0.0', false ],
				[ 'HTTP_X_FORWARDED_FOR', true,  '192.0.2.0', false ],
				[ 'HTTP_X_FORWARDED_FOR', true,  '224.0.0.0', false ],

				//	FILTER_FLAG_NO_PRIV_RANGE
				[ 'HTTP_X_FORWARDED_FOR', true,  '10.0.0.0', false ],
				[ 'HTTP_X_FORWARDED_FOR', true,  '172.16.0.0', false ],
				[ 'HTTP_X_FORWARDED_FOR', true,  '192.168.0.0', false ],
				[ 'HTTP_X_FORWARDED_FOR', true,  '192.168.0.1', false ],
				[ 'HTTP_X_FORWARDED_FOR', true,  '192.168.0.199', false ],



				////////////////////////////////////////////////////////////
				////////////////////////////////////////////////////////////
				////////////////////////////////////////////////////////////



				[ 'REMOTE_ADDR', false, null ],
				[ 'REMOTE_ADDR', false, false ],
				[ 'REMOTE_ADDR', false, [] ],
				[ 'REMOTE_ADDR', false, [ [] ] ],
				[ 'REMOTE_ADDR', false, [ [ '' ] ] ],
				[ 'REMOTE_ADDR', false, [ 'key1' ] ],
				[ 'REMOTE_ADDR', false, '127.0.0.1' ],

				//	FILTER_FLAG_NO_RES_RANGE
				[ 'REMOTE_ADDR', false, '0.0.0.0' ],
				[ 'REMOTE_ADDR', false, '169.254.0.0' ],
				[ 'REMOTE_ADDR', false, '192.0.2.0' ],
				[ 'REMOTE_ADDR', false, '224.0.0.0' ],

				[ 'REMOTE_ADDR', false, '256.168.0.1' ],

				[ 'REMOTE_ADDR', false,  '2001:0DB8:0000:0000:0000:0000:1428:0000' ],
				[ 'REMOTE_ADDR', false,  '2001:0DB8:0000:0000:0000::1428:0000' ],
				[ 'REMOTE_ADDR', false,  '2001:0DB8:0:0:0:0:1428:0000' ],
				[ 'REMOTE_ADDR', false,  '2001:0DB8:0::0:0:1428:0000' ],
				[ 'REMOTE_ADDR', false,  '2001:0DB8::1428:0000' ],

				//	FILTER_FLAG_NO_PRIV_RANGE
				[ 'REMOTE_ADDR', false, '10.0.0.0' ],
				[ 'REMOTE_ADDR', false, '172.16.0.0' ],
				[ 'REMOTE_ADDR', false, '192.168.0.0' ],
				[ 'REMOTE_ADDR', false, '192.168.0.1' ],
				[ 'REMOTE_ADDR', false, '192.168.0.199' ],

				[ 'REMOTE_ADDR', false, 'FD01:DB8:2de::e13' ],
				[ 'REMOTE_ADDR', false, 'FC01:DB8:2de::e13' ],

				//	...
				[ 'REMOTE_ADDR', false,  '2001:0DB8:02de::0e13' ],
				[ 'REMOTE_ADDR', true,   '2001:DB8:2de::e13' ],

				[ 'REMOTE_ADDR', false, '2001:0DB8::1428::' ],


				//
				//	check for internal addresses
				//
				[ 'REMOTE_ADDR', true,  '127.0.0.1', false ],

				//	FILTER_FLAG_NO_RES_RANGE
				[ 'REMOTE_ADDR', true,  '0.0.0.0', false ],
				[ 'REMOTE_ADDR', true,  '169.254.0.0', false ],
				[ 'REMOTE_ADDR', true,  '192.0.2.0', false ],
				[ 'REMOTE_ADDR', true,  '224.0.0.0', false ],

				//	FILTER_FLAG_NO_PRIV_RANGE
				[ 'REMOTE_ADDR', true,  '10.0.0.0', false ],
				[ 'REMOTE_ADDR', true,  '172.16.0.0', false ],
				[ 'REMOTE_ADDR', true,  '192.168.0.0', false ],
				[ 'REMOTE_ADDR', true,  '192.168.0.1', false ],
				[ 'REMOTE_ADDR', true,  '192.168.0.199', false ],



				//
				//	does not play with proxy
				//
				[ 'HTTP_X_FORWARDED_FOR', false, '192.168.0.199', false, false ],
				[ 'REMOTE_ADDR', true,  '192.168.0.199', false, false ],
			];

		if ( ! is_array( $_SERVER ) )
		{
			$_SERVER = [];
		}

		//	...
		$nNumber = 1;

		foreach ( $arrTestData as $arrItem )
		{
			$sServerKey	= $arrItem[ 0 ];
			$bGoal		= $arrItem[ 1 ];
			$vIp		= $arrItem[ 2 ];
			$bMustBePublic	= array_key_exists( 3, $arrItem ) ? $arrItem[ 3 ] : true;
			$bPlayWithProxy	= array_key_exists( 4, $arrItem ) ? $arrItem[ 4 ] : true;

			if ( xslib\CLib::IsSameString( 'HTTP_X_FORWARDED_FOR', $sServerKey ) )
			{
				$_SERVER = [ $sServerKey => sprintf( "%s, 110.110.110.110", ( is_string( $vIp ) ? $vIp : '' ) ) ];
			}
			else if ( xslib\CLib::IsSameString( 'REMOTE_ADDR', $sServerKey ) )
			{
				$_SERVER = [ $sServerKey => ( is_string( $vIp ) ? $vIp : '' ) ];
			}

			//	...
			$sGotIp		= xslib\CLib::GetClientIP( $bMustBePublic, $bPlayWithProxy );
			$bSuccess	= ( $bGoal === xslib\CLib::IsValidIP( $sGotIp, $bMustBePublic ) );
			$sValue		= xslib\CLib::IsArrayWithKeys( $_SERVER, $sServerKey ) ? $_SERVER[ $sServerKey ] : '';
			$sTitle		= sprintf
			(
				"GetClientIP( %s, %s ) case NO.%d\r\n  - key:\t%s\r\n  - input:\t%s\r\n  - goal:\t%s\r\n  - return:\t%s",
				$bMustBePublic ? "true " : "false",
				$bPlayWithProxy ? "true " : "false",
				$nNumber,

				$sServerKey,
				xslib\CLib::IsExistingString( $sValue ) ? $sValue : 'NULL',
				$bGoal ? "true " : "false",
				( xslib\CLib::IsExistingString( $sGotIp ) ? $sGotIp : "<Empty>" )
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