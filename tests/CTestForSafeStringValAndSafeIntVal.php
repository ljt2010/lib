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




/**
 *	class
 */
class CTestForSafeStringValAndSafeIntVal extends PHPUnit_Framework_TestCase
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
	public function testForSafeStringVal()
	{
		$arrTestData	=
			[
				[ true,	'',	'',		'' ],
				[ true,	'str',	'str',		'' ],
				[ true,	'123',	'123',		'' ],
				[ true,	123456,	'123456',	'' ],
				[ true,	[],	'',		'' ],
				[ true,	null,	'',		'' ],
			];
		//	...
		$nNumber = 1;

		foreach ( $arrTestData as $arrItem )
		{
			$bGoal		= $arrItem[ 0 ];
			$vInput		= $arrItem[ 1 ];
			$sOutput	= $arrItem[ 2 ];
			$sDefaultVal	= $arrItem[ 3 ];

			$bSuccess	= $bGoal == ( $sOutput === CLib::SafeStringVal( $vInput, $sDefaultVal ) );
			$nErrorId	= ( $bSuccess ? 0 : -1 );

			//	...
			$sTitle		= sprintf( "SafeStringVal case NO.%d", $nNumber );
			$nNumber ++;

			$this->_OutputResult( __FUNCTION__, $sTitle, $nErrorId, $bSuccess );
		}
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testForSafeIntVal()
	{
		$arrTestData	=
			[
				[ true,	'',	0,		0 ],
				[ true,	4.2,	4,		0 ],
				[ true,	'42',	42,		0 ],
				[ true,	'4.2',	4,		0 ],
				[ true,	'+42',	42,		0 ],
				[ true,	'-42',	-42,		0 ],
				[ true,	042,	34,		0 ],
				[ true,	'042',	42,		0 ],
				[ true,	'1e10',	1,		0 ],
				[ true,	0x1A,	26,		0 ],
				[ true,	42000000,			42000000,	0 ],
				//[ true,	420000000000000000000,		0,		0 ],
				[ true,	'420000000000000000000',	9223372036854775807,	0 ],
				[ true,	'str',	0,		0 ],
				[ true,	'123',	123,		0 ],
				[ true,	's123',	0,		0 ],
				[ true,	'123s',	123,		0 ],
				[ true,	123456,	123456,		0 ],
				[ true,	[],	0,		0 ],
				[ true,	['foo'],	1,		0 ],
				[ true,	null,	0,		0 ],
			];

		//	...
		$nNumber = 1;

		foreach ( $arrTestData as $arrItem )
		{
			$bGoal		= $arrItem[ 0 ];
			$vInput		= $arrItem[ 1 ];
			$sOutput	= $arrItem[ 2 ];
			$sDefaultVal	= $arrItem[ 3 ];

			var_dump( intval( $vInput ) );
			$vResultVal	= CLib::SafeIntVal( $vInput, $sDefaultVal );
			$bSuccess	= $bGoal == ( $sOutput === $vResultVal );
			$nErrorId	= ( $bSuccess ? 0 : -1 );

			//	...
			$sTitle		= sprintf
			(
				"SafeIntVal case NO.%d ( %s | %s )",
				$nNumber,
				( is_string( $vInput ) || is_numeric( $vInput ) ) ? $vInput : 'NULL',
				$vResultVal
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
