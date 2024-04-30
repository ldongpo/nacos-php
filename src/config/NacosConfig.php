<?php
namespace nacosphp\config;
class NacosConfig
{
    /**
     * nacos 服务地址
     * @var string
     */
    private static string $host;
    public static function setHost(string $host): void{
        self::$host = $host;
    }
    public static function getHost(): string{
        return self::$host;
    }
}