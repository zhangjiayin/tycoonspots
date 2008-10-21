#!/usr/bin/php
<?php
#*******************************************************
#    Author        : jiayin - zhangjiayin@360quan..com
#    Last modified : 2008-10-21 19:02
#    Filename      : kaixin.php
#    Description   :
#*******************************************************
$config = array (
    //基本配置
    'charset' => 'utf-8',
    'user' => array (
        'email'    => 'email',
        'password' => 'pw'
    ),
    'app_id'            => '',

    'db'   =>  array (
        'host' => 'localhost',
        'user' => 'root',
        'pw'   => '123456',
        'db'   => 'test'
    ),
    //可控参数
    'mint_limit'        => '3000',
    'interest_limit'    => '30',
    'mint_interest_limit'    => '10',
    'interest_ratio'    => '2',
    'mint_interest_ratio'    => '2',

    //url配置
    'login_url'         => 'http://login.kaixin.com/Login.do',
    'product_url'       => 'http://tycoon.kaixin.com/buildEmpire.do?select_type=%s',
    'sell_url'          => 'http://tycoon.kaixin.com/confirmSellProduct.do',
    'buy_url'           => 'http://tycoon.kaixin.com/confirmBuyProduct.do',
    'my_usual_url'      => 'http://tycoon.kaixin.com/myEmpire.do?type=0&id=%s',
    'my_unusual_url'    => 'http://tycoon.kaixin.com/myEmpire.do?type=1&id=%s',

);

$old_handler = set_error_handler("onerror");

function onerror()
{
    static $i;
    $i++;
    if($i == 200)
    {
        echo ('some error occured' . "\n");
        exit;
    }
    global $link;
    $cookie = login();
    $link   = getDbLink();
}

$table_sql = "
    CREATE TABLE IF NOT EXISTS `p` (
        `product_id` int(11) NOT NULL,
        `name` char(50) character set utf8 NOT NULL,
        `max_price` int(11),
  `min_price` int(11)
) ";

$data_sql = "
    INSERT INTO `p` (`product_id`, `name`, `max_price`, `min_price`) VALUES
    (1, '鱼子酱工厂', 2, 1),
