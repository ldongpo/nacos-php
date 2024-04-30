<?php

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

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
$hostname = !empty($argv[2]) ? $argv[2] : '127.0.0.1:50053';
greet($hostname, $name);
