<?php
namespace nacosphp\naming;
use nacosphp\config\NamingConfig;
use nacosphp\request\naming\DeleteInstanceNaming;
use nacosphp\request\naming\RegisterInstanceNaming;
use nacosphp\request\naming\ListInstanceNaming;
use nacosphp\exception\ResponseCodeErrorException;
use Psr\Http\Message\ResponseInterface;

/**
 * 服务发现客户端
 */
class NamingClient
{
    /**
     * 初始化
     * @param string $serviceName
     * @param string $ip
     * @param int $port
     * @param string $namespaceId
     * @param string $weight
     * @param bool $ephemeral
     * @param string $clusterName
     * @param string $groupName
     */
    public function __construct(string $serviceName, string $ip, int $port, string $namespaceId = "", string $weight = "", bool $ephemeral = true, string $clusterName = "", string $groupName = "")
    {
        NamingConfig::setServiceName($serviceName);
        NamingConfig::setIp($ip);
        NamingConfig::setPort($port);
        NamingConfig::setNamespaceId($namespaceId);
        NamingConfig::setWeight($weight);
        NamingConfig::setEphemeral($ephemeral);
        NamingConfig::setClusterName($clusterName);
        NamingConfig::setGroupName($groupName);
    }


    /**
     * Notes: 注册实例
     * User: mail@liangdongpo.com
     * Date: 2024/4/28
     * Time:18:25
     * @param bool $enable
     * @param bool $healthy
     * @param string $clusterName  集群名称，默认为DEFAULT
     * @param string $groupName 分组名，默认为DEFAULT_GROUP
     * @param string $metadata
     * @return ResponseInterface
     * @throws ResponseCodeErrorException
     */
    public function register(bool $enable = true, bool $healthy = true, string $clusterName = "", string $groupName = "", string $metadata = "{}"): ResponseInterface
    {
        // 分组名是空的情况下，取初始化时的分组名
        if($groupName == ""){
            $groupName = NamingConfig::getGroupName();
        }
        // 集群名是空的情况下，取初始化时的集群名
        if($clusterName == ""){
            $clusterName = NamingConfig::getClusterName();
        }
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
     * @param string $clusterName 集群名称，默认为DEFAULT
     * @param string $groupName 分组名，默认为DEFAULT_GROUP
     * @return ResponseInterface
     * @throws ResponseCodeErrorException
     */
    function delete(string $namespaceId = "", string $clusterName = "", string $groupName = ""): ResponseInterface
    {
        // 分组名是空的情况下，取初始化时的分组名
        if($groupName == ""){
            $groupName = NamingConfig::getGroupName();
        }
        // 集群名是空的情况下，取初始化时的集群名
        if($clusterName == ""){
            $clusterName = NamingConfig::getClusterName();
        }
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
     * @param string $clusterName 集群名称，默认为DEFAULT
     * @param string $groupName 分组名，默认为DEFAULT_GROUP
     * @return ResponseInterface
     * @throws ResponseCodeErrorException
     */
    function list(bool $healthyOnly = false, string $namespaceId = "", string $clusterName = "", string $groupName = ""): ResponseInterface{
        // 分组名是空的情况下，取初始化时的分组名
        if($groupName == ""){
            $groupName = NamingConfig::getGroupName();
        }
        // 集群名是空的情况下，取初始化时的集群名
        if($clusterName == ""){
            $clusterName = NamingConfig::getClusterName();
        }
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