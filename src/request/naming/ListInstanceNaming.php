<?php

namespace nacosphp\request\naming;
/**
 * 查询指定服务的实例列表
 */
class ListInstanceNaming extends NamingRequest
{
    protected string $uri = "/nacos/v2/ns/instance/list";
    protected string $method = "GET";
    private string $serviceName; //服务名
    private string $groupName; //分组名，默认为DEFAULT_GROUP
//    private string $ip; //服务实例IP
//    private int $port; //服务实例port
    private string $clusterName;//集群名
    private string $namespaceId;//命名空间ID
    private bool $ephemeral;//是否临时实例
    private bool $healthyOnly;//是否只获取健康实例，默认为false
    public function setServiceName(string $serviceName): void{
        $this->serviceName = $serviceName;
    }
    public function getServiceName(): string{
        return $this->serviceName;
    }
//    public function setIp(string $ip): void{
//        $this->ip = $ip;
//    }
//    public function getIp(): string{
//        return $this->ip;
//    }
//    public function setPort(int $port): void{
//        $this->port = $port;
//    }
//    public function getPort(): int{
//        return $this->port;
//    }
    public function setClusterName(string $clusterName): void{
        $this->clusterName = $clusterName;
    }
    public function getClusterName(): string{
        return $this->clusterName;
    }
    public function setNamespaceId(string $namespaceId): void{
        $this->namespaceId = $namespaceId;
    }
    public function getNamespaceId(): string{
        return $this->namespaceId;
    }
    public function setEphemeral(bool $ephemeral): void{
        $this->ephemeral = $ephemeral;
    }
    public function getEphemeral(): bool{
        return $this->ephemeral;
    }
    public function setGroupName(string $groupName): void{
        $this->groupName = $groupName;
    }
    public function getGroupName(): string{
        return $this->groupName;
    }
    public function setHealthyOnly(bool $healthyOnly): void{
        $this->healthyOnly = $healthyOnly;
    }
    public function getHealthyOnly(): bool{
        return $this->healthyOnly;
    }

    // 公共方法用于获取私有或受保护属性的值
    public function getPropertyValue($propertyName) {
        if (property_exists($this, $propertyName)) {
            return $this->$propertyName;
        }
        return null; // 或抛出异常
    }
}