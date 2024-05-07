<?php

require_once dirname(__DIR__, 2) . "/vendor/autoload.php";


use nacosphp\call\CallServiceWith;
use nacosphp\naming\NamingClient;
/**
 * 这是一个 grpc 协议的请求示例
 * 先获取健康的实例，然后随机选择一个实例，最后调用服务
 */
class callGrpcService extends CallServiceWith
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
     * @return mixed
     */
    public function callback ($ip,$port,$data): mixed
    {
        /**
         * 这只是一个 调用grpc 的栗子
         * 前提注册中心要注册服务的实例
         * example\grpc\helloworld_server.php 这是一个php 版本的grpc服务代码，可以启用做相关测试
         */
        $client = new Helloworld\GreeterClient($ip.":" . $port, [
            'credentials' => Grpc\ChannelCredentials::createInsecure(),
        ]);
        $request = new Helloworld\HelloRequest();
        $request->setName($data["name"]);
        list($response, $status) = $client->SayHello($request)->wait();
        if ($status->code !== Grpc\STATUS_OK) {
            echo "ERROR: " . $status->code . ", " . $status->details . PHP_EOL;
            exit(1);
        }
        return $response->getMessage();
    }
}
// 初始化请求实例
// NamingClient 中 ip 和 port 不是必须的
$call = new callGrpcService(
    "http://10.10.153.86:8848",
    new NamingClient(
        "php_test_svc_02",
        "",
        "0",
        "4f2a4e22-7668-4a8e-8b05-bcd3a5d925c2",
        "",
        false,
        "",
        ""
    )
);
//设置重试次数和重试间隔，非必选项
$call::setMaxRetries(2);
$call::setRetryDelay(3000);

// 需要传的参数
$data = [
    'name' => 'world',
];
try {
    // 调用服务 用 retry 方法，有重试机制
    $res = $call->callServiceWithRetry($data);
    var_dump($res);
} catch (\nacosphp\exception\ResponseCodeErrorException $e) {
    echo $e->getMessage();
}
