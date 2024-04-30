<?php
namespace nacosphp\enum;
class ErrorCodeEnum
{
    /**
     * Notes: 获取服务基本错误码汇总
     * User: mail@liangdongpo.com
     * Date: 2024/4/28
     * Time:14:58
     * @return string[]
     */
    public static function getErrorCodeMap(): array
    {
        return [
            10000 => '参数缺失',
            10001 => '访问拒绝',
            10002 => '数据访问错误',
            20001 => 'tenant参数错误',
            20002 => '参数验证错误',
            20003 => '请求的MediaType错误',
            20004 => '资源未找到',
            20005 => '资源访问冲突',
            20006 => '监听配置为空',
            20007 => '监听配置错误',
            20008 => '无效的dataId（鉴权失败）',
            20009 =>'请求参数不匹配',
            21000 => 'serviceName服务名错误',
            21001 => 'weight权重参数错误',
            21002 => 'instance metadata元数据错误',
            21003 => 'instance实例不存在',
            21004 => 'instance实例信息错误',
            21005 => '服务metadata元数据错误',
            21006 => '访问策略selector错误',
            21007 => '服务已存在',
            21008 => '服务不存在',
            21009 => '服务删除失败',
            21010 => 'healthy参数缺失',
            21011 => '健康检查仍在运行',
            22000 => '命名空间namespace不合法',
            22001 => '命名空间不存在',
            22002 => '命名空间已存在',
            23000 => '状态state不合法',
            23001 => '节点信息错误',
            23002 => '节点离线操作出错',
            30000 => '其他内部错误',
        ];
    }
}