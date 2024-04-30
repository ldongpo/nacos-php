<?php
namespace nacosphp\naming;
use nacosphp\config\NamingConfig;
use nacosphp\request\naming\DeleteInstanceNaming;
use nacosphp\request\naming\RegisterInstanceNaming;
use nacosphp\request\naming\ListInstanceNaming;
use nacosphp\exception\ResponseCodeErrorException;

/**
 * 服务发现客户端
 */
class NamingClient
{
    /**
     * 初始化
     * @param $serviceName
     * @param $ip
     * @param $port
     * @param string $namespaceId
     * @param string $weight
     * @param bool $ephemeral
     */
    public function __construct($serviceName, $ip, $port, string $namespaceId = "", string $weight = "", bool $ephemeral = true)
    {
        NamingConfig::setServiceName($serviceName);
        NamingConfig::setIp($ip);
        NamingConfig::setPort($port);
        NamingConfig::setNamespaceId($namespaceId);
        NamingConfig::setWeight($weight);
        NamingConfig::setEphemeral($ephemeral);
    }


    /**
     * Notes: 注册实例
     * User: mail@liangdongpo.com
     * Date: 2024/4/28
     * Time:18:25
     * @param bool $enable
     * @param bool $healthy
     * @param string $clusterName
     * @param string $groupName
     * @param string $metadata
     * @return \Psr\Http\Message\ResponseInterface
     * @throws ResponseCodeErrorException
     */
    public function register(bool $enable = true, bool $healthy = true, string $clusterName = "", string $groupName = "", string $metadata = "{}"): \Psr\Http\Message\ResponseInterface
    {
        $registerInstanceDiscovery = new RegisterInstanceNaming();
        $registerInstanceDiscovery->setServiceName(NamingConfig::getServiceName());
        $registerInstanceDiscovery->setIp( NamingConfig::getIp());
        $registerInstanceDiscovery->setPort(NamingConfig::getPort());
        $registerInstanceDiscovery->setNamespaceId(NamingConfig::getNamespaceId());
        $registerInstanceDiscovery->setWeight(NamingConfig::getWeight());
        $registerInstanceDiscovery->setEphemeral(NamingConfig::getEphemeral());
        $registerInstanceDiscovery->setGroupName($groupName);
        $registerInstanceDiscovery->setEnable($enable);
        $registerInstanceDiscovery->setHealthy($healthy);
        $registerInstanceDiscovery->setMetadata($metadata);
        $registerInstanceDiscovery->setClusterName($clusterName);
        try {
            return $registerInstanceDiscovery->doExecute();
        } catch (
            \GuzzleHttp\Exception\GuzzleException|
            \nacosphp\exception\RequestMethodRequiredException|
            \nacosphp\exception\RequestUriRequiredException|
        ResponseCodeErrorException $e) {
            throw new ResponseCodeErrorException($e->getCode(),$e->getMessage());
        }
    }


    /**
     * Notes: 注销实例
     * User: mail@liangdongpo.com
     * Date: 2024/4/28
     * Time:18:25
     * @param string $namespaceId
     * @param string $clusterName
     * @return \Psr\Http\Message\ResponseInterface
     * @throws ResponseCodeErrorException
     */
    function delete(string $namespaceId = "", string $clusterName = "",$groupName = ""): \Psr\Http\Message\ResponseInterface
    {
        $deleteInstanceNaming = new DeleteInstanceNaming();
        $deleteInstanceNaming->setServiceName(NamingConfig::getServiceName());
        $deleteInstanceNaming->setIp(NamingConfig::getIp());
        $deleteInstanceNaming->setPort(NamingConfig::getPort());
        $deleteInstanceNaming->setNamespaceId($namespaceId != "" ? $namespaceId : NamingConfig::getNamespaceId());
        $deleteInstanceNaming->setClusterName($clusterName);
        $deleteInstanceNaming->setGroupName($groupName);
        $deleteInstanceNaming->setEphemeral(NamingConfig::getEphemeral());
        try {
            return $deleteInstanceNaming->doExecute();
        } catch (
        \GuzzleHttp\Exception\GuzzleException|
        \nacosphp\exception\RequestMethodRequiredException|
        \nacosphp\exception\RequestUriRequiredException|
        ResponseCodeErrorException $e) {
            throw new ResponseCodeErrorException($e->getCode(),$e->getMessage());
        }
    }

    /**
     * Notes: 查询指定服务的实例列表; 查询服务列表，暂时不能按ip和端口条件查询，全部返回（可以按健康状态作为查询条件）
     * User: mail@liangdongpo.com
     * Date: 2024/4/28
     * Time:18:54
     * @param bool $healthyOnly
     * @param string $namespaceId
     * @param string $clusterName
     * @param string $groupName
     * @return \Psr\Http\Message\ResponseInterface
     * @throws ResponseCodeErrorException
     */
    function list(bool $healthyOnly = false, string $namespaceId = "", string $clusterName = "", string $groupName = ""): \Psr\Http\Message\ResponseInterface{
        $listInstanceNaming = new ListInstanceNaming();
        $listInstanceNaming->setServiceName(NamingConfig::getServiceName());
        $listInstanceNaming->setNamespaceId($namespaceId != "" ? $namespaceId : NamingConfig::getNamespaceId());
        $listInstanceNaming->setClusterName($clusterName);
        $listInstanceNaming->setGroupName($groupName);
        $listInstanceNaming->setHealthyOnly($healthyOnly);
        $listInstanceNaming->setEphemeral(NamingConfig::getEphemeral());
        try {
            return $listInstanceNaming->doExecute();
        } catch (
        \GuzzleHttp\Exception\GuzzleException|
        \nacosphp\exception\RequestMethodRequiredException|
        \nacosphp\exception\RequestUriRequiredException|
        ResponseCodeErrorException $e) {
            throw new ResponseCodeErrorException($e->getCode(),$e->getMessage());
        }
    }
}