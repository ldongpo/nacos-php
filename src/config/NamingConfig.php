<?php
namespace nacosphp\config;
class NamingConfig
{
    /**
     * 服务名
     * @var string
     */
    private static string $serviceName;
    /**
     * ip
     * @var string
     */
    private static string $ip;
    /**
     * 端口
     * @var int
     */
    private static int $port;
    /**
     * 命名空间
     * @var string
     */
    private static string $namespaceId = "";
    /**
     * 示注册的实例是临时实例还是持久化实例, true临时，false持久化
     * @var string
     */
    private static string $weight = "";
    /**
     * 集群名
     * @var bool
     */
    private static bool $ephemeral = true;

    /**
     * 集群名称，默认为DEFAULT
     * @param string $clusterName
     */
    private static string $clusterName = "";

    /**
     * 分组名，默认为DEFAULT_GROUP
     * @param string $groupName
     */
    private static string $groupName = "";
    /**
     * @param bool $ephemeral
     */
    public static function setEphemeral(bool $ephemeral): void
    {
        self::$ephemeral = $ephemeral;
    }
    public static function getEphemeral(): bool
    {
        return self::$ephemeral;
    }
    public static function getServiceName(): string
    {
        return self::$serviceName;
    }
    public static function setServiceName(string $serviceName): void
    {
        self::$serviceName = $serviceName;
    }
    public static function getIp(): string
    {
        return self::$ip;
    }
    public static function setIp(string $ip): void
    {
        self::$ip = $ip;
    }
    public static function getPort(): int
    {
        return self::$port;
    }
    public static function setPort(int $port): void
    {
        self::$port = $port;
    }
    public static function getNamespaceId(): string
    {
        return self::$namespaceId;
    }
    public static function setNamespaceId(string $namespaceId): void
    {
        self::$namespaceId = $namespaceId;
    }
    public static function getWeight(): string
    {
        return self::$weight;
    }
    public static function setWeight(string $weight): void
    {
        self::$weight = $weight;
    }
    public static function getClusterName(): string
    {
        return self::$clusterName;
    }
    public static function setClusterName(string $clusterName): void
    {
        self::$clusterName = $clusterName;
    }
    public static function getGroupName(): string
    {
        return self::$groupName;
    }
    public static function setGroupName(string $groupName): void
    {
        self::$groupName = $groupName;
    }
}