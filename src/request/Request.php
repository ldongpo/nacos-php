<?php
namespace nacosphp\request;
use nacosphp\exception\RequestMethodRequiredException;
use nacosphp\exception\RequestUriRequiredException;
use nacosphp\util\httpCurl;
use nacosphp\enum\ErrorCodeEnum;
use nacosphp\exception\ResponseCodeErrorException;

/**
 * Notes: 请求基类
 * User: mail@liangdongpo.com
 * Date: 2024/4/28
 * Time:14:36
 */
abstract class Request
{
    /**
     * 接口地址
     */
    protected  string $uri;

    /**
     * 请求类型
     */
    protected string $method;
    /**
     * 忽略的属性
     * @var array|string[]
     */
    protected array $standaloneParameterList = ["uri", "method"];

    /**
     * 发起请求
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws RequestUriRequiredException
     * @throws ResponseCodeErrorException
     * @throws RequestMethodRequiredException
     */
    public function doExecute(): \Psr\Http\Message\ResponseInterface
    {
        $parameterList = $this->getParameter();
        $response = httpCurl::execute(
            $this->getMethod(),
            $this->getUri(),
            $parameterList,
            []
        );
        if (isset(ErrorCodeEnum::getErrorCodeMap()[$response->getStatusCode()])) {
            throw new ResponseCodeErrorException($response->getStatusCode(), ErrorCodeEnum::getErrorCodeMap()[$response->getStatusCode()]);
        }
        return $response;
    }

    abstract protected function getParameter();
    abstract protected function getPropertyValue($propertyName);
    /**
     * @throws RequestUriRequiredException
     */
    public function getUri(): string{
        if ($this->uri == null) {
            throw new RequestUriRequiredException();
        }
        return $this->uri;
    }
    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @throws RequestMethodRequiredException
     */
    public function getMethod(): string
    {
        if ($this->method == null) {
            throw new RequestMethodRequiredException();
        }
        return $this->method;
    }
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }
}