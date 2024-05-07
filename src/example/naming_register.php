<?php

require_once dirname(__DIR__, 2) . "/vendor/autoload.php";
use nacosphp\config\NacosConfig;
use nacosphp\naming\NamingClient;

NacosConfig::setHost("http://10.10.153.86:8848"); // 配置中心地址

$naming = new NamingClient(
    "php_test_svc_02",
    "127.0.0.1",
    "50052",
    "4f2a4e22-7668-4a8e-8b05-bcd3a5d925c2",
    "",
    false,
    "",
    ""
);
// 注册实例
try {
    $res = $naming->register();
    print_r($res->getBody()->getContents());
} catch (\nacosphp\exception\ResponseCodeErrorException $e) {
    echo $e->getMessage();
}