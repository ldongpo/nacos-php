<?php
namespace nacosphp\call;
use Exception;
use nacosphp\exception\ResponseCodeErrorException;
use nacosphp\naming\NamingClient;
use nacosphp\config\NacosConfig;

abstract class CallServiceWith
{
    private  string $nsHost; //配置中心地址
    private static int $maxRetries = 3; //重试次数
    private static int $retryDelay = 2000;//重试间隔
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
     * Notes: 设置重试次数
     * User: mail@liangdongpo.com
     * Date: 2024/5/6
     * Time:11:37
     * @param int $maxRetries
     * @return void
     */
    public static function setMaxRetries(int $maxRetries): void
    {
        self::$maxRetries = $maxRetries;
    }
    /**
     * Notes: 设置重试间隔
     * User: mail@liangdongpo.com
     * Date: 2024/5/6
     * Time:11:37
     * @param int $retryDelay
     * @return void
     */
    public static function setRetryDelay(int $retryDelay): void
    {
        self::$retryDelay = $retryDelay;
    }
    /**
     * Notes: 获取重试次数
     * User: mail@liangdongpo.com
     * Date: 2024/5/6
     * Time:11:37
     * @return int
     */
    public static function getMaxRetries(): int{
        return self::$maxRetries;
    }
    /**
     * Notes: 获取重试间隔
     * User: mail@liangdongpo.com
     * Date: 2024/5/6
     * Time:11:37
     * @return int
     */
    public static function getRetryDelay(): int{
        return self::$retryDelay;
    }
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
     * @return mixed|void
     * @throws ResponseCodeErrorException
     */
    function retry(array $data) {
        $attempts = 0;
        $instance = $this->getHealthyOnlyServiceInstances();
        while ($attempts < self::$maxRetries) {
            try {
                return $this->callback($instance['ip'],$instance['port'],$data);
            } catch (Exception $e) {
                $attempts++;
                if ($attempts >=  self::$maxRetries) {
                    throw new ResponseCodeErrorException(-1, "Maximum retry attempts reached, last error: " . $e->getMessage());
                }
                // 等待一定时间后重试
                usleep(self::$retryDelay * 1000);  // usleep 的单位是微秒
            }
        }
    }

    /**
     * Notes: 调用服务
     * User: mail@liangdongpo.com
     * Date: 2024/4/30
     * Time:16:18
     * @param array $data
     * @return mixed
     * @throws ResponseCodeErrorException
     */
    function callServiceWithRetry(array $data): mixed
    {
        return $this->retry($data);
    }
}