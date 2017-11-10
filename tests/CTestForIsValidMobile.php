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


use libcn\lib;




/**
 *	class
 */
class CTestForIsValidMobile extends PHPUnit_Framework_TestCase
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
	public function testForIsValidMobile()
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
				[ false, 'liu-x@xs.cn' ],
				[ false, 'liu-_sdfdfdfdfx@xs.cn' ],
				[ false, 'liu-x.sdfdfdfdf.sdfdf_-@xs.cn' ],
				[ false, 'liu-x.sdfdfdfdf@@xs.cn' ],
				[ false, '1381055-569' ],

				[ false, '130105505' ],
				[ false, '1311055059' ],

				[ true,  '13010550569' ],
				[ true,  '13110550569' ],
				[ true,  '13210550569' ],
				[ true,  '13310550569' ],
				[ true,  '13410550569' ],
				[ true,  '13510550569' ],
				[ true,  '13610550569' ],
				[ true,  '13710550569' ],
				[ true,  '13810550569' ],
				[ true,  '13910550569' ],

				//	13|15|18|17
				[ true,  '13910550569' ],
				[ true,  '15910550569' ],
				[ true,  '18910550569' ],
				[ true,  '17910550569' ],
				[ true,  '17010550569' ],

				[ false, '17010550569 ' ],
				[ true,  '17010550569 ', true ],
				[ true,  ' 17010550569 ', true ],
			];

		foreach ( $arrTestData as $arrItem )
		{
			$bGoal		= $arrItem[ 0 ];
			$vMobile	= $arrItem[ 1 ];
			$bTrim		= array_key_exists( 2, $arrItem ) ? $arrItem[ 2 ] : false;

			//	...
			$bSuccess	= ( $bGoal === libcn\lib\CLib::IsValidMobile( $vMobile, $bTrim ) );
			$sTitle		= sprintf
			(
				"IsValidMobile case NO.%d - ( %s ) '%s'",
				$nNumber,
				$bGoal ? "true " : "false",
				is_string( $vMobile ) ? $vMobile : 'NULL'
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