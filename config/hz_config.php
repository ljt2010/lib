<?php
/**
 * Created by PhpStorm.
 * User: huizhi
 * Date: 2017/11/22
 * Time: 17:44
 */
$version = 'v2';
return [
    'server'=>'https://dapi2.ecosysnet.com',
    'version'=>$version,
    'create_wallet'=>["/$version/new",'get'],
    'active_wallet'=>["/$version/activate",'post'],
    'query_wallet'=>["/$version/assets",'get'],
    'query_trust'=>["/$version/trust",'get'],

    'set_trust'=>["/$version/trust",'post'],
    'delete_trust'=>["/$version/trust",'delete'],

    'query_transaction_path'=>["/$version/path_tracker",'post'],
    'transfer_transaction'=>["/$version/transfer",'post'],
    'create_consignment_order'=>["/$version/orders",'post'],
    'cancel_consignment_order'=>["/$version/orders",'delete'],
    'query_consignment_order'=>["/$version/orders",'get'],
    'exchange'=>["/$version/exchange",'post'],
    'query_consignment_assets_price'=>["/$version/assets_bids",'post'],
    'query_transactions_record'=>["/$version/transactions",'get'],

    'issue_assets'=>["/$version/issue",'post'],
    'release_assets'=>["/$version/initiative",'post'],
    'query_release_assets'=>["/$version/publiccodes",'get'],




];