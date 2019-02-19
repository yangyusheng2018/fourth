<?php
ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
$data=[
    'ApiKey'=>'B9X2G3Y5D3D8U1S3F1',
    'Password'=>'qwer1234',
    'Domain'=>'example.com'
    ];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://testapi.internet.bs/Domain/Check');
curl_setopt($curl, CURLOPT_HEADER, 1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
$data1 = curl_exec($curl);
//关闭URL请求
curl_close($curl);
//显示获得的数据
print_r($data1);
?>