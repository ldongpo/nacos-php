<?php
namespace nacosphp\call;
use Exception;
use nacosphp\exception\ResponseCodeErrorException;
use nacosphp\naming\NamingClient;
use nacosphp\config\NacosConfig;

abstract class CallServiceWith
{
    private  string $nsHost; //配置中心地址
    protected NamingClient $naming;
    public function __construct(string $nsHost,NamingClient $naming){
        NacosConfig::setHost($nsHost); // 配置中心地址
        $this->naming = $naming;
    }

    /**
     * Notes: 子类必须实现这个方法，具体逻辑自行实现
     * User: mail@liangdongpo.com
     * Date: 2024/4/30
     * Time:17:09
     * @param $ip //服务端ip
     * @param $port //服务端端口
     * @param $data //数据
     * @return mixed
     */
    abstract protected function callback ($ip,$port,$data): mixed;

    /**
     * Notes:
     * User: mail@liangdongpo.com
     * Date: 2024/4/30
     * Time:18:19
     * @param string $nsHost
     * @return void
     */
    public function setNsHost(string $nsHost): void
    {
        $this->nsHost = $nsHost;
    }

    /**
     * Notes:
     * User: mail@liangdongpo.com
     * Date: 2024/4/30
     * Time:18:20
     * @return string
     */
    public function getNsHost(): string
    {
        return $this->nsHost;
    }

    /**
     * Notes: 获取健康实例
     * User: mail@liangdongpo.com
     * Date: 2024/4/30
     * Time:16:16
     * @return array
     * @throws ResponseCodeErrorException
     */
    public function getHealthyServiceInstances(): array{
        $res =  $this->naming->list(true);
        $data = json_decode($res->getBody()->getContents(),true);
        $code = $data["code"] ?? -1;
        if($code != 0){
            return [];
        }
        $hosts = [];
        foreach ($data["data"]["hosts"] as $key => $value){
            $hosts[] = $value;
        }
        return $hosts;
    }

    /**
     * Notes: 获取一个健康实例
     * User: mail@liangdongpo.com
     * Date: 2024/4/30
     * Time:16:35
     * @return array
     * @throws ResponseCodeErrorException
     */
    public function getHealthyOnlyServiceInstances(): array{
        return $this->selectRandomInstance($this->getHealthyServiceInstances());
    }
    /**
     * Notes: 随机选择一个实例
     * User: mail@liangdongpo.com
     * Date: 2024/4/30
     * Time:16:15
     * @param $instances
     * @return mixed
     * @throws ResponseCodeErrorException
     */
    function selectRandomInstance($instances): mixed
    {
        if (empty($instances)) {
            throw new ResponseCodeErrorException(-1, "No healthy instances available.");
        }

        $randomKey = array_rand($instances);
        return $instances[$randomKey];
    }

    /**
     * Notes: 重试机制-调用服务
     * User: mail@liangdongpo.com
     * Date: 2024/4/30
     * Time:16:17
     * @param array $data
     * @param int $maxRetries
     * @param int $retryDelay
     * @return mixed|void
     * @throws ResponseCodeErrorException
     */
    function retry(array $data, int $maxRetries = 3, int $retryDelay = 2000) {
        $attempts = 0;
        $instance = $this->getHealthyOnlyServiceInstances();
        while ($attempts < $maxRetries) {
            try {
                return $this->callback($instance['ip'],$instance['port'],$data);
            } catch (Exception $e) {
                $attempts++;
                if ($attempts >= $maxRetries) {
                    throw new ResponseCodeErrorException(-1, "Maximum retry attempts reached, last error: " . $e->getMessage());
                }
                // 等待一定时间后重试
                usleep($retryDelay * 1000);  // usleep 的单位是微秒
            }
        }
    }

    /**
     * Notes: 调用服务
     * User: mail@liangdongpo.com
     * Date: 2024/4/30
     * Time:16:18
     * @param array $data
     * @param int $maxRetries
     * @param int $retryDelay
     * @return mixed
     * @throws ResponseCodeErrorException
     */
    function callServiceWithRetry(array $data, int $maxRetries = 3, int $retryDelay = 2000): mixed
    {
        return $this->retry($data, $maxRetries, $retryDelay);
    }
}