<?php

$ch = curl_init();
$url = "https://api.godaddy.com/v1/domains/available?domain=fergferthtyjhuy.com&checkType=FAST&forTransfer=false";

        $header=[
            'accept: application/json',
          'Authorization: sso-key dLiW5qgMZd7f_MtT8PtBEpqKvoYywPW4kTJ:MtTCeRm6o8NZ3b1fz5Y8QY'
        ];
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//绕过ssl验证
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

//执行并获取HTML文档内容
$output = curl_exec($ch);
//释放curl句柄
curl_close($ch);

var_dump(json_decode($output,true));
