<?php

namespace libcn\lib;


/**
 *     CLib 
 */
class CLib 
{
	const ENCODEOBJECT_TYPE_SERIALIZE	= 1;
	const ENCODEOBJECT_TYPE_JSON		= 2;

	const VARTYPE_NUMERIC			= 1;
	const VARTYPE_STRING			= 2;
	const VARTYPE_ARRAY			= 3;

	static function IsArrayWithKeys( $vData, $vKeys = null )
	{
		//
		//	vData	- array
		//	vKeys	- keys array, like: [ 'key1', 'key2', ... ]
		//		  key string, like: 'key1'
		//	RETURN	- true / false
		//
		if ( ! is_array( $vData ) )
		{
			return false;
		}

		//	...
		$bRet = false;

		if ( is_array( $vKeys ) && count( $vKeys ) > 0 )
		{
			//	vKeys is a list in array
			//	check if vData have the specified keys
			$nKeyCount	= count( $vKeys );
			$nMatchedCount	= 0;
			foreach ( $vKeys as $vKey )
			{
				if ( is_string( $vKey ) || is_numeric( $vKey ) )
				{
					if ( array_key_exists( $vKey, $vData ) )
					{
						$nMatchedCount ++;
					}
				}
			}

			$bRet = ( $nKeyCount == $nMatchedCount );
		}
		else if ( self::IsExistingString( $vKeys ) )
		{
			//	vKeys is a key in string
			$bRet = array_key_exists( $vKeys, $vData );
		}
		else
		{
			//	vKeys is null
			$bRet = ( count( $vData ) > 0 );
		}

		return $bRet;
	}
	static function IsSameString( $sStr1, $sStr2 )
	{
		return ( is_string( $sStr1 ) && is_string( $sStr2 ) && 0 == strcmp( $sStr1, $sStr2 ) );
	}
	static function IsCaseSameString( $sStr1, $sStr2 )
	{
		return ( is_string( $sStr1 ) && is_string( $sStr2 ) && 0 == strcasecmp( $sStr1, $sStr2 ) );
	}
	static function IsExistingString( $sStr, $bTrim = false )
	{
		$bRet	= false;

		if ( is_string( $sStr ) )
		{
			$bRet = ( strlen( $bTrim ? trim( $sStr ) : $sStr ) > 0 );
		}

		return $bRet;
	}

	static function EncodeObject( $ArrObject, $nEncodeType = CLib::ENCODEOBJECT_TYPE_JSON )
	{
		$sRet = '';

		if ( is_array( $ArrObject ) )
		{
			if ( self::ENCODEOBJECT_TYPE_SERIALIZE == $nEncodeType )
			{
				$sRet = @ serialize( $ArrObject );
			}
			else if ( self::ENCODEOBJECT_TYPE_JSON == $nEncodeType )
			{
				$sRet = @ json_encode( $ArrObject );
			}
		}

		return $sRet;
	}

	static function DecodeObject( $sString, $nEncodeType = CLib::ENCODEOBJECT_TYPE_JSON )
	{
		$ArrRet	= Array();

		//	...
		$sString = trim( $sString );
		if ( ! empty( $sString ) )
		{
			if ( self::ENCODEOBJECT_TYPE_SERIALIZE == $nEncodeType )
			{
				$ArrRet = @ unserialize( $sString );
			}
			else if ( self::ENCODEOBJECT_TYPE_JSON == $nEncodeType )
			{
				$ArrRet = @ json_decode( $sString, true );
			}

			if ( ! is_array( $ArrRet ) )
			{
				$ArrRet = Array();
			}
		}

		return $ArrRet;
	}

