<?php
namespace LaneWeChat\Core;
/**
 *
 * 错误提示类
 */
class Msg {
	/**
	 * 返回错误信息 ...
	 * @param int $code 错误码
	 * @param string $errorMsg 错误信息
	 * @return Ambigous <multitype:unknown , multitype:, boolean>
	 */
	public static function returnErrMsg($code,  $errorMsg = null) {
		$returnMsg = array('error_code' => $code);
		if (!empty($errorMsg)) {
			$returnMsg['custom_msg'] = $errorMsg;
		}
		$result = array();
		$result['statusCode'] = 300; 
		$result['message'] = '出错啦！'.$returnMsg['custom_msg'];
        //$returnMsg['custom_msg'] = '出错啦！'.$returnMsg['custom_msg'];
        
        exit( json_encode($result) );
        //exit($returnMsg['custom_msg']);
	}
}
?>
