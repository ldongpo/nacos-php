<?php

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

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
$port = !empty($argv[1]) ? $argv[1] : '50051';
//$port = 50051;
$server = new \Grpc\RpcServer();
$server->addHttp2Port('0.0.0.0:'.$port);
$server->handle(new Greeter());
echo 'Listening on port :' . $port . PHP_EOL;
$server->run();