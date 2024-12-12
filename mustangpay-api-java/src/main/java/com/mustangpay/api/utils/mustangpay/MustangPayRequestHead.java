package com.mustangpay.api.utils.mustangpay;
/**
 * @Author: hyssop
 * @Date: 08/27/2024
 */
public class MustangPayRequestHead {
    private String version;

    private String reqTime;//发送报文时间戳

    private String reqMsgId;//报文编号



    public String getVersion() {
        return version;
    }

    public void setVersion(String version) {
        this.version = version;
    }


    public String getReqTime() {
        return reqTime;
    }

    public void setReqTime(String reqTime) {
        this.reqTime = reqTime;
    }

    public String getReqMsgId() {
        return reqMsgId;
    }

    public void setReqMsgId(String reqMsgId) {
        this.reqMsgId = reqMsgId;
    }
}
