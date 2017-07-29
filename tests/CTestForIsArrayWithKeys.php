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
 *	class of CTestForLib
 */
class CTestForIsArrayWithKeys extends PHPUnit_Framework_TestCase
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
	public function testForIsArrayWithKeys()
	{
		$nNumber	= 1;
		$arrTestData	=
			[
				[ false, null, null ],
				[ false, false, false ],
				[ false, false, [] ],
				[ false, false, '' ],
				[ false, [], null ],
				[ false, [], false ],
				[ false, [], [] ],
				[ false, [], '' ],
				[ true,  [ 'key1' ], null ],
				[ true,  [ 'key1' ], false ],
				[ true,  [ 'key1' ], [] ],
				[ true,  [ 'key1' ], '' ],
				[ false, [ 'key1' ], [ 'key2' ] ],
				[ true,  [ 'key1' => 1 ], [ 'key1' ] ],
				[ true,  [ 'key1' => 1 ], 'key1' ],
				[ false, [ 'key1' => 1, 'key2' => 1 ], 'key3' ],
				[ false, [ 'key1' => 1, 'key2' => 1 ], [ 'key3' ] ],
				[ true,  [ 'key1' => 1, 'key2' => 1 ], [ 'key1' ] ],
				[ false, [ 'key1' => 1, 'key2' => 1 ], [ 'key3', 'key2' ] ],
				[ true,  [ 'key1' => 1, 'key2' => 1 ], [ 'key1', 'key2' ] ],
				[ true,  [ 'key1' => 1 ], null ],
				[ true,  [ 'key1' => 1 ], false ],
				[ true,  [ 'key1' => 1 ], '' ],
				[ true,  [ 'key1' ], '' ],
				[ false, [], '' ],
				[ true,  [ [] ], '' ],
				[ true,  [ [] ], [] ],
				[ true,  [ [ [ [] ] ] ], [] ],
				[ false, [ [ [ [] ] ] ], [ [] ] ],
			];
		foreach ( $arrTestData as $arrItem )
		{
			$bGoal		= $arrItem[ 0 ];
			$arrTestData	= $arrItem[ 1 ];
			$arrKeys	= $arrItem[ 2 ];

			//	...
			$bSuccess	= ( $bGoal === xslib\CLib::IsArrayWithKeys( $arrTestData, $arrKeys ) );
			$sTitle		= sprintf( "IsArrayWithKeys case NO.%d", $nNumber );
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