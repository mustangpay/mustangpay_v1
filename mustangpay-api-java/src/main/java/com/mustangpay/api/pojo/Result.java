package com.mustangpay.api.pojo;

import lombok.Data;

import java.io.Serializable;

@Data
public class Result<T> implements Serializable {

    private String code;

    private String msg;

    private T data;

    public Result(){
    }

    public Result(String code, String msg){
        this.code = code;
        this.msg = msg;
    }
    public static Result fail(String msg) {
        Result result = new Result<>();
        result.setCode("000001");
        result.setMsg(msg);
        return result;
    }
    public static <T> Result<T> success(T data) {
        Result<T> result = new Result<>();
        result.setCode("000000");
        result.setMsg("success");
        result.setData(data);
        return result;
    }
}
