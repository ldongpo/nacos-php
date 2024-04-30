# grpc 操作说明

## 准备条件
- grpc和protobuf拓展
- protobuf命令行工具

###  php安装grpc和protobuf拓展
```
pecl install grpc
pecl install protobuf
```

### 安装protobuf命令行工具
##### macos
`brew install protobuf`
##### linux
`apt install protobuf`

### 编译安装protoc的grpc_php_plugin插件
```
git clone https://github.com/grpc/grpc.git
cd grpc
git submodule update --init
mkdir -p cmake/build
cd cmake/build
cmake ../..
make protoc grpc_php_plugin
```
> 以下代码示例来自
> https://github.com/grpc/grpc/blob/master/examples/php/
# 编写 proto文件：helloWorld.proto
```
syntax = "proto3";

option java_multiple_files = true;
option java_package = "io.grpc.examples.helloworld";
option java_outer_classname = "HelloWorldProto";
option objc_class_prefix = "HLW";

package helloworld;

// The greeting service definition.
service Greeter {
  // Sends a greeting
  rpc SayHello (HelloRequest) returns (HelloReply) {}
}

// The request message containing the user's name.
message HelloRequest {
  string name = 1;
}

// The response message containing the greetings
message HelloReply {
  string message = 1;
}
```

## 启动一个服务端

### 服务端代码
```
require dirname(__FILE__) . '/vendor/autoload.php';

class Greeter extends Helloworld\GreeterStub
{
    public function SayHello(
        \Helloworld\HelloRequest $request,
        \Grpc\ServerContext $serverContext
    ): ?\Helloworld\HelloReply {
        $name = $request->getName();
        echo 'Received request: ' . $name . PHP_EOL;
        $response = new \Helloworld\HelloReply();
        $response->setMessage("Hello " . $name);
        return $response;
    }
}

$port = 50051;
$server = new \Grpc\RpcServer();
$server->addHttp2Port('0.0.0.0:'.$port);
$server->handle(new Greeter());
echo 'Listening on port :' . $port . PHP_EOL;
$server->run();
```

### 客户端代码
```
require dirname(__FILE__).'/vendor/autoload.php';

function greet($hostname, $name)
{
    $client = new Helloworld\GreeterClient($hostname, [
        'credentials' => Grpc\ChannelCredentials::createInsecure(),
    ]);
    $request = new Helloworld\HelloRequest();
    $request->setName($name);
    list($response, $status) = $client->SayHello($request)->wait();
    if ($status->code !== Grpc\STATUS_OK) {
        echo "ERROR: " . $status->code . ", " . $status->details . PHP_EOL;
        exit(1);
    }
    echo $response->getMessage() . PHP_EOL;
}

$name = !empty($argv[1]) ? $argv[1] : 'world';
$hostname = !empty($argv[2]) ? $argv[2] : 'localhost:50051';
greet($hostname, $name);
```

