<?php
namespace nacosphp\util;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use nacosphp\config\nacosConfig;

class httpCurl
{
    // 超时时间
    public static int $timeout = 20;
    /**
     * Notes: 请求类
     * User: mail@liangdongpo.com
     * Date: 2024/4/26
     * Time:15:46
     * @param string $method
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     */
    public static function execute(string $method, string $url, array $data = array(), array $headers = []): \Psr\Http\Message\ResponseInterface
    {
        $client = self::getGuzzle();
        if (count($headers) > 0) {
            $options['headers'] = $headers;
        }
        switch ($method) {
            case 'POST':
                //$requestMethod = 'POST';
                $options['form_params'] = $data;
                return $client->post( $url, $options);
            case 'GET':
                //$requestMethod = 'GET';
                $options['query'] = $data;
                return $client->get( $url, $options);
            case 'DELETE':
                //$requestMethod = 'DELETE';
                $options['form_params'] = $data;
                return $client->delete( $url, $options);
            case 'JSON':
                //$requestMethod = 'POST';
                $options['json'] = $data;
                return $client->post( $url, $options);
                break;
            default:
                //$requestMethod = "GET";
                $options['query'] = $data;
                return $client->get( $url, $options);
        }

    }

    /**
     * Notes: 创建请求类，并设置域名等
     * User: mail@liangdongpo.com
     * Date: 2024/4/28
     * Time:15:34
     * @return Client|mixed
     */
    public static function getGuzzle(): mixed
    {
        static $guzzle;
        if ($guzzle == null) {
            $guzzle = new Client([
                'timeout'=>self::$timeout,
                'base_uri' => NacosConfig::getHost(),
            ]);
        }
        return $guzzle;
    }
}