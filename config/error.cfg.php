<?php
/**
 * 错误数组定义
 * @date 2015-01-05
 * @var array
 *
 * @note 错误码分配原则
 * 
 * 错误码数字分配
 *
 *   -1   系统繁忙，此时请开发者稍候再试
 *   0    请求成功
 *   10000起 系统级别错误
 *   
 *   客服APP 错误码 从第1个-第100个;base api 101-200;wechatauth 901-999;material 801-900;wechatreply  701-800;menu 601-700;mass 501-600
 *   401-500  活动管理
 *
 *   40000  未知错误
 *
 *   40001-40999  不合法参数
 *
 *   41000-41999  缺少参数
 *
 *   42000-42999  超时
 *
 *   43000-43999  好友关系、协议验证
 *
 *   44000-44999  数据为空
 *
 *   45000-45999  各种超过限制
 *
 *   46000-46999  用户不存在、列表不存在等等
 *
 *   47000-47999  解析错误 例如json 其他解析错误等
 *
 *   48000-48999  授权错误
 * 
 */
$error_no_array = array(
    "-1"    => "系统繁忙",
    "0"     => "ok",


    '10001' => '微信错误',
    '10020' => '接口不存在',
    '10021' => '保存文本素材失败',
    '10022' => '添加定时发送失败',
    '10023' => '发送记录保存失败',
    '10024' => '删除失败',

    '10031' => '不合法的分组id',
    '10032' => '粉丝id不能为空',
    '10033' => '目标分组不存在',
    '10034' => '粉丝不存在',
    '10035' => '微信分组更改失败',
    '10036' => '微信分组数据异常',
    
    '40181' => '缺少参数',


    /***app-wechat----start--**/
    "40001" => '添加失败',
    "40002" => '修改失败',
    "40003" => '删除失败',
    "40004" => '检查数据有误',
    "40005" => '传输数据为空',
    "40006" => '没找到相关数据',
    "40007" => '保存失败',
    "40008" => '填写数据有误',
    "40011" => '用户不存在',
    "40012" => '抱歉,您没有此权限',
    "40009" => 'socket错误',
    "40010" => '营业时间内未找到可分发的用户',
    "40011" => '未开启邀请评价',


    "40101" => "不合法的请求",
    "40102" => "不合法的任务",
    "40801" => "不合法参数",
	"40802" => "所选素材已用于定时群发，不能删除",
	"40803" => "每天同步次数超限",
	"40804" => "微信删除素材失败",
    "40104" => "不允许删除非待发送和发送失败的任务",
    "41101" => "参数错误",
    "41102" => "auth_uid或wx_system_uid为空亦或auth_uid或wx_system_uid格式非法",
    "41103" => "请设置发送时间",
    "41104" => "定时群发时间需至少在当前时间10分钟以后",
    "41105" => "10分钟内的待发送消息不能立即发送",
    "41151" => "该用户暂不能对话",
    "41152" => "发送失败",
    "41153" => "添加分组失败",
    "41154" => "分组已经存在",
    "41155" => "修改分组名称失败",
    "41131" => "缺少参数",
    "41801" => "缺少参数",
    "43101" => "无效的请求",
    "40698" => "菜单名不能为空",
    "40699" => "参数不合法",
    "40793" => "无效参数",
    "40794" => "关键词描述不能为空",
    "40795" => "定义关键词不能为空",
    "40796" => "设置数据已存在",
    "40797" => "参数错误",
    "40798" => "图文类型不能为空",
    "40799" => "关键词文本内容不能为空",
    "41799" => "参数错误",
    "40997" => "不合法的请求",
    "40998" => "参数错误",
    "40999" => "uid为空或者非法的uid格式",
    '40911' => '二维码上传失败',
    "44101" => "群发内容不能为空",
    "44102" => "群发人数不能为零",
    "44103" => "无法获取要发送的素材",
    "44104" => "群发人数不能少于两个",
    "44105" => "无法获取用户信息",
    "44106" => "公众平台未返回数据",
    "44107" => "不允许的素材类型",
    "44108" => "数据为空",
    '44131' => '未找到粉丝信息',
	/***mass***/
	'43501' => '您输入的微信号没有关注您',
	'44501' => '微信号不能为空',
    "44698" => "未填写appid 和 appsecret",
    "44699" => "授权信息为空",
	"44801" => "上传数据为空",
	"44802" => "用户素材分类数据为空",
	"44803" => "用户素材数据为空",
    "44996" => "获取accesstoken失败",
    "44997" => "appId或者appSecret不能为空",
    "44998" => "微信号不能为空",
    "44999" => "账号类型不能为空",
    "45101" => "群发次数超过限制",
    "45102" => "该日期内的已有定时群发任务，次数已达到1次，请选择在其它时间发送",
	"45103" => "该日期内此图文消息已发送或已有定时任务",
	"45104" => "外链类型的图文不能用于群发",
    "45151" => "对话时间已过期", 
    "45152" => "分组名过长", 
    "45153" => "分组数量超限", 
    "45697" => "修改菜单信息失败",
    "45698" => "删除菜单失败",
    "45699" => "菜单创建已达上限",
	"45801" => "上传图文数量已达上限",
	"45802" => "上传图文数量小于2条",
	"45803" => "最多添加21个分组，不能再添加",
	"45804" => "图片标题不能重复",
    "45998" => "账号不能重复绑定",
    "45999" => "微信号字数超出限制",
    "46698" => "无法获取菜单列表",
    "46699" => "无法获取菜单项信息",
    "46101" => "分组不存在",
    "46797" => "此关键词已存在",
    "46798" => "此关键词不存在",
    "46799" => "暂无关键词列表",
	"46801" => "分组名称不能重复",
    "46999" => "用户不存在",

    //消息分发规则
    '40711' => '消息分发地址不能为空',
    '40712' => '消息分发标识不能为空',
    '40713' => '关键词已存在',
	//二维码
	'40301' => '不合法的请求',
	'40302' => '参数为空',
	'40303' => '无效参数',
	'40304' => '参数不合法',
	"40305" => "auth_uid或wx_system_uid为空亦或auth_uid或wx_system_uid格式非法",
	'40306' => '二维码数量上限',
	'40307' => '创建二维码失败',
	'40308' => '获取二维码图片失败',
	'40309' => '二维码不存在',
	'40310' => '操作失败',
	'40311' => '用户不存在',
	'40312' => '获取ticket失败',
	'40313' => '数据为空',
	'40314' => '字数超限',
	'40315' => '关注结束时间不得早于开始时间',
	'40316' => '您不是认证服务号，暂无此功能权限',


    "48999" => "微信授权异常",
    "48101" => "初始化失败",
    "48102" => "当前帐号未授权",
    "48103" => "当前帐号没有高级群发接口权限",
	"48801" => "当前帐号access_token无效",


    //粉丝应用
    "40500" => "数据为空",
    "40501" => "参数错误",
    "40502" => "同时只能定义 500 个标签，您可以联系客服购买更高版本或删除后再添加",
    "40503" => "创建标签失败",
    "40504" => "打标签失败",
    "40505" => "已有用户打过此标签", 
    "40506" => "移除标签失败",
    "40507" => "删除标签失败",
    "40508" => "添加标签分类失败",
    "40509" => "标签分类名称不可重复",
    "40510" => "分类不存在",
    "40511" => "标签名称不可重复",
    "40512" => "修改标签失败",
    "40512" => "修改标签分类失败",
    "40514" => "部分打标签操作失败 或者 部分用户已打过指定标签",
    "40515" => "分类下还有标签",
    "40516" => "删除标签分类失败",


    "40520" => "客户备注同步失败",
    "40530" => "关注结束时间不得早于开始时间",
    "40531" => "page_info 参数错误",
    "40532" => "参数 search_str 不能为空",
    "40533" => "参数 search_name 不能为空",
    "40534" => "搜索条件数量不能超过 3 个",
    "40535" => "名称重复",
    "40560" => "账号类型有误",
	// 搜索相关追加 code 提示信息
	"40561" => "参数不能为空",

    "40570" => "已达到20标签分类上限，不能继续添加",
    "40571" => "已达到300标签上限，不能继续添加",
    "40572" => "删除搜索条件失败",
	
    //会员卡
    "40580" => "没有设置会员等级",
    "40581" => "total 必须为整数",
    "40582" => "增加积分失败",
    "40583" => "设置会员卡样式失败",
    "40584" => "升级模式不可用",
    "40585" => "升级特权不可用",
    "40586" => "删除会员等级失败",
    "40587" => "只能删除最高等级",
    "40588" => "等级超过最大数量",
    "40589" => "会员卡状态切换失败",
    "40590" => "必须打折",
    "40591" => "您的手机号码已领取过会员卡",
	
	"40401" =>"删除失败",
	"40402" =>"添加失败",
	"40403" =>"类型名称重复",
	"40404" =>"类型数量不能超过十个",
    "40405" =>"分类下有已安排的群发",
	"40406" =>"插入数据失败",


    /***活动报名相关***/
    "50001" => "您已经报名啦",

	/******签到相关*******/
	"50101" => "您已经签到过了",
	"50102" => "您还未填写签到基本信息",
	"50103" => "对不起，验证失败，验证码错误。谢谢！",
	"50104" => "对不起，验证码已经被使用，谢谢！",
	"50105" => "请输入正确的手机号码！",

    /******抽奖相关*******/
    '50201'     => '未登录',
    '50202'     => '你输入的数据有误',
    '50203'      => '您输入的兑换码不正确',        //兑换码为空
    '50204'     => '该兑换码未中奖',                //兑换码不存在
    '50205'     => '该兑换码未中奖',                //兑换码已使用
    '50206'     => '兑换码已过期',
    '50207'     => '活动未开始，敬请期待！',
    '50208'     => '抱歉，兑换码对应的活动已结束！',
    '50209'     => '此活动不存在',


    '50210'     => '对不起，您额外抽奖机会没有了！',
    '50211'     => '对不起，抽奖失败！',
    '50212'     => '对不起，请不要重复充值！',
    '50213'     => '充值金额大于账户余额',
    '50214'     => '总充值金额超过上限',
    '50215'     => '充值失败',
    '50216'     => '您已填写领奖信息',
    '50217'     => '保存领奖信息失败',
    '50218'     => '您没有中金戒记录',
    '50219'     => '兑奖码连续输错5次，24小时内将无法兑奖',
    '50220'     => '调用失败',
    '50221'     => '请不要快速提交',
    '50222'     => '每人每天最多兑奖1000次，请明天再来',

    '50223'     => '今日累计中奖金额已达上限，请明日再来', //每人每天中奖总金额上限 1000元
    '50224'     => '每人每天充值金额上限 2000元',
    '50225'     => '每人总最高累积兑奖额 20000元',
    '50226'     => '您手速太快了，请1个小时后再来~',
    '50227'     => '您兑奖次数过多，休息一下再来~',
    '50228'     => '每人每天兑奖次数超过20次',
    '50229'     => '系统繁忙，请稍后再试！', //Refer错误
    '50230'     => '该兑换码未中奖',  //OPEN_ID黑名单用户兑奖
    '50231'     => '系统繁忙，请稍后再试！',  //Get方式提交兑奖码
    '50232'     => '系统繁忙，请稍后再试！',  //OPEN_ID黑名单用户充值

    '50234'     => '该兑换码未中奖',  //IP黑名单用户兑奖
    '50235'     => '系统繁忙，请稍后再试！',  //IP黑名单用户充值
    '50236'     => '系统繁忙，请刷新页面后再试！',  //用户Token验证不通过
    '50237'     => '兑奖码无效',  //兑奖码还未开仓
    '50238'     => '兑奖码已过期',  //兑奖码已过期
    '50239'     => '该兑换码未中奖！',  //大数据防刷系统验证不通过
	
	'50240'     => '该兑换码未中奖！',  //今日中奖金额已经超限

    /***********联合利华KCP活动相关*****************/
    '50301'=>'传入参数不正确',
    '50302'=>'调用失败',
    '50303'=>'用户不存在',
    '50304'=>'优惠券已存在',
    '50305'=>'商户不存在',
    '50306'=>'签名出错',
    '50307'=>'优惠券不存在',
    '50308'=>'优惠券已使用',
    '50309'=>'优惠券已过期',
    '50310'=>'优惠券已激活'

);
