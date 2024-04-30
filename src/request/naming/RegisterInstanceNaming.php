<?php

namespace nacosphp\request\naming;

/**
 * 注册实例
 */
class RegisterInstanceNaming extends NamingRequest
{
    protected string $uri = "/nacos/v2/ns/instance";
    protected string $method = "POST";
    private string $namespaceId;//命名空间
    private string $groupName;//分组名
    private string $serviceName;//服务名
    private string $ip; //ip
    private int $port;//端口
    private string $clusterName;//集群名
    private string $weight;//权重
    private bool $enable;//是否启用
    private bool $healthy;//是否健康
    private string $metadata;//元数据
    private bool $ephemeral;//是否临时实例

    public function setNamespaceId(string $namespaceId): void{
        $this->namespaceId = $namespaceId;
    }
    public function getNamespaceId(): string{
        return $this->namespaceId;
    }
    public function setGroupName(string $groupName): void{
        $this->groupName = $groupName;
    }
    public function getGroupName(): string{
        return $this->groupName;
    }
    public function setServiceName(string $serviceName): void{
        $this->serviceName = $serviceName;
    }
    public function getServiceName(): string{
        return $this->serviceName;
    }
    public function setIp(string $ip): void{
        $this->ip = $ip;
    }
    public function getIp(): string{
        return $this->ip;
    }
    public function setPort(int $port): void{
        $this->port = $port;
    }
    public function getPort(): int{
        return $this->port;
    }
    public function setClusterName(string $clusterName): void{
        $this->clusterName = $clusterName;
    }
    public function getClusterName(): string{
        return $this->clusterName;
    }
    public function setWeight(string $weight): void{
        $this->weight = $weight;
    }
    public function getWeight(): string{
        return $this->weight;
    }
    public function setEnable(bool $enable): void{
        $this->enable = $enable;
    }
    public function getEnable(): bool{
        return $this->enable;
    }
    public function setHealthy(bool $healthy): void{
        $this->healthy = $healthy;
    }
    public function getHealthy(): bool{
        return $this->healthy;
    }
    public function setMetadata(string $metadata): void{
        $this->metadata = $metadata;
    }
    public function getMetadata(): string{
        return $this->metadata;
    }
    public function setEphemeral(bool $ephemeral): void{
        $this->ephemeral = $ephemeral;
    }
    public function getEphemeral(): bool{
        return $this->ephemeral;
    }
    // 公共方法用于获取私有或受保护属性的值
    public function getPropertyValue($propertyName) {
        if (property_exists($this, $propertyName)) {
            return $this->$propertyName;
        }
        return null; // 或抛出异常
    }
}