<?php
/**
 * TOP API: alibaba.aliqin.flow.wallet.query.charge request
 * 
 * @author auto create
 * @since 1.0, 2015.11.26
 */
class AlibabaAliqinFlowWalletQueryChargeRequest
{
	/** 
	 * 渠道id
	 **/
	private $channelId;
	
	/** 
	 * 唯一流水号
	 **/
	private $outRechargeId;
	
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

	public function setOutRechargeId($outRechargeId)
	{
		$this->outRechargeId = $outRechargeId;
		$this->apiParas["out_recharge_id"] = $outRechargeId;
	}

	public function getOutRechargeId()
	{
		return $this->outRechargeId;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.flow.wallet.query.charge";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->channelId,"channelId");
		RequestCheckUtil::checkNotNull($this->outRechargeId,"outRechargeId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
