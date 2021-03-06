<?php

namespace libcn\lib;

/**
 *      CConst
 */
class CConst
{
        //
        //      common status
        //
	const STATUS_UNKNOWN		= -1;	//	unknown
	const STATUS_UNVERIFIED         = 0;	//	unverified
	const STATUS_OKAY               = 1;	//	okay
	const STATUS_DELETED            = 2;	//	deleted
	const STATUS_EXPIRED            = 3;	//	expired
	const STATUS_DENIED             = 4;	//	denied
	const STATUS_COMPLETE           = 5;	//	complete
	const STATUS_ABORT              = 6;	//	abort
	const STATUS_PENDING            = 7;	//	pending
	const STATUS_ACCEPTED           = 8;	//	accepted
	const STATUS_REJECTED           = 9;	//	rejected
	const STATUS_ARCHIVED		= 10;	//	archived
	const STATUS_LOCKED		= 11;	//	locked
	const STATUS_UNLOCKED		= 12;	//	unlocked


	//
	//      Source/Client
	//
	const SOURCE_UNKNOWN		= 0;	//	user from product unknown
	const SOURCE_ANDROID		= 1;	//	user from product Android
	const SOURCE_IOS		= 2;	//	user from product IOS
	const SOURCE_WAP		= 3;	//	user from product WAP
	const SOURCE_PC			= 4;	//	user from product PC
	const SOURCE_DATAIMPORTER	= 5;	//	user from product data importer / data.xs.cn
	const SOURCE_NIRVANA		= 20;	//	user from product nirvana
	const SOURCE_MGR_SYSTEM		= 30;	//	user from product management system / manage.xs.cn


	//
	//	environment type
	//
	const ENVTYPE_UNKNOWN		= -1;	//	unknown
	const ENVTYPE_PRODUCTION	= 0;	//	production environment
	const ENVTYPE_PRE_PRODUCTION	= 1;	//	pre-production environment
	const ENVTYPE_DEVELOPMENT	= 2;	//	development environment
	const ENVTYPE_LOCAL		= 3;	//	local environment


	//
	//	common error codes
	//
	const ERROR_SUCCESS			= 0;            //      successfully

	const ERROR_USER_START			= 10000;	//	start of user customized error id
	const ERROR_USER_END			= 99999;	//	end of user customized error id

	const ERROR_UNKNOWN			= -100000;      //      unknown errors
	const ERROR_ACCESS_DENIED		= -100001;      //      access denied
	const ERROR_PARAMETER			= -100002;      //      error in parameters
	const ERROR_PERMISSION			= -100003;      //      error in permission
	const ERROR_EXPIRED			= -100004;      //      error in expired
	const ERROR_NOT_LOGGEDIN		= -100005;      //      error in not logged in
	const ERROR_FAILED_LOGGEDIN		= -100006;      //      error in failed logged in

	const ERROR_CREATE_INSTANCE		= -100010;      //      error in creating instance
	const ERROR_EXCEPTION			= -100011;	//	error in exception

	const ERROR_DB_SELECT			= -100050;	//	error in selecting database
	const ERROR_DB_UPDATE			= -100051;	//	error in updating database
	const ERROR_DB_INSERT			= -100052;	//	error in inserting database
	const ERROR_DB_DELETE			= -100053;	//	error in deleting database
	const ERROR_DB_DROP			= -100054;	//	error in dropping database
	const ERROR_DB_TRANSACTION		= -100060;	//	error in transaction
	const ERROR_DB_TABLE_NAME		= -100065;	//	error in table name

	const ERROR_REQUEST_VIA_IP		= -100100;	//	bad request via ip request

	const ERROR_MO_NOT_ENOUGH_COINS		= -100200;	//	not enough coins
	const ERROR_MO_HIRE_OVERDUE		= -100201;	//	the hiring date is overdue
	const ERROR_MO_TRANSACTION_TYPE		= -100202;	//	error consume type

	const ERROR_NETWORK			= -100300;	//	error network
	const ERROR_JSON			= -100301;	//	error json
	const ERROR_JSON_ERRORID		= -100302;	//	error json.errorid
	const ERROR_JSON_ERRORDESC		= -100303;	//	error json.errordesc
	const ERROR_JSON_VDATA			= -100304;	//	error json.vdata

	//
	//	public parameters
	//
	const ERROR_PUBPARAM_SOURCE		= -100401;	//	error - source
	const ERROR_PUBPARAM_APP_ID		= -100402;	//	error - app id of client/caller
	const ERROR_PUBPARAM_APP_NAME		= -100403;	//	error - app name of client/caller
	const ERROR_PUBPARAM_APP_VERSION	= -100404;	//	error - 软件版本号
	const ERROR_PUBPARAM_APP_CHANNEL	= -100405;	//	error - app的渠道
	const ERROR_PUBPARAM_SYSTEM_VERSION	= -100406;	//	error - 手机系统版本号
	const ERROR_PUBPARAM_PHONE_MODEL	= -100407;	//	error - 手机型号（例如iPhone5s）
	const ERROR_PUBPARAM_SCREEN_RESOLUTION	= -100408;	//	error - 屏幕分辨率（例如1136x640）
	const ERROR_PUBPARAM_MOBILE_OPERATOR	= -100409;	//	error - 运营商（CT:中国电信， CMCC:中国移动， CU:中国联通）
	const ERROR_PUBPARAM_IMEI		= -100410;	//	error - 移动设备国际识别码
	const ERROR_PUBPARAM_NET_STATUS		= -100411;	//	error - 网络状态(2G, 3G, 4G, WiFi)
	const ERROR_PUBPARAM_MAC_ADDRESS	= -100412;	//	error - mac地址
	const ERROR_PUBPARAM_SYSTEM_LANGUAGE	= -100413;	//	error - 系统语言
	const ERROR_PUBPARAM_SYSTEM_TIMEZONE	= -100414;	//	error - 系统时区，单位为：分钟


	//
	//	tools
	//
	static function IsValidSource( $nVal )
	{
		return ( is_numeric( $nVal ) &&
		(
			self::SOURCE_ANDROID == $nVal ||	//	Android
			self::SOURCE_IOS == $nVal ||		//	IOS
			self::SOURCE_WAP == $nVal ||		//	WAP
			self::SOURCE_PC == $nVal ||		//	PC
			self::SOURCE_DATAIMPORTER == $nVal ||	//	data importer
			self::SOURCE_NIRVANA == $nVal ||	//	for nirvana
			self::SOURCE_MGR_SYSTEM == $nVal	//	management system
		));
	}
}

?>