	static function GetClientIP( $bMustBePublic = true, $bPlayWithProxy = true )
	{
		//
		//	bMustBePublic	- true 	/ the ip address must be a valid public address
		//			  false	/ return true if an address is valid in its format.
		//				  return true for all type of internal addresses, e.g.: 127.0.0.1, 192.168.0.1
		//	bPlayWithProxy	- true	/ try to extract address from proxy field of HTTP
		//			  false	/ give up to extract address from proxy field
		//	RETURN		- ip address of client
		//
		//
		//	* History
		//		liu qixing	created		@20160221
		//
		//	* About HTTP_X_FORWARDED_FOR
		//
		//		The X-Forwarded-For (XFF) HTTP header field was a common method for identifying
		//		the originating IP address of a client connecting to a web server through an HTTP proxy
		//		or load balancer.
		//
		//		The general format of the field is:
		//		X-Forwarded-For: client, proxy1, proxy2
		//
		//		Where the value is a comma+space separated list of IP addresses,
		// 		the left-most being the original client, and each successive proxy that passed the request
		// 		adding the IP address where it received the request from.
		// 		In this example, the request passed through proxy1, proxy2, and then proxy3 ( not shown in the header ).
		// 		proxy3 appears as remote address of the request.
		//
		//		Since it is easy to forge an X-Forwarded-For field the given information should be used with care.
		// 		The last IP address is always the IP address that connects to the last proxy,
		// 		which means it is the most reliable source of information.
		// 		X-Forwarded-For data can be used in a forward or reverse proxy scenario.
		//
		//		Just logging the X-Forwarded-For field is not always enough as the last proxy IP address in a chain
		// 		is not contained within the X-Forwarded-For field, it is in the actual IP header.
		//		A web server should log BOTH the request's source IP address and
		// 		the X-Forwarded-For field information for completeness.
		//
		if ( ! is_bool( $bMustBePublic ) || ! is_bool( $bPlayWithProxy ) )
		{
			return '';
		}

		//	...
		$sRet		= '';
		$sClientIp	= '';

		//	...
		if ( self::IsArrayWithKeys( $_SERVER ) )
		{
			if ( $bPlayWithProxy &&
				array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ) &&
				self::IsExistingString( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) )
			{
				$sClientIp = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
			}
			else if ( array_key_exists( 'REMOTE_ADDR', $_SERVER ) &&
				self::IsExistingString( $_SERVER[ 'REMOTE_ADDR' ] ) )
			{
				$sClientIp = $_SERVER[ 'REMOTE_ADDR' ];
			}
		}
		else
		{
			if ( $bPlayWithProxy &&
				getenv( 'HTTP_X_FORWARDED_FOR' ) )
			{
				$sClientIp = getenv( 'HTTP_X_FORWARDED_FOR' );
			}
			else
			{
				$sClientIp = getenv( 'REMOTE_ADDR' );
			}
		}

		if ( self::IsExistingString( $sClientIp ) )
		{
			$nPos = strpos( $sClientIp, ',' );
			if ( false !== $nPos )
			{
				//
				//	may be an address from HTTP_X_FORWARDED_FOR
				//
				$sClientIp = trim( substr( $sClientIp, 0, $nPos ) );
				if ( self::IsValidIp( $sClientIp, $bMustBePublic ) )
				{
					$sRet = $sClientIp;
				}
			}
			else
			{
				//
				//	may be an address from REMOTE_ADDR
				//
				if ( self::IsValidIp( $sClientIp, $bMustBePublic ) )
				{
					$sRet = $sClientIp;
				}
			}
		}