(2, '啤酒厂', 4, 2),
(3, '葡萄酒厂', 6, 4),
(4, '玩具厂', 21, 18),
(5, '名牌服装商标', 40, 35),
(6, '儿童食品厂', 43, 38),
(7, '零食生产厂家', 55, 52),
(8, '谷物食品厂', 71, 66),
(9, '化妆品公司', 105, 96),
(10, '豪华家具厂', 118, 110),
(11, '罐装食品厂', 124, 116),
(12, '名牌提包品牌', 146, 140),
(13, '快餐连锁店', 200, 190),
(14, '汽车生产线', 267, 255),
(15, '软饮料公司', 567, 550),
(16, '家用电器公司', 577, 560),
(17, '名牌鞋商标', 650, 630),
(18, '珠宝厂商', 784, 760),
(19, '服装市场连锁', 949, 920),
(20, '咖啡店连锁', 1237, 1200),
(21, '赛马', 2, 1),
(22, '意大利布加迪跑车', 4, 2),
(23, '美洲鸵养殖场', 4, 3),
(24, '私人动物园', 7, 5),
(25, '赛车队', 5, 1),
(26, '猎鹰', 49, 46),
(27, '喷气式飞机', 64, 60),
(28, '度假风情岛', 157, 150),
(29, '太空飞行装备', 167, 160),
(30, '搜宝队', 251, 240),
(31, '基因实验室', 743, 720),
(32, '大学城', 949, 920),
(33, '慈善基金会', 1135, 1100),
(34, '太空探索队', 2660, 2600),
(35, '军事机器人', 4400, 4300),
(36, '国家公园', 40443, 40000),
(37, '新国家', 78763, 78000),
(38, '太空聚居地', 262199, 260000),
(39, '油轮', 7, 5),
(40, '钢铁厂', 37, 32),
(41, '金矿开采', 40, 35),
(42, '机械厂', 70, 65),
(43, '风力发电厂', 85, 79),
(44, '汽车运输公司', 356, 340),
(45, '采矿场', 805, 780),
(46, '钻石开采', 1269, 1240),
(47, '医用设备', 3069, 3000),
(48, '采油厂', 4092, 4000),
(49, '石油开采团队', 6650, 6500),
(50, '太阳能发电厂', 7161, 7000),
(51, '热气球旅行公司', 12, 8),
(52, '夜总会', 14, 10),
(53, '飞船公司', 26, 23),
(54, '广播卫星', 37, 32),
(55, '电视频道', 37, 32),
(56, '报社', 50, 46),
(57, '航空公司', 75, 70),
(58, '健身中心', 97, 90),
(59, '游乐园', 107, 100),
(60, '杂志出版社', 152, 145),
(61, '电影制片厂', 167, 160),
(62, '唱片公司', 188, 180),
(63, '电视台', 516, 500),
(64, '赌场', 536, 520),
(65, '冰球队', 598, 580),
(66, '足球队', 1135, 1100),
(67, '篮球队', 1134, 1100),
(68, '垒球队', 1432, 1400),
(69, '橄榄球队', 1637, 1600),
(70, '服务器制造厂', 14, 10),
(71, '电子宠物公司', 54, 50),
(72, '电脑游戏公司', 107, 100),
(73, 'IT公司', 314, 300),
(74, '电子商务公司', 419, 400),
(75, '特技公司', 619, 600),
(76, '电话中心', 619, 600),
(77, '电讯网络', 826, 800),
(78, '商务软件公司', 1031, 1000),
(79, '水上别墅', 9, 6),
(80, '公寓单元楼', 86, 80),
(81, '大农场', 86, 80),
(82, '豪华宾馆', 86, 80),
(83, '豪华公寓', 108, 100),
(84, '滑雪场', 188, 180),
(85, '私人别墅', 293, 280),
(86, '综合写字楼', 433, 420),
(87, '高尔夫球场', 433, 420),
(88, '摩天大楼', 722, 700),
(89, '温室', 929, 900),
(90, '购物中心', 2046, 2000),
(91, '法拉利ENZO', 40, 10),
(92, '超级游艇', 792, 700),
(93, '圣杯', 100, 40),
(94, '兵马俑', 100, 20),
(95, '莫奈真迹', 197, 100),
(96, '英国皇宫', 338, 200),
(97, '金腰带', 105, 60),
(98, '皇冠', 310, 200),
(99, '大本钟', 40946, 40000);";

