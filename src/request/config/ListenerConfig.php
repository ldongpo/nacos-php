<?php
/**
 * 监听配置
 */
namespace nacosphp\curl\config;

class ListenerConfig extends Config
{
    protected string $uri = "/nacos/v2/cs/config";
    protected string $method = "POST";
}