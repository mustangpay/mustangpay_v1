# x-sure cms-yii2-base

# 环境
* php7.4

# 伪静态配置
```
location / {
    try_files $uri $uri/ /index.php$is_args$args;
}
```

# 访问路径
-预下单接口  4种测试
/webpayment/cashier/preorder
/webpayment/cashier/preorder2
/webpayment/cashier/preorder3
/webpayment/cashier/preorder4
/webpayment/cashier/refund# nanfeiapi

DDD