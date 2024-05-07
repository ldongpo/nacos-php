# PHP  nacos（v2） 客户端
## 功能介绍
### 服务注册与发现
1. 注册实例
1. 注销实例
1. 查询指定服务下的实例列表
> 更多代码示例参考 src/example 目录下代码
### 集成客户端调用功能
> 实现简单的负载均衡；客户端调用服务端逻辑


## 快速开始
### 使用方式

`composer require ldongpo/nacos-php`
### 注册实例
```
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
```
### 注销实例
```
use nacosphp\config\NacosConfig;
use nacosphp\naming\NamingClient;

NacosConfig::setHost("http://10.10.153.86:8848"); // 配置中心地址

$naming = new NamingClient(
    "php_test_svc_01",
    "10.99.57.121",
    "9999",
    "4f2a4e22-7668-4a8e-8b05-bcd3a5d925c2",
    "",
    false,
    "",
    ""
);

// 删除实例

try {
    $res = $naming->delete();
    print_r($res->getBody()->getContents());
}catch (\nacosphp\exception\ResponseCodeErrorException $e) {
    echo $e->getMessage();
}
```
### 查询指定服务下的实例列表
```

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
```

### grpc 模式 客户端请求服务端

```

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
    "http://10.10.10.2:8848",
    new NamingClient(
        "php_test_svc_02",
        "",
        "0",
        "4f2a4e22-7668-4a8e-8b05-bcd3add5c2",
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
```

### curl (http1) 模式 客户端请求服务端

```

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

```