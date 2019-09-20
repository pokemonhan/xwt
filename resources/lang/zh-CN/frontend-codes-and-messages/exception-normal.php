<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/7/2019
 * Time: 8:23 PM
 */

return [
    //ApiMainController
    '100000' => '机器人等不正常客户禁止请求',
    //FrontendAuthController
    '100001' => '您没有访问权限',
    '100002' => '账号密码错误',
    '100003' => '旧密码不匹配',
    '100004' => '没有此用户',
    '100005' => '你已多次登录暂时不能登录',
    '100007' => '新密码与旧密码相同',
    '100008' => '两次密码不一致',
    '100009' => '原密码不正确',
    '100010' => 'type非法',
    '100011' => '修改密码失败',
    '100012' => '修改资料失败',
    '100013' => '资金密码已存在',
    '100014' => '您已被禁止登陆',
    '100015' => '奖金组参数缺失',
    '100016' => '奖金组参数错误',
    '100017' => '邀请码参数缺失',
    '100018' => '邀请码不存在',
    '100019' => '请先登录',
    '100020' => '平台信息不存在',
    '100021' => '代理用户不存在',
    '100022' => '您的下级用户过多',
    '100023' => '对不起，您的账号信息不完整',
    '100024' => '资金密码与登录密码不能一致',
    '100025' => '登录密码与资金密码不能一致',
    '100026' => '密码必须为6-16位的字符串',
    '100027' => '密码必须包含字母,强度:弱',
    '100028' => '密码必须包含数字,强度:中',
    '100029' => '密码只能包含数字和字母,强度:强',
    '100030' => '资金密码必须为6-18位的字符串',
    '100031' => '资金密码必须包含字母,强度:弱',
    '100032' => '资金密码必须包含数字,强度:中',
    '100033' => '资金密码只能包含数字和字母,强度:强',
    //UserHandleController
    '100100' => '更改密码已有申请',
    '100101' => '更改资金密码已有申请',
    '100102' => '没有此条信息',
    //UserBankCardController
    '100200' => '银行卡所有者非本人,不可操作',
    '100201' => '删除绑定银行卡失败',
    '100202' => '可绑定的银行卡数量已经是最大，不能继续添加',
    '100203' => '添加的银行卡已经存在',
    '100204' => '当前开户姓名已存在,如需添加其它开户姓名请删除当前开户姓名',
    '100205' => '您未设置资金密码，请先设置资金密码',
    '100206' => '您输入的资金密码错误，请重新输入',
    '100207' => '您输入开户姓名错误，请重新输入',
    '100208' => '资金密码不能为空',
    '100209' => '验证成功',
    '100210' => '不允许添加该银行的银行卡',
    '100211' => '资金密码不能为空',
    //LotteriesController
    '100300' => '对不起, 玩法:methodName位置不正确!',
    '100301' => '对不起, 模式:mode, 不存在!',
    '100302' => '对不起, 奖金组:prizeGroup, 游戏未开放!',
    '100303' => '对不起, 奖金组:prizeGroup, 用户不合法!',
    '100305' => '对不起, 倍数:times, 不合法!',
    '100306' => '对不起, 单价不符合!',
    '100307' => '对不起, 总价不符合!',
    '100309' => '对不起, 追号奖期不正确!',
    '100310' => '对不起, 奖期已过期!',
    '100312' => '对不起, 当前余额不足!',
    '100313' => '对不起, 账号不完整!',
    '100314' => '非法操作',
    '100315' => '该追号不存在或当前状态不可停止追号',
    '100316' => '该投注当前状态不可撤销',
    '100317' => '您已被禁止投注',
    '100318' => '您已被禁止资金操作',
    '100319' => '传入的范围超过限定标准',
    '100320' => '注单不存在',
    '100321' => '对不起,期号:issue已截止投注,追号不可取消,请等待开奖!',
    '100322' => '对不起,本次追号失败，请重新尝试',
    '100323' => '对不起,投注的玩法不存在或未开启',
    '100324' => '暂无走势图数据',
    //HomepageController
    '100400' => '当前模块为关闭状态',
    '100401' => '非法操作',
    //CryptMiddleware
    '100500' => 'data参数传入格式错误',
    '100501' => '解密参数缺失',
    '100502' => 'IV解密错误',
    '100503' => 'KEY解密错误',
    '100504' => '解压JSON数据失败',
    '100505' => 'AES解密失败',
    '100506' => 'DATA参数为空',
    '100507' => '传入的参数不符合规范',
    //UserAgentCenterController
    '100600' => '链接有效期错误',
    '100601' => '奖金组参数错误',
    '100602' => '只有代理才能生成注册链接',
    '100603' => '您生成的注册链接太多了！',
    '100604' => '该用户不存在',
    '100605' => '该用户不是你的下级',
    //UserRechargeController
    '100700' => '对不起, 资金密码未设置!',
    '100701' => '对不起, 测试账户不能充值!',
    '100702' => '对不起, 无效的充值金额!',
];