		return $sRet;
	}

	static function IsValidIP( $sStr, $bMustBePublic = true, $bTrim = false )
	{
		//
		//	sStr		- the ip address / the variable being evaluated
		//	bMustBePublic	- true 	/ the ip address must be a valid public address
		//			  false	/ return true if an address is valid in its format.
		//				  return true for all type of internal addresses, e.g.: 127.0.0.1, 192.168.0.1
		//	RETURN		- ip address or empty if occurred errors
		//
		//
		//	<Documentation>
		//		https://en.wikipedia.org/wiki/X-Forwarded-For
		//		https://en.wikipedia.org/wiki/IPv6
		//		http://php.net/manual/en/function.filter-var.php
		//
		if ( ! self::IsExistingString( $sStr ) )
		{
			return false;
		}
		if ( ! is_bool( $bMustBePublic ) )
		{
			return false;
		}
		if ( false == $bMustBePublic && self::IsSameString( '127.0.0.1', $sStr ) )
		{
			return true;
		}
		if ( ! is_bool( $bTrim ) )
		{
			return false;
		}

		//	...
		$sStr	= ( $bTrim ? trim( $sStr ) : $sStr );

		//
		//	Documentation
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
		return ( false !== filter_var
			(
				$sStr,
				FILTER_VALIDATE_IP,
				$bMustBePublic ? ( FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) : FILTER_DEFAULT
			) );
	}



	static function IsValidMobile( $sStr, $bTrim = false )
	{
		//
		//	sStr	- cell phone number / the variable being evaluated
		//	bTrim	- if we trim sStr before checking
		//	RETURN	- true / false
		//
		if ( ! self::IsExistingString( $sStr ) )
		{
			return false;
		}
		if ( ! is_bool( $bTrim ) )
		{
			return false;
		}

		$sReExp	= '/^(?:13|14|15|18|17)[0-9]{9}$/';
		$sStr	= ( $bTrim ? trim( $sStr ) : $sStr );

		return ( 1 == preg_match( $sReExp, $sStr ) );
	}

	static function IsMobileDevice()
	{
		$cMobDet = CMobileDetector::GetInstance();
		return ( $cMobDet->isMobile() || $cMobDet->isTablet() );
	}

	static function GetVal( $arrObj, $sName, $bIsNumeric = false, $vDefValue = null )
	{
		//
		//	arrObj		- [in] object
		//	sName		- [in] index name
		//	bIsNumeric	- [in] is numeric
		//	vDefValue	- [in] default value
		//
		$vRet = $vDefValue;

		if ( ! self::IsArrayWithKeys( $arrObj ) )
		{
			return $vDefValue;
		}
		if ( ! self::IsExistingString( $sName ) )
		{
			return $vDefValue;
		}

		if ( array_key_exists( $sName, $arrObj ) && isset( $arrObj[ $sName ] ) )
		{
			if ( $bIsNumeric )
			{
				if ( is_numeric( $arrObj[ $sName ] ) )
				{
					$vRet = $arrObj[ $sName ];
				}
			}
			else
			{
				$vRet = $arrObj[ $sName ];
			}
		}

		return $vRet;
	}
	static function GetValEx( $arrObj, $sName, $nVarType = self::VARTYPE_STRING, $vDefValue = null )
	{
		//
		//	arrObj		- [in] object
		//	sName		- [in] index name
		//	nVarType	- [in] type of variable: VARTYPE_NUMERIC, VARTYPE_STRING, VARTYPE_ARRAY
		//	vDefValue	- [in] default value
		//	RETURN		- value in user specified type
		//
		$vRet = $vDefValue;

		if ( ! self::IsArrayWithKeys( $arrObj ) )
		{
			return $vDefValue;
		}
		if ( ! self::IsExistingString( $sName ) )
		{
			return $vDefValue;
		}

		if ( array_key_exists( $sName, $arrObj ) && isset( $arrObj[ $sName ] ) )
		{
			if ( self::VARTYPE_NUMERIC == $nVarType )
			{
				if ( is_numeric( $arrObj[ $sName ] ) )
				{
					$vRet = $arrObj[ $sName ];
				}
			}
			else if ( self::VARTYPE_STRING == $nVarType )
			{
				if ( is_string( $arrObj[ $sName ] ) || is_numeric( $arrObj[ $sName ] ) )
				{
					$vRet = strval( $arrObj[ $sName ] );
				}
			}
			else if ( self::VARTYPE_ARRAY == $nVarType )
			{
				if ( is_array( $arrObj[ $sName ] ) )
				{
					$vRet = $arrObj[ $sName ];
				}
			}
			else
			{
				$vRet = $arrObj[ $sName ];
			}
		}

		return $vRet;
	}

	static function SafeStringVal( $vVal, $sDefaultVal = '' )
	{
		return ( ( is_string( $vVal ) || is_numeric( $vVal ) ) ? strval( $vVal ) : $sDefaultVal );
	}
	static function SafeIntVal( $vVal, $nDefaultVal = 0 )
	{
		return ( ( is_string( $vVal ) || is_numeric( $vVal ) || is_array( $vVal ) ) ? intval( $vVal ) : $nDefaultVal );
	}

	static function GenerateRandomString( $nMaxLength = 10, $bNumeric = false )
	{
		$sRet = '';

		//	...
		$sChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if ( $bNumeric )
		{
			$sChars = '0123456789';
		}

		//	...
		$nCharLen = strlen( $sChars );

		for ( $i = 0; $i < $nMaxLength; $i ++ )
		{
			$sRet .= $sChars[ rand( 0, $nCharLen - 1 ) ];
		}

		return $sRet;
	}




	 static function IsValidEmail( $sEmailString, $bCheckDNS=false )
     {
         $bRet = false;
         if(!is_bool($bCheckDNS)){
             return false;
         }
         if(is_string($sEmailString)&&3<=strlen(trim($sEmailString))){
             if( filter_var($sEmailString,FILTER_VALIDATE_EMAIL) ){
                 $bRet = $bCheckDNS?checkdnsrr(array_pop(explode("@",$sEmailString)),"MX"):true;
             }
         }
         return $bRet;
     }


     static function IsValidUrl( $sUrlString, $bCheckDNS=false )
     {
	    $bRet = false;
         if(!is_bool($bCheckDNS)){
             return false;
         }
         if(is_string($sUrlString)&&3<=strlen(trim($sUrlString))){
             if( filter_var($sUrlString,FILTER_VALIDATE_URL) ){
                 $aTmp = parse_url( $sUrlString );
                 if( array_key_exists('host',$aTmp) ){
                     $bRet = $bCheckDNS?checkdnsrr($aTmp['host'],"A"):true;
                 }
             }
         }
         return $bRet;
     }


}