<?php
namespace libcn\lib\src;



class CRequest
{
    public static $oInstance = null;

    public $sMethod = 'GET';
    public $mixParameters = [];
    public $urlParameters = [];
    public $sUrl = '';
    public $body = null;
    public $response = null;
    public $nResponseStatus = null;
    public $nTimeOut = 5;
    public $sContentType = 'application/x-www-form-urlencoded';
    public $rCurlHandle = null;
    public $sCAInfo = '';
    public $aCookie = [];
    public $aHeader = [];
    public $sChartSet = 'utf-8';
    public $HasFile = false;
    public $WithCookie = false;
    public $GetHeader = false;


    private function __construct($sUrl)
    {
        if( is_string($sUrl) ){
            $this->sUrl = $sUrl;
            return $this;
        }
        return false;
    }


    public static function GetInstance($sUrl)
    {
        if (!(isset($oInstance) && $oInstance instanceof CRequest)) {
            $oInstance = new self($sUrl);
        }

        return $oInstance;
    }

    public function setMethod($sMethod)
    {
        if ($this->_IsValidMethod($sMethod)) {
            $this->sMethod = strtoupper($sMethod);
            return $this;
        }
        return false;
    }

    public function setHeader($sHeaderName, $sHeaderValue)
    {
        if (is_string($sHeaderName) && 0 < strlen(trim($sHeaderName))) {
            $this->_setHeader($sHeaderName, $sHeaderValue);
            return $this;
        }
        return false;
    }

    public function setCharset($sCharset = 'utf-8')
    {
        if (is_string($sCharset) && 0 < strlen(trim($sCharset))) {
            $this->aHeader['Charset'] = $sCharset;
            return $this;
        }
        return false;
    }


    public function setHeaders($aHeaders)
    {
        if (is_array($aHeaders) && 0 < count($aHeaders)) {
            foreach ($aHeaders as $sHeaderName => $sHeaderValue) {
                $this->_setHeader($sHeaderName, $sHeaderValue);
            }
            return $this;
        }
        return false;
    }

    public function setCookie($sCookieName, $sCookieValue)
    {
        if (is_string($sCookieName) && 0 < strlen(trim($sCookieName))) {
            $this->_setHeader($sCookieName, $sCookieValue);
            $this->WithCookie = true;
            return $this;
        }
        return false;
    }

    public function setCookies($aCookies)
    {
        if (is_array($aCookies) && 0 < count($aCookies)) {
            foreach ($aCookies as $sCookieName => $sCookieValue) {
                $this->_setHeader($sCookieName, $sCookieValue);
            }
            $this->WithCookie = true;
            return $this;
        }
        return false;
    }

    public function setParameter($sParameterName, $sParameterValue)
    {
        $this->mixParameters[$sParameterName] = $sParameterValue;
        return $this;
    }

    public function setParameters($aParameters)
    {
        if (is_array($aParameters) && 0 < count($aParameters)) {
            foreach ($aParameters as $sParameterName => $sParameterValue) {
                $this->setParameter($sParameterName, $sParameterValue);
            }
            return $this;
        } else {
            return false;
        }
    }

    public function setUrlParameter($sParameterName, $sParameterValue)
    {
        $this->urlParameters[$sParameterName] = $sParameterValue;
        return $this;
    }

    public function setUrlParameters($aParameters)
    {
        if (is_array($aParameters) ) {
            foreach ($aParameters as $sParameterName => $sParameterValue) {
                $this->setUrlParameter($sParameterName, $sParameterValue);
            }
            return $this;
        } else {
            return false;
        }
    }


    public function setTimeout($nTime)
    {
        if (!(is_int($nTime) && 0 < $nTime)) {
            return false;
        }
        $this->nTimeOut = $nTime;
        return $this;
    }


