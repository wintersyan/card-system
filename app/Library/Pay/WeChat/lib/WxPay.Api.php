<?php
require_once 'WxPay.Exception.php'; require_once 'WxPay.Config.php'; require_once 'WxPay.Data.php'; class WxPayApi { public static function unifiedOrder($spc0f55d, $sp7eb8ef = 6) { $sp59c732 = 'https://api.mch.weixin.qq.com/pay/unifiedorder'; if (!$spc0f55d->IsOut_trade_noSet()) { throw new WxPayException('缺少统一支付接口必填参数out_trade_no！'); } else { if (!$spc0f55d->IsBodySet()) { throw new WxPayException('缺少统一支付接口必填参数body！'); } else { if (!$spc0f55d->IsTotal_feeSet()) { throw new WxPayException('缺少统一支付接口必填参数total_fee！'); } else { if (!$spc0f55d->IsTrade_typeSet()) { throw new WxPayException('缺少统一支付接口必填参数trade_type！'); } } } } if ($spc0f55d->GetTrade_type() == 'JSAPI' && !$spc0f55d->IsOpenidSet()) { throw new WxPayException('统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！'); } if ($spc0f55d->GetTrade_type() == 'NATIVE' && !$spc0f55d->IsProduct_idSet()) { throw new WxPayException('统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！'); } if (!$spc0f55d->IsNotify_urlSet()) { $spc0f55d->SetNotify_url(WxPayConfig::NOTIFY_URL); } $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); $spa65db2 = $spc0f55d->ToXml(); $sp83735d = self::getMillisecond(); $sp31c557 = self::postXmlCurl($spa65db2, $sp59c732, false, $sp7eb8ef); $sp820aff = WxPayResults::Init($sp31c557); self::reportCostTime($sp59c732, $sp83735d, $sp820aff); return $sp820aff; } public static function orderQuery($spc0f55d, $sp7eb8ef = 6) { $sp59c732 = 'https://api.mch.weixin.qq.com/pay/orderquery'; if (!$spc0f55d->IsOut_trade_noSet() && !$spc0f55d->IsTransaction_idSet()) { throw new WxPayException('订单查询接口中，out_trade_no、transaction_id至少填一个！'); } $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); $spa65db2 = $spc0f55d->ToXml(); $sp83735d = self::getMillisecond(); $sp31c557 = self::postXmlCurl($spa65db2, $sp59c732, false, $sp7eb8ef); $sp820aff = WxPayResults::Init($sp31c557); self::reportCostTime($sp59c732, $sp83735d, $sp820aff); return $sp820aff; } public static function closeOrder($spc0f55d, $sp7eb8ef = 6) { $sp59c732 = 'https://api.mch.weixin.qq.com/pay/closeorder'; if (!$spc0f55d->IsOut_trade_noSet()) { throw new WxPayException('订单查询接口中，out_trade_no必填！'); } $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); $spa65db2 = $spc0f55d->ToXml(); $sp83735d = self::getMillisecond(); $sp31c557 = self::postXmlCurl($spa65db2, $sp59c732, false, $sp7eb8ef); $sp820aff = WxPayResults::Init($sp31c557); self::reportCostTime($sp59c732, $sp83735d, $sp820aff); return $sp820aff; } public static function refund($spc0f55d, $sp7eb8ef = 6) { $sp59c732 = 'https://api.mch.weixin.qq.com/secapi/pay/refund'; if (!$spc0f55d->IsOut_trade_noSet() && !$spc0f55d->IsTransaction_idSet()) { throw new WxPayException('退款申请接口中，out_trade_no、transaction_id至少填一个！'); } else { if (!$spc0f55d->IsOut_refund_noSet()) { throw new WxPayException('退款申请接口中，缺少必填参数out_refund_no！'); } else { if (!$spc0f55d->IsTotal_feeSet()) { throw new WxPayException('退款申请接口中，缺少必填参数total_fee！'); } else { if (!$spc0f55d->IsRefund_feeSet()) { throw new WxPayException('退款申请接口中，缺少必填参数refund_fee！'); } else { if (!$spc0f55d->IsOp_user_idSet()) { throw new WxPayException('退款申请接口中，缺少必填参数op_user_id！'); } } } } } $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); $spa65db2 = $spc0f55d->ToXml(); $sp83735d = self::getMillisecond(); $sp31c557 = self::postXmlCurl($spa65db2, $sp59c732, true, $sp7eb8ef); $sp820aff = WxPayResults::Init($sp31c557); self::reportCostTime($sp59c732, $sp83735d, $sp820aff); return $sp820aff; } public static function refundQuery($spc0f55d, $sp7eb8ef = 6) { $sp59c732 = 'https://api.mch.weixin.qq.com/pay/refundquery'; if (!$spc0f55d->IsOut_refund_noSet() && !$spc0f55d->IsOut_trade_noSet() && !$spc0f55d->IsTransaction_idSet() && !$spc0f55d->IsRefund_idSet()) { throw new WxPayException('退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！'); } $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); $spa65db2 = $spc0f55d->ToXml(); $sp83735d = self::getMillisecond(); $sp31c557 = self::postXmlCurl($spa65db2, $sp59c732, false, $sp7eb8ef); $sp820aff = WxPayResults::Init($sp31c557); self::reportCostTime($sp59c732, $sp83735d, $sp820aff); return $sp820aff; } public static function downloadBill($spc0f55d, $sp7eb8ef = 6) { $sp59c732 = 'https://api.mch.weixin.qq.com/pay/downloadbill'; if (!$spc0f55d->IsBill_dateSet()) { throw new WxPayException('对账单接口中，缺少必填参数bill_date！'); } $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); $spa65db2 = $spc0f55d->ToXml(); $sp31c557 = self::postXmlCurl($spa65db2, $sp59c732, false, $sp7eb8ef); if (substr($sp31c557, 0, 5) == '<xml>') { return ''; } return $sp31c557; } public static function micropay($spc0f55d, $sp7eb8ef = 10) { $sp59c732 = 'https://api.mch.weixin.qq.com/pay/micropay'; if (!$spc0f55d->IsBodySet()) { throw new WxPayException('提交被扫支付API接口中，缺少必填参数body！'); } else { if (!$spc0f55d->IsOut_trade_noSet()) { throw new WxPayException('提交被扫支付API接口中，缺少必填参数out_trade_no！'); } else { if (!$spc0f55d->IsTotal_feeSet()) { throw new WxPayException('提交被扫支付API接口中，缺少必填参数total_fee！'); } else { if (!$spc0f55d->IsAuth_codeSet()) { throw new WxPayException('提交被扫支付API接口中，缺少必填参数auth_code！'); } } } } $spc0f55d->SetSpbill_create_ip($_SERVER['REMOTE_ADDR']); $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); $spa65db2 = $spc0f55d->ToXml(); $sp83735d = self::getMillisecond(); $sp31c557 = self::postXmlCurl($spa65db2, $sp59c732, false, $sp7eb8ef); $sp820aff = WxPayResults::Init($sp31c557); self::reportCostTime($sp59c732, $sp83735d, $sp820aff); return $sp820aff; } public static function reverse($spc0f55d, $sp7eb8ef = 6) { $sp59c732 = 'https://api.mch.weixin.qq.com/secapi/pay/reverse'; if (!$spc0f55d->IsOut_trade_noSet() && !$spc0f55d->IsTransaction_idSet()) { throw new WxPayException('撤销订单API接口中，参数out_trade_no和transaction_id必须填写一个！'); } $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); $spa65db2 = $spc0f55d->ToXml(); $sp83735d = self::getMillisecond(); $sp31c557 = self::postXmlCurl($spa65db2, $sp59c732, true, $sp7eb8ef); $sp820aff = WxPayResults::Init($sp31c557); self::reportCostTime($sp59c732, $sp83735d, $sp820aff); return $sp820aff; } public static function report($spc0f55d, $sp7eb8ef = 1) { $sp59c732 = 'https://api.mch.weixin.qq.com/payitil/report'; if (!$spc0f55d->IsInterface_urlSet()) { throw new WxPayException('接口URL，缺少必填参数interface_url！'); } if (!$spc0f55d->IsReturn_codeSet()) { throw new WxPayException('返回状态码，缺少必填参数return_code！'); } if (!$spc0f55d->IsResult_codeSet()) { throw new WxPayException('业务结果，缺少必填参数result_code！'); } if (!$spc0f55d->IsUser_ipSet()) { throw new WxPayException('访问接口IP，缺少必填参数user_ip！'); } if (!$spc0f55d->IsExecute_time_Set()) { throw new WxPayException('接口耗时，缺少必填参数execute_time_！'); } $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetUser_ip($_SERVER['REMOTE_ADDR']); $spc0f55d->SetTime(date('YmdHis')); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); $spa65db2 = $spc0f55d->ToXml(); $sp83735d = self::getMillisecond(); $sp31c557 = self::postXmlCurl($spa65db2, $sp59c732, false, $sp7eb8ef); return $sp31c557; } public static function bizpayurl($spc0f55d, $sp7eb8ef = 6) { if (!$spc0f55d->IsProduct_idSet()) { throw new WxPayException('生成二维码，缺少必填参数product_id！'); } $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetTime_stamp(time()); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); return $spc0f55d->GetValues(); } public static function shorturl($spc0f55d, $sp7eb8ef = 6) { $sp59c732 = 'https://api.mch.weixin.qq.com/tools/shorturl'; if (!$spc0f55d->IsLong_urlSet()) { throw new WxPayException('需要转换的URL，签名用原串，传输需URL encode！'); } $spc0f55d->SetAppid(WxPayConfig::APPID); $spc0f55d->SetMch_id(WxPayConfig::MCHID); $spc0f55d->SetNonce_str(self::getNonceStr()); $spc0f55d->SetSign(); $spa65db2 = $spc0f55d->ToXml(); $sp83735d = self::getMillisecond(); $sp31c557 = self::postXmlCurl($spa65db2, $sp59c732, false, $sp7eb8ef); $sp820aff = WxPayResults::Init($sp31c557); self::reportCostTime($sp59c732, $sp83735d, $sp820aff); return $sp820aff; } public static function notify($sp3ccd4a, &$sp3a6f3c) { $spa65db2 = file_get_contents('php://input'); try { $sp820aff = WxPayResults::Init($spa65db2); } catch (WxPayException $spbcc446) { $sp3a6f3c = $spbcc446->errorMessage(); return false; } return call_user_func($sp3ccd4a, $sp820aff); } public static function getNonceStr($sp4d881e = 32) { $spb7853e = 'abcdefghijklmnopqrstuvwxyz0123456789'; $sp512d95 = ''; for ($spf69b52 = 0; $spf69b52 < $sp4d881e; $spf69b52++) { $sp512d95 .= substr($spb7853e, mt_rand(0, strlen($spb7853e) - 1), 1); } return $sp512d95; } public static function replyNotify($spa65db2) { echo $spa65db2; } private static function reportCostTime($sp59c732, $sp83735d, $sp69c4ce) { if (WxPayConfig::REPORT_LEVENL == 0) { return; } if (WxPayConfig::REPORT_LEVENL == 1 && array_key_exists('return_code', $sp69c4ce) && $sp69c4ce['return_code'] == 'SUCCESS' && array_key_exists('result_code', $sp69c4ce) && $sp69c4ce['result_code'] == 'SUCCESS') { return; } $spa513c5 = self::getMillisecond(); $spa26b23 = new WxPayReport(); $spa26b23->SetInterface_url($sp59c732); $spa26b23->SetExecute_time_($spa513c5 - $sp83735d); if (array_key_exists('return_code', $sp69c4ce)) { $spa26b23->SetReturn_code($sp69c4ce['return_code']); } if (array_key_exists('return_msg', $sp69c4ce)) { $spa26b23->SetReturn_msg($sp69c4ce['return_msg']); } if (array_key_exists('result_code', $sp69c4ce)) { $spa26b23->SetResult_code($sp69c4ce['result_code']); } if (array_key_exists('err_code', $sp69c4ce)) { $spa26b23->SetErr_code($sp69c4ce['err_code']); } if (array_key_exists('err_code_des', $sp69c4ce)) { $spa26b23->SetErr_code_des($sp69c4ce['err_code_des']); } if (array_key_exists('out_trade_no', $sp69c4ce)) { $spa26b23->SetOut_trade_no($sp69c4ce['out_trade_no']); } if (array_key_exists('device_info', $sp69c4ce)) { $spa26b23->SetDevice_info($sp69c4ce['device_info']); } try { self::report($spa26b23); } catch (WxPayException $spbcc446) { } } private static function postXmlCurl($spa65db2, $sp59c732, $spc58885 = false, $sp94e2b0 = 30) { $sp72f257 = curl_init(); curl_setopt($sp72f257, CURLOPT_TIMEOUT, $sp94e2b0); if (WxPayConfig::CURL_PROXY_HOST != '0.0.0.0' && WxPayConfig::CURL_PROXY_PORT != 0) { curl_setopt($sp72f257, CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST); curl_setopt($sp72f257, CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT); } curl_setopt($sp72f257, CURLOPT_URL, $sp59c732); curl_setopt($sp72f257, CURLOPT_SSL_VERIFYPEER, TRUE); curl_setopt($sp72f257, CURLOPT_SSL_VERIFYHOST, 2); curl_setopt($sp72f257, CURLOPT_HEADER, FALSE); curl_setopt($sp72f257, CURLOPT_RETURNTRANSFER, TRUE); if ($spc58885 == true) { curl_setopt($sp72f257, CURLOPT_SSLCERTTYPE, 'PEM'); curl_setopt($sp72f257, CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH); curl_setopt($sp72f257, CURLOPT_SSLKEYTYPE, 'PEM'); curl_setopt($sp72f257, CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH); } else { curl_setopt($sp72f257, CURLOPT_SSL_VERIFYPEER, false); } curl_setopt($sp72f257, CURLOPT_POST, TRUE); curl_setopt($sp72f257, CURLOPT_POSTFIELDS, $spa65db2); $sp69c4ce = curl_exec($sp72f257); if ($sp69c4ce) { curl_close($sp72f257); return $sp69c4ce; } else { $sp827b09 = curl_errno($sp72f257); \WxLog::error('WxPat.Api.postXmlCurl Error: ' . curl_error($sp72f257)); curl_close($sp72f257); throw new WxPayException("curl出错，错误码: {$sp827b09}"); } } private static function getMillisecond() { $spaae7dc = explode(' ', microtime()); $spaae7dc = $spaae7dc[1] . $spaae7dc[0] * 1000; $sp3ab8ea = explode('.', $spaae7dc); $spaae7dc = $sp3ab8ea[0]; return $spaae7dc; } }