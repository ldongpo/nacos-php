<?php
require_once dirname(__DIR__, 2) . "/vendor/autoload.php";
use nacosphp\config\NacosConfig;
use nacosphp\naming\NamingClient;

NacosConfig::setHost("http://10.10.153.86:8848"); // 配置中心地址

$naming = new NamingClient(
    "php_test_svc_01",
    "",
    0,
    "4f2a4e22-7668-4a8e-8b05-bcd3a5d925c2",
    "",
    false,
    "",
    ""
);
// 查询指定服务的实例列表
// 查询服务列表，暂时不能按ip和端口条件查询，全部返回（可以按健康状态作为查询条件）

try {
    $res = $naming->list();
    print_r($res->getBody()->getContents());
}catch (\nacosphp\exception\ResponseCodeErrorException $e) {
    echo $e->getMessage();
}