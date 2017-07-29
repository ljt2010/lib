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
require_once( dirname( __DIR__ ) . "/src/CEnv.php" );


use libcn\lib\CEnv;



class CTestForEnv extends PHPUnit_Framework_TestCase
{
	public function testEnv()
	{
		$arrTestData	=
			[
				[ true,		CEnv::ENV_PRE_PRODUCTION,	'pay-pre.xs.cn' ],
				[ true,		CEnv::ENV_DEVELOPMENT,		'pay-dev.xs.cn' ],
				[ true,		CEnv::ENV_LOCAL,		'pay-loc.xs.cn' ],
				[ true,		CEnv::ENV_TEST,			'pay-test.xs.cn' ],
				[ true,		CEnv::ENV_PRODUCTION,		'paypre.xs.cn' ],
				[ true,		CEnv::ENV_PRODUCTION,		'pay.xs.cn' ],
				[ true,		CEnv::ENV_PRODUCTION,		'xs.cn' ],
				[ true,		CEnv::ENV_PRODUCTION,		'www.xs.cn' ],
				[ true,		CEnv::ENV_PRODUCTION,		'WWW.XS.CN' ],
				[ true,		CEnv::ENV_PRODUCTION,		'update.service.xs.cn' ],
				[ false,	CEnv::ENV_PRODUCTION,		'update.service-loc.xs.cn' ],
				[ true,		CEnv::ENV_UNKNOWN,		'' ],
				[ true,		CEnv::ENV_UNKNOWN,		[] ],
				[ true,		CEnv::ENV_UNKNOWN,		null ],
			];
		if ( ! is_array( $_SERVER ) )
		{
			$_SERVER = [];
		}

		foreach ( $arrTestData as $arrItem )
		{
			$bGoal	= $arrItem[ 0 ];
			$nEnvT	= $arrItem[ 1 ];
			$vVal	= $arrItem[ 2 ];

			//	...
			$_SERVER[ 'SERVER_NAME' ]	= $vVal;

			$nGetEnvT	= CEnv::GetEnvType();
			$bResult	= ( $nEnvT == $nGetEnvT );
			$bSuccess	= ( $bGoal == $bResult );

			$sTitle		= sprintf
			(
				"CEnv::GetEnvType\tresult(%d) - goal(%d)\tval(%s)",
				$nGetEnvT,
				$nEnvT,
				( is_string( $vVal ) || is_numeric( $vVal ) ) ? $vVal : "NULL"
			);

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