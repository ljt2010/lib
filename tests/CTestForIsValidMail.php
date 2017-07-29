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
class CTestForIsValidMail extends PHPUnit_Framework_TestCase
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
	public function testForIsValidMail()
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
				[ true,  'liu-x@xs.cn' ],
				[ true,  'liu-_sdfdfdfdfx@xs.cn' ],
				[ true,  'liu-x.sdfdfdfdf.sdfdf_-@xs.cn' ],
				[ false, 'liu-x.sdfdfdfdf@@xs.cn' ],

				[ true,  'liuqixingliuqixingliuqixingliuqixingliuqixingliuqixingliuqixin@xs.cn' ],	//	local-part is 64 characters length
				[ true,  'liuqixingliuqixingliuqixingliuqixingliuqixingliuqixingliuqixinx@xs.cn' ],	//	local-part is 63 characters length
				[ true,  'liuqixingliuqixingliuqixingliuqixingliuqixingliuqixingliuqixinx1@xs.cn' ],	//	local-part is 64 characters length
				[ false, 'liuqixingliuqixingliuqixingliuqixingliuqixingliuqixingliuqixinx12@xs.cn' ],	//	local-part is 65 characters length
				[ false, 'liuqixingliuqixingliuqixingliuqixingliuqixingliuqixingliuqixingliuqixingliuqixingliuqixing@xs.cn' ],
				[ true,  'asdfasdfasdfasdfsdfdf@liuqixingliuqixingliuqixingliuqixingliuqdfdfdfdfsfsdfdsfsdixing.com.cn' ],	//	domain is 64 characters length
				[ false, 'asdfasdfasdfasdfsdfdf@liuqixingliuqixingliuqixingliuqixingliuqdfdfdfdfsfsdfdsfsdixings.com.cn' ],	//	domain is 65 characters length
				[ true,  'x@xs.cn' ],
				[ false, 'x@xssdfa;slkdfjas-x#dlfkjasdfasflaskdjf.cn' ],
				[ false, 'x@asdfasdf).cn' ],
				[ true,  'x-asdf1-9_121@sasdf.com.cn' ],
				[ true,  'x-asdf1-9_121@sasdf.com.ct' ],

				[ false,  'x-asdf1-9_121@sasdf.com.ct ' ],
				[ false,  ' x-asdf1-9_121@sasdf.com.ct ' ],
				[ true,  ' x-asdf1-9_121@sasdf.com.ct ', false, true ],

				[ true,  'liuqixing@readnovel.com', true, true ],
			];

		foreach ( $arrTestData as $arrItem )
		{
			$bGoal		= $arrItem[ 0 ];
			$vEMail		= $arrItem[ 1 ];
			$bCheckDNS	= array_key_exists( 2, $arrItem ) ? $arrItem[ 2 ] : false;
			$bTrim		= array_key_exists( 3, $arrItem ) ? $arrItem[ 3 ] : false;

			//	...
			$bSuccess	= ( $bGoal === xslib\CLib::IsValidEMail( $vEMail, $bCheckDNS, $bTrim ) );
			$sTitle		= sprintf
			(
				"IsValidEMail case NO.%d - ( %s ) '%s'",
				$nNumber,
				$bGoal ? "true " : "false",
				is_string( $vEMail ) ? $vEMail : 'NULL'
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