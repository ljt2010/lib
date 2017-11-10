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


use libcn\lib\CLib;




/**
 *	class
 */
class CTestForGetVar extends PHPUnit_Framework_TestCase
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
	public function testForGetVar()
	{
		$arrTestData	=
			[
				[ '123', [ 'key' => '123' ], 'key', CLib::VARTYPE_STRING, '' ],
				[ '123', [ 'key' => '123' ], 'key', CLib::VARTYPE_NUMERIC, 0 ],
				[ '', [], 'key', CLib::VARTYPE_STRING, '' ],
				[ '', [ 'key1' => '123' ], 'key', CLib::VARTYPE_STRING, '' ],
				[ '', null, 'key', CLib::VARTYPE_STRING, '' ],
				[ '', [ 'key1' => '123' ], '', CLib::VARTYPE_STRING, '' ],
				[ '', [], '', CLib::VARTYPE_STRING, '' ],
				[ '', [], null, [], '' ],
				[ '', [], [[]], [], '' ],
				[ 7890, [ 'key' => '7890' ], 'key', CLib::VARTYPE_NUMERIC, 0 ],
			];
		//	...
		$nNumber = 1;

		foreach ( $arrTestData as $arrItem )
		{
			$vResult	= $arrItem[ 0 ];
			$vObj		= $arrItem[ 1 ];
			$sKey		= $arrItem[ 2 ];
			$nType		= $arrItem[ 3 ];
			$vDefaultVal	= $arrItem[ 4 ];

			$bSuccess	= ( $vResult == CLib::GetValEx( $vObj, $sKey, $nType, $vDefaultVal ) );
			$nErrorId	= ( $bSuccess ? 0 : -1 );

			//	...
			$sTitle		= sprintf
			(
				"GetValEx case NO.%d",
				$nNumber
			);
			$nNumber ++;

			$this->_OutputResult( __FUNCTION__, $sTitle, $nErrorId, $bSuccess );
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
