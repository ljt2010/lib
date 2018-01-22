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
class CTestForLibFunction extends PHPUnit_Framework_TestCase
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
	public function testForFunction(  )
	{



        $arrTestData	=
			[
			    //[true,'libcn\lib\Clib::getRand',[5,'letter']],
			    //[true,'libcn\lib\Clib::getRand',[4,'numeric']],
			    //[true,'libcn\lib\Clib::getRand',[13,'mix']],
			    [true,'libcn\lib\Clib::GetRand',[13,'mix']]
			];
		//	...
		$nNumber = 1;

		foreach ( $arrTestData as $arrItem )
		{
			$function		= $arrItem[ 1 ];
            $re = call_user_func_array( $arrItem[ 1 ],$arrItem[ 2 ] );
            $bSuccess	= is_string( $re ) &&  $arrItem[2][0] == strlen( $re );
			$nErrorId	= ( $bSuccess ? 0 : -1 );
			//	...
			$sTitle		= sprintf( "$function case NO.%d", $nNumber );
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
