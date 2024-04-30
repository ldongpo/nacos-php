<?php

namespace nacosphp\util;
use nacosphp\exception\ResponseCodeErrorException;
use ReflectionClass;
use ReflectionException;

class fun
{
    /**
     * 获取传入对象的所有属性及其值，包括其所有父类的属性
     * @throws ReflectionException
     * @throws ResponseCodeErrorException
     */
    public static function getProperties($object): array
    {
        if (!is_object($object)) {
            throw new ResponseCodeErrorException(-1, "Expected an object.");
        }
        $properties = array();
        $reflect = new ReflectionClass($object);
        do {
            foreach ($reflect->getProperties() as $property) {
                // 调用公共方法来获取属性值
                $propertyName = $property->getName();
                $properties[$propertyName] = $object->getPropertyValue($propertyName);
            }
        } while ($reflect = $reflect->getParentClass());
        return $properties;
    }
}