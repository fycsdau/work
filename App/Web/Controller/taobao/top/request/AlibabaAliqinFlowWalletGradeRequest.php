<?php
/**
 * TOP API: alibaba.aliqin.flow.wallet.grade request
 * 
 * @author auto create
 * @since 1.0, 2015.11.26
 */
class AlibabaAliqinFlowWalletGradeRequest
{
	/** 
	 * 手机号码
	 **/
	private $phoneNum;
	
	private $apiParas = array();
	
	public function setPhoneNum($phoneNum)
	{
		$this->phoneNum = $phoneNum;
		$this->apiParas["phone_num"] = $phoneNum;
	}

	public function getPhoneNum()
	{
		return $this->phoneNum;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.flow.wallet.grade";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->phoneNum,"phoneNum");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