function httpRequest ($url, $data=array(), $cookiefile="cookiefile")
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_REFERER, "http://home.kaixin.com/Home.do?id=700003457");
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
    curl_setopt($ch, CURLOPT_URL, $url);

    if(!empty($data))
    {
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function login ($cookie="cookiefile")
{
    global $config;

    $data = array(
        'email' => $config['user']['email'],
        'password' => $config['user']['password'],
        'origURL' => 'http://www.kaixin.com/SysHome.do',
    );

    httpRequest($config['login_url'],$data, $cookie);

    return $cookie;
}

function getProductInfo ($cookie = "cookiefile")
{
    global  $config;

    $product_url = $config['product_url'];
    $productTotalPage = 7;

    for($i=1; $i<=$productTotalPage; $i++)
    {
        $request_url[] = sprintf($product_url, $i);
    }

    $product = array();

    foreach($request_url as $url)
    {
        $response = httpRequest($url,array(), $cookie);

        $pattern = '#<input type="hidden" name="product_id" value="(\d+)"\/>#i';
        preg_match_all($pattern, $response, $product_id);

        $pattern = '#<input type="hidden" name="buy_price" value="(\d+)"/>#';
        preg_match_all($pattern, $response, $buy_price);

        $pattern = '#<input type="hidden" name="count" value="(\d+)"/>#';
        preg_match_all($pattern, $response, $buy_count);

        $pattern = '#<dt class=".*?" title=".*?">(.*?)</dt>#';
        preg_match_all($pattern, $response, $buy_name);

        foreach($buy_name[1] as $key => $name)
        {
            $product[$product_id[1][$key]] = array (
                'name'  => $name,
                'id'    => $product_id[1][$key],
                'price' => $buy_price[1][$key],
                'count' => $buy_count[1][$key],
            );
        }
    }

    return $product;
}

function getDbLink ()
{
    global $link;
    global $config;

    if($link == null)
    {
        $link = new Mysqli($config['db']['host'], $config['db']['user'], $config['db']['pw'], $config['db']['db']);
        $link->query('set Names utf8');
    }

    return $link;
}

function initDB ()
{
    global $table_sql;
    global $data_sql;
    $link = getDbLink();
    $return =  $link->query($table_sql);
    $return &= $link->query($data_sql);
    return $return;
}

function getInterestInfo ($mint=false)
{
    global $config;

    $link = getDbLink();

    $return = array();
    if($mint)
    {
        $sql = "SELECT product_id, (max_price - min_price)/min_price  as interest_rate , (max_price - min_price) as for_order, name, max_price  , min_price FROM `p` group by product_id order by for_order desc limit " . $config['interest_limit'];
        $stmt = $link->query($sql);
        if(!$link->errno)
        {
            while($row = $stmt->fetch_assoc())
            {
                $return[$row['product_id']] = $row;
            }
        }
    }

    $sql = "SELECT product_id, (max_price - min_price) / min_price as interest_rate , name, max_price  , min_price FROM `p` group by product_id order by   interest_rate desc limit " . $config['interest_limit'];

    $stmt = $link->query($sql);
    if(!$link->errno)
    {
        while($row = $stmt->fetch_assoc())
        {
            $return[$row['product_id']] = $row;
        }
    }
    if($link->errno == 1146)
    {
        initDB();
        $stmt = $link->query($sql);
    }



    return $return;
}

function updatePrice ($product_id, $price, $max=false)
{
    $link = getDbLink();
    $sql = 'UPDATE P set ';

    if($max)
    {
        $sql .= '`max_price` = ' . $price;
    } else {
        $sql .= '`min_price` = '  . $price;
    }

    $sql .= ' where product_id =' . $product_id;

    return $link->query($sql);
}

function sellProduct ($product_id, $pid, $price)
{
    global $config;

    $url = $config['sell_url'];

    $data = array(
        'confirm'       => 'true',
        'pid'           => $pid,
        'product_id'    => $product_id,
        'sell_price'    => $price,
        'is_use_item'   => 'false'
    );

    for($i=5; $i>0; $i--)
    {
        $data['count'] = $i;
        $return = httpRequest($url, $data);

        if(strpos($return, '成功'))
        {
            return $i;
        }
    }

    return false;
}

function buyProduct ($product_id, $price)
{
    global $config;

    $url = $config['buy_url'];

    $data = array (
        'confirm'    => 'true',
        'product_id' => $product_id,
        'buy_price'  => $price,
    );

    for($i=5; $i>0; $i--)
    {
        $data['count'] = $i;
        $return = httpRequest($url, $data);
        if(strpos($return, '成功'))
        {
            return $i;
        }
    }
    return false;
}

function getMyProduct ()
{
    global $config;

    $my_url = array (
        sprintf($config['my_usual_url'], $config['app_id']),
        sprintf($config['my_unusual_url'], $config['app_id']),
    );

    foreach($my_url as $url)
    {
        $response = httpRequest($url);

        $pattern = '#<input type="hidden" name="product_id" value="(\d+)"\/>#i';
        preg_match_all($pattern, $response, $product_id);

        $pattern = '#<input type="hidden" name="pid" value="(\d+)"\/>#i';
        preg_match_all($pattern, $response, $pid);

        foreach($product_id[1] as $key => $value)
        {
            $return[$value] = array('pid' => $pid[1][$key], 'product_id' => $product_id[1][$key]);
        }
    }

    return $return;
}

function getMyInfo()
{
    global $config;

    $my_url = array (
        sprintf($config['my_usual_url'], $config['app_id']),
        sprintf($config['my_unusual_url'], $config['app_id']),
    );

    $product_count = 0;
    $return = array();

    foreach($my_url as $url)
    {
        $response = httpRequest($url);

        $pattern = '#<input type="hidden" name="product_id" value="(\d+)"\/>#i';
        preg_match_all($pattern, $response, $product_id);
        if(!empty($product_id[0]))
        {
            $product_count +=count($product_id[0]);
        }

        if(empty($return)) {
            $pattern = '#资产总额：<span class="add-num">(.*?)</span>#';
            preg_match_all($pattern, $response, $total_assets);
            $return['total_assets'] = toMillion($total_assets[1][0]);
            $pattern = '#现金：<span class="add-num">(.*?)</span>#';
            preg_match_all($pattern, $response, $cash);
            $return['cash'] = toMillion($cash[1][0]);
            $pattern = '#企业价值：<span class="add-num">(.*?)</span>#';
            preg_match_all($pattern, $response, $ent_assets);
            $return['ent_assets'] = toMillion($ent_assets[1][0]);
        }
    }

    $return['ent_count'] = $product_count;

    return $return;
}

function toMillion($str)
{
    if(strpos($str, '亿')){
        $tmp = explode('亿', $str);
        return $tmp[0] . substr(sprintf('%04d', intval($tmp[1])), 0, -2);
    }
    return substr(intval($str), 0, -2);
}

function Utf8toGBK ($str)
{
    global $config;
    if($config['charset'] == 'gbk')
    {
        return iconv("unicodebig", "gbk" ,iconv("utf-8", 'unicodebig', $str));
    }

    return $str;
}

function secho($str,$type=false)
{
    if($type){
        echo ("\033[31;32m". $str ."\033[0m");
        return true;
    }
    echo ("\033[33;34m". $str ."\033[0m");
    return true;
}



$link   = null;
$cookie = login();
$link   = getDbLink();
if(file_exists('exit')){
    unlink('exit');
}

while(1)
{
    if(file_exists('exit'))
    {
        exit;
    }

    $myInfo = getMyInfo();
    $mint = false;
    if($myInfo['total_assets'] > $config['mint_limit']) {
        $mint = true;
    }

    $product = getProductInfo();
    $interest = getInterestInfo($mint);
    $myProduct = getMyProduct();

    foreach($interest as $product_id => $value)
    {
        if($product[$product_id]['price'] <= $value['min_price'])
        {
            if($product[$product_id]['price'] < $value['min_price'])
            {
                updatePrice($product_id, $product[$product_id]['price'],false);
            }

            $str = $product[$product_id]['name'] . " 可买" . " 当前为历史最低价格:"  . $product[$product_id]['price'];
            echo(Utf8toGBK($str) . "\n\n");

            if(!array_key_exists($product_id, $myProduct))
            {
                if( $r = buyProduct($product_id, $product[$product_id]['price']))
                {
                    secho(Utf8toGBK('买入' . $product[$product_id]['name'] . ' ' . $r . '个', true));
                    echo( "\n\n");
                }
            }
        }

        if($product[$product_id]['price'] >= $value['max_price'])
        {
            if($product[$product_id]['price'] > $value['max_price'])
            {
                updatePrice($product_id,$product[$product_id]['price'] ,true);
            }

            $str = $product[$product_id]['name'] . "\33 可卖" . " 当前为历史最高价格:" . $product[$product_id]['price'] ;
            echo (Utf8toGBK($str) . "\n\n");
        }

        if(array_key_exists($product_id, $myProduct))
        {
            if($mint)
            {
                $sell_cond = ($product[$product_id]['price'] - $value['min_price']) * $config['mint_interest_ratio'] / $value['min_price'] >= $value['interest_rate'];
            } else {
                $sell_cond = ($product[$product_id]['price'] - $value['min_price']) * $config['interest_ratio'] / $value['min_price'] >= $value['interest_rate'];
            }

            if($sell_cond)
            {
                if($r = sellProduct($product_id, $myProduct[$product_id]['pid'], $product[$product_id]['price']))
                {
                    secho(Utf8toGBK('卖出' . $product[$product_id]['name'] . ' ' . $r . '个', false));
                    echo( "\n\n");
                }
            }
        }
    }

    $myInfo = getMyInfo();
    echo ("\n");
    echo ("----------------------------------------------");
    echo ("\n");
    $str  = '当前总资产:' . $myInfo['total_assets'] . "\n";
    $str .= '现金      :' . intval($myInfo['cash']) . "\n";
    $str .= '企业资产  :' . $myInfo['ent_assets'] . "\n";
    echo (Utf8toGBK($str));
    echo ("----------------------------------------------");
    echo ("\n");
    sleep(60);
}
