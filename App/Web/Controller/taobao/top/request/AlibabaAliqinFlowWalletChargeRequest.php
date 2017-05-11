<?php
/**
 * TOP API: alibaba.aliqin.flow.wallet.charge request
 * 
 * @author auto create
 * @since 1.0, 2015.11.26
 */
class AlibabaAliqinFlowWalletChargeRequest
{
	/** 
	 * 渠道id
	 **/
	private $channelId;
	
	/** 
	 * 档位id
	 **/
	private $gradeId;
	
	/** 
	 * 唯一流水号
	 **/
	private $outRechargeId;
	
	/** 
	 * 充值号码
	 **/
	private $phoneNum;
	
	/** 
	 * 原因
	 **/
	private $reason;
	
	private $apiParas = array();
	
	public function setChannelId($channelId)
	{
		$this->channelId = $channelId;
		$this->apiParas["channel_id"] = $channelId;
	}

	public function getChannelId()
	{
		return $this->channelId;
	}

	public function setGradeId($gradeId)
	{
		$this->gradeId = $gradeId;
		$this->apiParas["grade_id"] = $gradeId;
	}

	public function getGradeId()
	{
		return $this->gradeId;
	}

	public function setOutRechargeId($outRechargeId)
	{
		$this->outRechargeId = $outRechargeId;
		$this->apiParas["out_recharge_id"] = $outRechargeId;
	}

	public function getOutRechargeId()
	{
		return $this->outRechargeId;
	}

	public function setPhoneNum($phoneNum)
	{
		$this->phoneNum = $phoneNum;
		$this->apiParas["phone_num"] = $phoneNum;
	}

	public function getPhoneNum()
	{
		return $this->phoneNum;
	}

	public function setReason($reason)
	{
		$this->reason = $reason;
		$this->apiParas["reason"] = $reason;
	}

	public function getReason()
	{
		return $this->reason;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.flow.wallet.charge";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->channelId,"channelId");
		RequestCheckUtil::checkNotNull($this->gradeId,"gradeId");
		RequestCheckUtil::checkNotNull($this->outRechargeId,"outRechargeId");
		RequestCheckUtil::checkNotNull($this->phoneNum,"phoneNum");
		RequestCheckUtil::checkNotNull($this->reason,"reason");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
