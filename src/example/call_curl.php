<?php

require_once dirname(__DIR__, 2) . "/vendor/autoload.php";

use nacosphp\call\CallServiceWith;
use nacosphp\naming\NamingClient;
/**
 * 这是一个curl HTTP 协议的请求示例
 * 先获取健康的实例，然后随机选择一个实例，最后调用服务
 */
class callService extends CallServiceWith
{
    public function __construct(string $nsHost,NamingClient $naming){
        parent::__construct($nsHost, $naming);
    }

    /**
     * Notes: 子类实现具体逻辑的方法
     * User: mail@liangdongpo.com
     * Date: 2024/4/30
     * Time:17:19
     * @param $ip
     * @param $port
     * @param $data
     * @return string|bool
     */
    public function callback ($ip,$port,$data): string|bool
    {
        /**
         * 这个方法的逻辑只是一个栗子
         */
        $url = "http://" . $ip . ":" . $port . "/cloud-web-ali/auth/login";
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_TIMEOUT => 10
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
// 初始化请求实例
// NamingClient 中 ip 和 port 不是必须的
$call = new callService(
    "http://10.10.153.86:8848",
    new NamingClient(
        "php_test_svc_01",
        "",
        "0",
        "4f2a4e22-7668-4a8e-8b05-bcd3a5d925c2",
        "",
        false
    )
);
// 需要传的参数
$data = [
    'username' => 'test',
    'password' => '1111',
];
try {
    // 调用服务 用 retry 方法，有重试机制
    $res = $call->callServiceWithRetry($data);
    var_dump($res);
} catch (\nacosphp\exception\ResponseCodeErrorException $e) {
    echo $e->getMessage();
}
