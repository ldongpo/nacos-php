<?php

namespace nacosphp\request\naming;
use nacosphp\request\Request;
use nacosphp\util\fun;
use nacosphp\config\NamingConfig;

abstract class NamingRequest extends Request
{

    /**
     * Notes: 获取参数
     * User: mail@liangdongpo.com
     * Date: 2024/4/28
     * Time:14:36
     * @return array
     * @throws \ReflectionException
     */
    protected function getParameter(): array{
        $parameterList = [];

        $properties = fun::getProperties($this);
        foreach ($properties as $propertyName => $propertyValue) {
            if (in_array($propertyName, $this->standaloneParameterList)) {
                // 忽略这些参数
            } else {
                $parameterList[$propertyName] = $propertyValue;
            }
        }

        if ($this instanceof RegisterInstanceNaming) {
            $parameterList["ephemeral"] = NamingConfig::getEphemeral();
        }
        return $parameterList;
    }
    abstract protected function getPropertyValue($propertyName);

}