    public function setUrl($sUrl)
    {
        if (is_string($sUrl) && 0 < strlen($sUrl)) {
            $this->sUrl = trim($sUrl);
        } else {
            return false;
        }
        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function setFile($sFilePath, $sFileName = 'file')
    {
        if (isset($sFilePath) && file_exists($sFilePath)) {
            $sFilePath = realpath($sFilePath);
            if (class_exists('\CURLFile')) {
                $this->mixParameters[$sFileName] = new \CURLFile(realpath($sFilePath));
            } else {
                $this->mixParameters[$sFileName] = '@'.$sFilePath;
            }
            $this->HasFile = true;
            return $this;
        }

        return false;
    }


    public function setContentType($sContentType)
    {
        if (is_string($sContentType) && 0 < strlen($sContentType)) {
            $this->sContentType = trim($sContentType);
            $this->aHeader["Content-Type"] = trim($sContentType);
        } else {
            return false;
        }
        return $this;
    }

    public function setCAInfo($sCACertPath)
    {
        if (file_exists($sCACertPath)) {
            $this->sCAInfo = $sCACertPath;
        }

        return false;
    }


    public function WithGetHeader($bGetOrNot = false)
    {
        if (is_bool($bGetOrNot)) {
            $this->GetHeader = $bGetOrNot;
            return $this;
        } else {
            return false;
        }
    }


    public function requestReady()
    {
        //设置链接后的参数
        $sUrlParameters = $this->_getUrlParameterString();
        //设置body的参数
        $sParameters = $this->_getBodyParameterString();

        //初始化
        $ch = curl_init();
        //设置请求地址
        curl_setopt($ch, CURLOPT_URL, $this->LinkUrlAndParameter($this->sUrl, $sUrlParameters));
        if ($this->HasFile && 'POST' != $this->sMethod) {
            echo "上传文件方法必须为POST";
            exit;
        }
        //根据scheme设置证书信息
        if (false == strpos($this->sUrl, 'https') && '' == $this->sCAInfo) {
            //禁用后cURL将终止从服务端进行验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //生产环境  CURLOPT_SSL_VERIFYHOST 设置为 2
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        } elseif (file_exists($this->sCAInfo)) {
            //设置使用证书及证书路径
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            //生产环境  CURLOPT_SSL_VERIFYHOST 设置为 2
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, $this->sCAInfo);
        } else {
            //证书信息设置错误或者证书不存在，返回false
            echo '证书信息设置错误！';
            exit;
        }
        //设置超时信息
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->nTimeOut);
        //设置请求方法
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->sMethod);
        //设置传参
        if (in_array($this->sMethod, ['POST', 'PUT','DELETE','OPTIONS'])) {
            curl_setopt($ch, CURLOPT_POST, 1);
            if( null!==$this->body ){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
            } else{
                curl_setopt($ch, CURLOPT_POSTFIELDS, $sParameters);
            }
            $this->_setHeader('CONTENT-TYPE', $this->sContentType );
        }
        //设置COOKIE信息
        if ($this->WithCookie) {
            $sCookieString = http_build_query($this->aCookie, '', '&', PHP_QUERY_RFC3986);
            curl_setopt($ch, CURLOPT_COOKIE, $sCookieString);
        }
        //设置请求头信息
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->aHeader); //设置头信息的地方
        //是否获取头信息
        curl_setopt($ch, CURLOPT_HEADER, $this->GetHeader);
        //返回原生的(Raw)输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //支持跳转
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $this->rCurlHandle = $ch;
        return $this;
    }


    public function requestSend($bGetResponse = true)
    {
        if ($this->requestReady()) {
            if ($this->_IsValidCUrlHandle($this->rCurlHandle)) {
                $this->response = curl_exec($this->rCurlHandle);
                $this->nResponseStatus = curl_getinfo($this->rCurlHandle, CURLINFO_HTTP_CODE);
                if (200 != $this->nResponseStatus || 302 != $this->nResponseStatus || 301 != $this->nResponseStatus) {
                    $this->errMsg = curl_errno($this->rCurlHandle);
                    $this->errNum = curl_error($this->rCurlHandle);
                }
                curl_close($this->rCurlHandle);
                if ($bGetResponse) {
                    return $this->response;
                }
                return $this;
            }
        }
        return false;
    }


    private function _getUrlParameterString()
    {
        $sUrlParameters = http_build_query($this->urlParameters, '', '&', PHP_QUERY_RFC3986);
        if( 'GET' === $this->sMethod ){
            if( is_array( $this->mixParameters ) ){
                $sUrlParameters = http_build_query(
                    array_merge( $this->mixParameters,$this->urlParameters ),
                    '', '&', PHP_QUERY_RFC3986);
            }
        }
        return $sUrlParameters;
    }


    private function _getBodyParameterString()
    {
        if(false!==strpos($this->sContentType,'multipart/form-data')){
            //传递数组
            $BodyParameters = $this->mixParameters;
        } else{
            if( is_string( $this->mixParameters ) ){
                $BodyParameters = $this->mixParameters;
            } else{
                $BodyParameters = http_build_query($this->mixParameters, '', '&', PHP_QUERY_RFC3986);
            }
        }
        return $BodyParameters;
    }

    protected function LinkUrlAndParameter($sUrl, $sParameters)
    {
        $sUrl .= (strchr($sUrl, '?') ? '&' : '?') . $sParameters;
        return $sUrl;
    }


    private function _IsValidCUrlHandle($oCUrl)
    {
        return (isset($oCUrl) && false !== $oCUrl && is_resource($oCUrl));
    }


    private function _IsValidMethod($sMethod)
    {
        return in_array(strtoupper($sMethod), ['GET', 'POST', 'PUT', 'DELETE', 'HEAD', 'OPTIONS']);
    }


    private function _setHeader($sHeaderName, $sHeaderValue)
    {
        $this->aHeader[] = trim($sHeaderName).':'.$sHeaderValue;
    }

    public function __get($name)
    {
        return $this->$name;
    }


    public function ToArray()
    {
        $aRet = [];
        if ($this->response) {
            $aResponse = explode("\r\n", $this->response);
            if ($this->GetHeader && '' == $aResponse[count($aResponse) - 2]) {
                $aRet['status'] = array_shift($aResponse);
                $body = array_pop($aResponse);
                array_pop($aResponse);
                foreach ($aResponse as $item) {
                    $tmp = explode(': ', $item);
                    $aRet['header'][$tmp[0]] = $tmp[1];
                }
                $aRet['body'] = $body;

            } else {
                $aRet['body'] = $this->response;
            }
        }

        return $aRet;
    }



}


