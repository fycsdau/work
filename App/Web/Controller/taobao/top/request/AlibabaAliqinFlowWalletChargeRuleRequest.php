<?php
/**
 * TOP API: alibaba.aliqin.flow.wallet.charge.rule request
 * 
 * @author auto create
 * @since 1.0, 2015.12.24
 */
class AlibabaAliqinFlowWalletChargeRuleRequest
{
	/** 
	 * 渠道id（运营分配）
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
	 * 号码
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
		return "alibaba.aliqin.flow.wallet.charge.rule";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
