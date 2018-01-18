<?php
namespace libcn\lib\src;

use libcn\lib\CLib;
use libcn\lib\src\CRequest;
class HZBlockChain
{
    private $sServer = "";

    private $aConfig = [];

    private $aParameter = [];

    private $sBody = null;

    private $sMethod = 'get';

    public $sResponse = null;

    private $bDebug = true;

    private $sContentType = 'application/json';

    public function __construct()
    {
        $this->_init();
    }



    public function CreateWallet( &$account )
    {
        if( $this->setInterface('create_wallet') ){
            $this->sResponse = CRequest::GetInstance($this->sServer)->setMethod($this->sMethod)->requestSend();
            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success','account'] ) && true===$result['success'] ){
                    $account = $result['account'];
                    return true;
                }
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }


    public function ActiveWallet( $aParameter,&$aRet )
    {
        if( !CLib::IsArrayWithKeys( $aParameter,['source_account','destination_account','source_private_key'] ) ){
            $this->echoError('激活参数必须含有source_account，destination_account,source_private_key');
            return false;
        }
        if( CLib::IsArrayWithKeys($aParameter,['issuer']) &&
            CLib::IsArrayWithKeys($aParameter,['destination_private_key']) ){
            $this->echoError('指定issuer时必须提交destination_private_key');
            return false;
        }
        $aUrlPath = [
            'source_account'=>$aParameter['source_account'],
            'destination_account'=>$aParameter['destination_account']
        ];
        $aBody = ['source_private_key'=>$aParameter['source_private_key']];
        if( array_key_exists('destination_private_key',$aParameter ) ){
            $aBody['$aBody'] = $aParameter['destination_private_key'];
        }
        $aTmp = [];
        foreach ( $aParameter as $key=>$value ){
            if( !in_array($key,['source_account','destination_account','source_private_key','destination_private_key'])){
                $aTmp[$key]=$value;
            }
        }
        $sBody = json_encode($aBody);
        if( $this->setInterface('active_wallet') ){
            if($this->_setUrlPath( $aUrlPath ) instanceof HZBlockChain){
                $this->sResponse = CRequest::GetInstance($this->sServer)
                    ->setContentType($this->sContentType)
                    ->setMethod($this->sMethod)
                    ->setUrlParameters($aTmp)
                    ->setBody($sBody)
                    ->requestSend();
                if( is_string( $this->sResponse) ){
                    $result = $this->processResult();
                    if( CLib::IsArrayWithKeys( $result,['success'] ) ){
                        if(true===$result['success']){
                            $aRet = $result;
                            return true;
                        } else {
                            $this->echoError($this->sResponse );
                            return false;
                        }
                    } else {
                        $this->echoError("返回结果不是json",__LINE__);
                        $this->echoError($this->sResponse );
                    }
                } else{
                    $this->echoError("返回结果格式错误",__LINE__);
                    $this->echoError($this->sResponse );
                }
            } else{
                $this->echoError("设置URL路径错误",__LINE__);
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;

    }

    public function QueryWallet( $sAccount, &$aInfo , $sParameter = [] )
    {
        if( !CLib::IsExistingString( $sAccount ) ){
            return false;
        }
        if( !is_array( $sParameter ) ){
            return false;
        }
        if( $this->setInterface('query_wallet') ){
            $this->_setUrlPath($sAccount);
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setMethod($this->sMethod)
                ->setUrlParameters($sParameter)
                ->requestSend();
            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aInfo = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }


    public function QueryTrust( $sAccount, &$aInfo  )
    {
        if( !CLib::IsExistingString( $sAccount ) ){
            return false;
        }
        if( $this->setInterface('query_trust') ){
            $this->_setUrlPath($sAccount);
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setMethod($this->sMethod)
                ->requestSend();
            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aInfo = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }


    public function SetTrust( $aParameter, &$aInfo  )
    {
        if( !CLib::IsArrayWithKeys($aParameter,['source_account','private_key','trust']) ){
            $this->echoError("设置信任必须传递参数source_account,private_key,trust");
            return false;
        }
        if( !CLib::IsArrayWithKeys( $aParameter['trust'],'issuer' ) ){
            $this->echoError("设置信任参数trust必须包含参数issuer");
            return false;
        }
        if( $this->setInterface('set_trust') ){
            $this->_setUrlPath($aParameter['source_account']);
            unset($aParameter['source_account']);
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setContentType($this->sContentType)
                ->setMethod($this->sMethod)
                ->setBody(json_encode( $aParameter ))
                ->requestSend();
            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aInfo = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }

    public function DeleteTrust( $aParameter, &$aInfo  )
    {
        if( !CLib::IsArrayWithKeys($aParameter,['source_account','private_key','trust']) ){
            $this->echoError("设置信任必须传递参数source_account,private_key,trust");
            return false;
        }

        if( !CLib::IsArrayWithKeys( $aParameter['trust'],'issuer' ) ){
            $this->echoError("删除信任参数trust必须包含参数issuer");
            return false;
        }

        if( $this->setInterface('delete_trust') ){
            $this->_setUrlPath($aParameter['source_account']);
            unset($aParameter['source_account']);
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setContentType($this->sContentType)
                ->setMethod($this->sMethod)
                ->setBody(json_encode( $aParameter ))
                ->requestSend();
            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aInfo = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }

    public function QueryTransactionPath( $aParameter, &$aPath )
    {
        if( !CLib::IsArrayWithKeys($aParameter,['tracker']) ){
            $this->echoError("查询交易路径必须传递参数tracker");
            return false;
        }
        if( !CLib::IsArrayWithKeys( $aParameter['tracker'],['source_account','source_amount',
            'destination_account','destination_amount'] ) ){
            $this->echoError("查询交易路径必须传递参数tracker必须含有source_account,source_amount,
            destination_account,destination_amount");
            return false;
        }
        if( !CLib::IsArrayWithKeys( $aParameter['tracker']['source_amount'],['amount','code', 'issuer'] ) ){
            $this->echoError("查询交易路径必须传递参数source_amount必须含有amount,code, issuer");
            return false;
        }
        if( !CLib::IsArrayWithKeys( $aParameter['tracker']['destination_amount'],['amount','code', 'issuer'] ) ){
            $this->echoError("查询交易路径必须传递参数source_amount必须含有amount,code, issuer");
            return false;
        }
        if( $this->setInterface('query_transaction_path') ){
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setContentType($this->sContentType)
                ->setMethod($this->sMethod)
                ->setBody(json_encode( $aParameter ))
                ->requestSend();
            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aPath = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }


    public function TransferTransactionPath($aParameter,&$aTransactionResult)
    {
        if( !CLib::IsArrayWithKeys($aParameter,['source_account','private_key','serial_number','transfer']) ){
            $this->echoError("转移交易必须传递参数source_account,private_key,serial_number,transfer");
            return false;
        }
        if( !CLib::IsArrayWithKeys( $aParameter['transfer'],['destination_account','amount','code', 'issuer'] ) ){
            $this->echoError("查转移交易参数transfer必须含有destination_account,amount,code,issuer");
            return false;
        }
        //处理接口可选参数
        $aUrlParameter = [];
        if( CLib::IsArrayWithKeys( $aParameter,'validated' ) ){
            if(!is_bool($aParameter['validated'])){
                $this->echoError("参数validated错误，必须为布尔值");
                return false;
            } else{
                $aUrlParameter['validated'] = $aParameter['validated'];
            }
        }

        if( $this->setInterface('transfer_transaction') ){
            $this->_setUrlPath($aParameter['source_account']);
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setContentType($this->sContentType)
                ->setMethod($this->sMethod)
                ->setUrlParameters($aUrlParameter)
                ->setBody(json_encode( $aParameter ))
                ->requestSend();

            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aTransactionResult = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }


    public function CreateConsignmentOrder( $aParameter, &$aOrderInfo )
    {
        if( !CLib::IsArrayWithKeys($aParameter,['source_account','private_key','type','gets','pays','serial_number']) ){
            $this->echoError("创建挂单必须传递参数 source_account,private_key,type,gets,pays,serial_number");
            return false;
        }
        if( !CLib::IsArrayWithKeys( $aParameter['gets'],['amount','code', 'issuer'] ) ){
            $this->echoError("创建挂单参数 gets 必须含有 amount,code,issuer");
            return false;
        }
        if( !CLib::IsArrayWithKeys( $aParameter['pays'],['amount','code', 'issuer'] ) ){
            $this->echoError("创建挂单参数 pays 必须含有 amount,code,issuer");
            return false;
        }
        if( CLib::IsArrayWithKeys( $aParameter,['Validated'] )
            &&  false == $aParameter['Validated'] && !CLib::IsArrayWithKeys(  $aParameter,['serial_number']) ){
            $this->echoError("异步创建订单必须传递参数 serial_number");
            return false;
        }
        //处理接口可选参数
        $aUrlParameter = [];
        if( CLib::IsArrayWithKeys( $aParameter,'Validated' ) ){
            if(!is_bool($aParameter['Validated'])){
                $this->echoError("Validated，必须为布尔值");
                return false;
            } else{
                $aUrlParameter['Validated'] = $aParameter['Validated'];
                unset($aParameter['Validated']);
            }
        }

        if( $this->setInterface('create_consignment_order') ){
            $this->_setUrlPath($aParameter['source_account']);
            unset($aParameter['source_account']);
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setContentType($this->sContentType)
                ->setMethod($this->sMethod)
                ->setUrlParameters($aUrlParameter)
                ->setBody(json_encode( $aParameter ))
                ->requestSend();

            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aOrderInfo = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }


    public function QueryConsignmentOrder( $aParameter, &$aOrderInfo )
    {
        if( !CLib::IsArrayWithKeys($aParameter,['source_account']) ){
            $this->echoError("查询挂单必须传递参数 source_account");
            return false;
        }

        if( $this->setInterface('query_consignment_order') ){
            $this->_setUrlPath([$aParameter['source_account'],$aParameter['order_id']]);
            unset($aParameter['source_account']);
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setContentType($this->sContentType)
                ->setMethod($this->sMethod)
                ->setUrlParameters( $aParameter )
                ->requestSend();
            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aOrderInfo = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }


    public function cancelConsignmentOrder( $aParameter, &$aOrderInfo )
    {
        if( !CLib::IsArrayWithKeys($aParameter,['source_account','private_key','order_id']) ){
            $this->echoError("取消挂单必须传递参数 source_account,private_key,order_id");
            return false;
        }

        if( $this->setInterface('cancel_consignment_order') ){
            $this->_setUrlPath([$aParameter['source_account'],$aParameter['order_id']]);
            unset($aParameter['source_account']);
            unset($aParameter['order_id']);
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setContentType($this->sContentType)
                ->setMethod($this->sMethod)
                ->setBody(json_encode( $aParameter ))
                ->requestSend();
            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aOrderInfo = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }

    /*
     * 发布资产
     *
     * {
  "private_key": "saBRtGuRvXuK4NYiCPRoYeuiYLXdy",
  "serial_number": "17fc5146269edcbac86e05ec9cab3484",
  "asset": {
      "code": "90000C0000000200010000012099123100000001",
      "issuer":"enRYsXGHBmhnZv7jCYGeRC4uQxCswMUrpp",
      "hash":"A283543991FF04E8AD6DBCF29E2996BFA94B09383AFD134979475C69335FAE9B",
      "description":"This is some description about the asset.",
      "URL":"https://www.assets.com.cn",
      "status":"on sale"
    }
}
     *
     * */
    public function ReleaseAssets( $aParameter, &$aResult )
    {
        if( !CLib::IsArrayWithKeys($aParameter,['private_key','serial_number','asset']) ){
            $this->echoError("发行资产必须传递参数 'issuer_account',private_key, serial_number,asset");
            return false;
        }

        if( !CLib::IsArrayWithKeys($aParameter['asset'],[ 'code','issuer','hash']) ){
            $this->echoError("issue必须包含参数 code, issuer, hash ");
            return false;
        }

        if( $this->setInterface('release_assets') ){
            $this->_setUrlPath($aParameter['asset']['issuer_account']);
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setContentType($this->sContentType)
                ->setMethod($this->sMethod)
                ->setBody( json_encode($aParameter) )
                ->requestSend();
            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aResult = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }



    /*
     * 发行资产
     * */
    public function IssueAssets( $aParameter, &$aResult )
    {
        if( !CLib::IsArrayWithKeys($aParameter,['issuer_account','private_key','serial_number','issue']) ){
            $this->echoError("发行资产必须传递参数 'issuer_account',private_key, serial_number,issue");
            return false;
        }

        if( !CLib::IsArrayWithKeys($aParameter['issue'],[ 'destination_account','amount','code']) ){
            $this->echoError("issue必须包含参数 destination_account, amount, code ");
            return false;
        }

        if( $this->setInterface('issue_assets') ){
            $this->_setUrlPath($aParameter['issuer_account']);
            unset($aParameter['issuer_account']);
            $this->sResponse = CRequest::GetInstance($this->sServer)
                ->setContentType($this->sContentType)
                ->setMethod($this->sMethod)
                ->setBody( json_encode($aParameter) )
                ->requestSend();
            if( is_string( $this->sResponse) ){
                $result = $this->processResult();
                if( CLib::IsArrayWithKeys( $result,['success'] ) && true===$result['success'] ){
                    $aResult = $result;
                    return true;
                } else{
                    $this->echoError( $result,__LINE__ );
                    return false;
                }
            } else{
                $this->echoError(  $this->sResponse,__LINE__ );
            }
        } else{
            $this->echoError("接口不存在",__LINE__);
        }
        return false;
    }



    public function SetServer( $sNewUrl = '' )
    {
        if( is_string($sNewUrl) ){
            $this->sServer = $sNewUrl;
            return $this;
        } else{
            return false;
        }
    }


    private function setInterface( $sConfigKeyName )
    {
        if( array_key_exists($sConfigKeyName, $this->aConfig) ){
            $this->sServer = $this->aConfig['server'].$this->aConfig[$sConfigKeyName][0];
            $this->sMethod = $this->aConfig[$sConfigKeyName][1];
            return true;
        }
        return false;
    }

    private function _setUrlPath( $Parameter )
    {
        if( is_array($Parameter) ){
            $sPath = implode('/',$Parameter);
            if( is_string( $sPath ) ){
                $this->sServer .= '/'.$sPath;
                return $this;
            } else {
                $this->echoError("url参数错误，数组内容必须是字符串",__LINE__);
            }
        }elseif( is_string( $Parameter ) ){
            $this->sServer .= '/'.$Parameter;
            return $this;
        } else{
            $this->echoError("url参数错误必须为数组或者字符串",__LINE__);
        }
        return false;
    }


    private function _init()
    {
        $this->aConfig = require_once dirname(__FILE__)."/../config/hz_config.php";
    }


    private function echoError(  $sError,$nLineNumber = null )
    {
        if( $this->bDebug ){
            var_dump( $sError);
            echo "\r\n";
            if(null!==$nLineNumber){
                echo "class:".__CLASS__.",line:".$nLineNumber."";
                echo "\r\n";
            }
        }
    }

    private function processResult()
    {
        $aRet = @json_decode($this->sResponse,true);
        if(is_array($aRet)){
            return $aRet;
        } else{
            $this->echoError("返回结果不是json");
            $this->echoError( $this->sResponse );
        }
        return false;
    }



}
require_once "./CRequest.php";
require_once "./CLib.php";

//创建账号
$a = new HZBlockChain();
//var_dump( $a->CreateWallet( $aAccount ),$aAccount);

//激活账号
$aParameter = [
    'source_account'=>'enRYsXGHBmhnZv7jCYGeRC4uQxCswMUrpp',
    'destination_account'=>'eUGqUN7C94XrTgrpunPry7Hmvp4CwtDJpA',
    'source_private_key'=>'saBRtGuRvXuK4NYiCPRoYeuiYLXdy',
    'amount'=>10
];

//var_dump($a->ActiveWallet($aParameter,$aInfo),$aInfo);

//查询账号
$aParameter = [
    'source_account'=>'enRYsXGHBmhnZv7jCYGeRC4uQxCswMUrpp',
];
//var_dump($a->QueryWallet('enRYsXGHBmhnZv7jCYGeRC4uQxCswMUrpp',$aInfo),$aInfo);

//查询信任
$aParameter = [
    'source_account'=>'enRYsXGHBmhnZv7jCYGeRC4uQxCswMUrpp',
];
//var_dump($a->QueryTrust('enRYsXGHBmhnZv7jCYGeRC4uQxCswMUrpp',$aInfo),$aInfo);

//设置信任

$aParameter = [
    'source_account'=>'eUGqUN7C94XrTgrpunPry7Hmvp4CwtDJpA',
    "private_key"=>"shkea9eu2AT6LXeTwxvim9Z4Q4fmU",
    "trust"=>[
        "issuer"=>"ef32USiHX9dBCYSe8K2Vis1K8xm7SWE6MG",
        "code"=> "90000C0000000100020400FF2099123100000000"
    ]
];
//var_dump($a->SetTrust($aParameter,$aInfo),$aInfo);

//删除信任
$aParameter = [
    'source_account'=>'eUGqUN7C94XrTgrpunPry7Hmvp4CwtDJpA',
    "private_key"=>"shkea9eu2AT6LXeTwxvim9Z4Q4fmU",
    "trust"=>[
        "issuer"=>"ef32USiHX9dBCYSe8K2Vis1K8xm7SWE6MG",
        "code"=> "90000C0000000100020400FF2099123100000000"
    ]
];


//var_dump($a->DeleteTrust($aParameter,$aInfo),$aInfo);


//查询交易路径
$aParameter = [
    "tracker"=>[
    "source_account"=>"eUGqUN7C94XrTgrpunPry7Hmvp4CwtDJpA",
    "source_amount"=>[
        "amount"=>"1",
        "code"=>"90000C0000000100020400FF2099123100000000",
        "issuer"=>"enRYsXGHBmhnZv7jCYGeRC4uQxCswMUrpp"
		],
    "destination_account"=>"eMoUXD9XpfrMJtjJTkUZeh3bsEf379wHxS",
    "destination_amount"=>[
        "amount"=>"1",
        "code"=>"90000C0000000100020400FF2099123100000001",
        "issuer"=>"enRYsXGHBmhnZv7jCYGeRC4uQxCswMUrpp"
		]
	]
];

//var_dump($a->QueryTransactionPath($aParameter,$aInfo),$aInfo);


//转移交易
$aParameter = [
    "source_account"=>"eUGqUN7C94XrTgrpunPry7Hmvp4CwtDJpA",
    "validated"=>true,
    "private_key"=>"shkea9eu2AT6LXeTwxvim9Z4Q4fmU",
    "serial_number"=>"17fc5146269edcbac86e05ec9cab3484",
    "transfer"=> [
        "destination_account"=> "eMoUXD9XpfrMJtjJTkUZeh3bsEf379wHxS",
        "amount"=> "2",
        "code"=> "90000C0000000100020400FF2099123100000000",
        "issuer"=> "enRYsXGHBmhnZv7jCYGeRC4uQxCswMUrpp"
    ]
];
//var_dump($a->TransferTransactionPath($aParameter,$aInfo),$aInfo);

//创建挂单
$aParameter = [
    "source_account"=>"eUGqUN7C94XrTgrpunPry7Hmvp4CwtDJpA",
    "private_key"=> "shkea9eu2AT6LXeTwxvim9Z4Q4fmU",
    "Validated"=>true,
    "serial_number"=>"dsdw23344scdscdsccscd543543",
	"type"=>"sell",
	"gets"=> [
        "code"=> "90000C0000000100020400FF2099123100000000",
        "issuer"=> "ef32USiHX9dBCYSe8K2Vis1K8xm7SWE6MG",
        "amount"=> "1"
    ],
    "pays"=> [
        "code"=> "90000C0000000100020400FF2099123100000000",
		"issuer"=> "ef32USiHX9dBCYSe8K2Vis1K8xm7SWE6MG",
    	"amount"=>"1"
	]
];
//var_dump($a->CreateConsignmentOrder($aParameter,$aInfo),$aInfo);

//查询挂单

$aParameter = [
    "source_account"=>"eUGqUN7C94XrTgrpunPry7Hmvp4CwtDJpA"
];

//var_dump($a->QueryConsignmentOrder($aParameter,$aInfo),$aInfo);


//if( true||CLib::IsArrayWithKeys( $aInfo,['sequence'] ) ){
    //取消挂单
 /*   echo "****************测试取消挂单****************";
    echo "\r\n";
    //$sOrderId = $aInfo['sequence'];
    $sOrderId = 1;
    $aParameter = [
        "source_account"=>"eUGqUN7C94XrTgrpunPry7Hmvp4CwtDJpA",
        "private_key"=> "shkea9eu2AT6LXeTwxvim9Z4Q4fmU",
        "order_id"=>$sOrderId
    ];
    //var_dump($a->cancelConsignmentOrder( $aParameter, $aOrderInfo),$aOrderInfo);
}*/


//echo "*********************发布资产***********************\n";
$aParameter = [
    'issuer_account'=> "eaGXzdaoCjC1AidxvsEVGP8M3PXb8JcqBv",
    "private_key"=> "saBRtGuRvXuK4NYiCPRoYeuiYLXdy",
    "serial_number"=> "17fc5146269edcbac86e05ec9cab3484",
    "asset"=> [
        "code"=> "DF12345678901000000000000000000000000000",
        "issuer"=>"enRYsXGHBmhnZv7jCYGeRC4uQxCswMUrpp",
        "hash"=>"A283543991FF04E8AD6DBCF29E2996BFA94B09383AFD134979475C69335FAE9B",
        "description"=>"This is some description about the asset.",
        "URL"=>"https://www.assets.com.cn",
        "status"=>"on sale"
    ]
];

//var_dump($a->ReleaseAssets($aParameter,$result),$result);




 //发行资产测试

//echo "*********************发行资产***********************\n";
$aParameter = [
    'issuer_account'=> "eaGXzdaoCjC1AidxvsEVGP8M3PXb8JcqBv",
    "private_key"=> "snxifGq6xLRsFH5it5Biws7wmHBhL",
    "serial_number"=> '7f1a4a5cd224aa85876b940e2c8958a7',
    "issue"=> [
        "destination_account"=> "eUGqUN7C94XrTgrpunPry7Hmvp4CwtDJpA",
        "amount"=> "10",
        "code"=> "DF12345678901000000000000000000000000000"
    ]
];

//var_dump($a->IssueAssets($aParameter,$aResult),$aResult);


