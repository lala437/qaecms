<?php

if (!function_exists('curl_get')) {
    function curl_get($url, $proxy = "")
    {
        $testurl = $url;
        $conputer_user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36";
        $mobile_user_agent = "Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $testurl);
        //代理
        if ($proxy) {
            $proxy = array_filter(explode(":", $proxy));
            curl_setopt($ch, CURLOPT_PROXY, $proxy[0]);
            curl_setopt($ch, CURLOPT_PROXYPORT, $proxy[1]);
        }
        //参数为1表示传输数据，为0表示直接输出显示。
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $conputer_user_agent);
        //参数为0表示不带头文件，为1表示带头文件
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        $error_code = curl_errno($ch);
        $curl_info = curl_getinfo($ch);
        $host = parse_url($curl_info['url'])['host'] ?? "";
        $port = parse_url($curl_info['url'])['port'] ?? 80;
        curl_close($ch);
        if ($error_code || (!$output && $curl_info['http_code'] != 200)) {
            return ['status' => 1001, 'errno' => $error_code, "info" => "通讯失败", "host" => $host . ":" . $port];
        }
        return $output;
    }
}

if (!function_exists('curl_post')) {
    function curl_post($url, $array)
    {
        $curl = curl_init();
        $user_agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36";
        $mobile_user_agent = "Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4";
        //$cookies = 'csrftoken=NK9zWiHi1QGpvvSYDk9zEmFNXfJ77bj77ZmTaEla5JgHDe1Cgw2UJNHvs6qIvaJa; sessionid=z8nncax7og8b3x74fw8nt2svp6l89pqa';
        //设置提交的url
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //curl_setopt($curl,CURLOPT_REFERER,$referer);
        //curl_setopt($curl, CURLOPT_COOKIE, $cookies);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data = $array;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        $error_code = curl_errno($curl);
        $curl_info = curl_getinfo($curl);
        $host = parse_url($curl_info['url'])['host'];
        $port = parse_url($curl_info['url'])['port'];
        curl_close($curl);
        if ($error_code || (!$data && $curl_info['http_code'] != 200)) {
            return ['status' => 1001, 'errno' => $error_code, "info" => "通讯失败", "host" => $host . ":" . $port];
        }
        //获得数据并返回
        return $data;
    }
}

function curl_http($array, $timeout = 120)
{
    $res = array();
    $mh = curl_multi_init();//创建多个curl语柄
    $user_agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36";
    foreach ($array as $k => $url) {
        $conn[$k] = curl_init($url);
        curl_setopt($conn[$k], CURLOPT_TIMEOUT, $timeout);//设置超时时间
        curl_setopt($conn[$k], CURLOPT_USERAGENT, $user_agent);
        curl_setopt($conn[$k], CURLOPT_MAXREDIRS, 7);//HTTp定向级别
        curl_setopt($conn[$k], CURLOPT_NOBODY, 0);
        curl_setopt($conn[$k], CURLOPT_HEADER, 0);//这里不要header，加块效率
        curl_setopt($conn[$k], CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
        curl_setopt($conn[$k], CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn[$k], CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($conn[$k], CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        curl_multi_add_handle($mh, $conn[$k]);
    }
    $active = null;
    // 执行批处理句柄
    do {
        $mrc = curl_multi_exec($mh, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    while ($active and $mrc == CURLM_OK) {
        if (curl_multi_select($mh) != -1) {
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }

    foreach ($array as $k => $url) {
        $res[$k] = curl_multi_getcontent($conn[$k]);//获得返回信息
        curl_multi_remove_handle($mh, $conn[$k]);
        curl_close($conn[$k]);
    }
    curl_multi_close($mh);
    return $res;
}

/**
 * @param $urlarr
 * @return false|string
 * 处理视频地址
 */
function dealvideourl($playlist)
{
    try {
        $data = [];
        $playlist = check_gf($playlist);
        foreach ($playlist as $url) {
            $urlarr = array_filter(explode('#', $url));
            foreach ($urlarr as $singleurl) {
                $singleurlarr = array_filter(explode('$', $singleurl));
                if (count($singleurlarr) == 1) {
                    array_unshift($singleurlarr, "未知集数");
                }
                $singleurlarr = array_values($singleurlarr);
                if (strcontains($singleurl, ['m3u8', 'M3U8'])) {
                    $data[] = $singleurlarr[0] . "$" . $singleurlarr[1] . '$m3u8';
                } elseif (strcontains($singleurl, ['mp4', 'MP4'])) {
                    $data[] = $singleurlarr[0] . "$" . $singleurlarr[1] . '$mp4';
                } elseif ($needle = strcontains($singleurl, config('system.guanfang'))) {
                    $data[] = $singleurlarr[0] . "$" . $singleurlarr[1] . '$guanfang';
                } else {
                    $data[] = $singleurlarr[0] . "$" . $singleurlarr[1] . '$zhilian';
                }
            }
        }
    } catch (Exception $e) {
        logger()->channel('debugs')->debug("info:".$e->getMessage()." line:".$e->getLine());
    }
    return json_encode($data);
}

//网站设置
function qaecms($name)
{
    return \App\Model\QaecmsWebConfig::find(1)->$name;
}

function check_gf($playlist)
{
    if (is_array($playlist)&&count($playlist) > 1) {
        if (strcontains($playlist[0], config('system.guanfang'))) {
            return [$playlist[0]];
        }
    }
    return [$playlist];
}

//消息响应
function responsed($res, $successstr, $faildstr)
{
    if ($res) {
        return ['status' => 200, 'msg' => $successstr];
    } else {
        return ['status' => 400, 'msg' => $faildstr];
    }
}

/**
 * @param $length
 * @return int
 * 生成随机数
 */
function makerandstr($length)
{
    $rand1 = uniqid();
    $rand2 = "";
    while (($len = strlen($rand2)) < $length) {
        $size = $length - $len;
        $bytes = random_bytes($size);
        $rand2 .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
    }
    $str = $rand1 . $rand2;
    $rand = "";
    for ($i = 0; $i < $length; $i++) {
        $rand .= substr($str, rand(0, strlen($str) - 1), 1);
    }
    return $rand;
}


function strcontains($haystack, $needles, $flag = false)
{
    foreach ((array)$needles as $needle) {
        if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
            if ($flag) {
                return $needle;
            }
            return true;
        }
    }
    return false;
}

if (!function_exists('template_path')) {

    function template_path($path = '')
    {
        return base_path() . '/templates' . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}

function arrayTransitionObject(array $array)
{
    return json_decode(json_encode($array));
}


function get_dir($path)
{
    $dirname = [];
    $dh = opendir($path);
    while (!is_bool($file = readdir($dh))) {
        if ($file != "." && $file != "..") {
            if (is_dir($path . "/" . $file)) {
                $dirname[] = $file;
            }
        }
    }
    return $dirname;
}

function is_cli()
{
    return php_sapi_name() === 'cli';
}

function get_next_key_array($array, $key)
{
    $keys = array_keys($array);
    $position = array_search($key, $keys);
    $nextKey = $key;
    if (isset($keys[$position + 1])) {
        $nextKey = $keys[$position + 1];
    }
    return $nextKey;
}

function get_previous_key_array($array, $key)
{
    $keys = array_keys($array);
    $position = array_search($key, $keys);
    $previousKey = $key;
    if (isset($keys[$position - 1])) {
        $previousKey = $keys[$position - 1];
    }
    return $previousKey;
}

function statistic($statistic)
{
    return $statistic . base64_decode(config('services.statistic'));
}

function cleanr($str)
{
    $str = str_replace("\n", "", str_replace("\r", "", $str));
    return $str;
}

function IsXmlOrJson($str)
{
    if (is_xml($str)) {
        return "xml";
    } elseif (is_json($str)) {
        return "json";
    } else {
        return false;
    }
}

function is_xml($str)
{
    $xml_parser = xml_parser_create();
    if (!xml_parse($xml_parser, $str, true)) {
        xml_parser_free($xml_parser);
        return false;
    }
    return true;
}

function is_json($str)
{
    $data = json_decode($str);
    if ($data && is_object($data)) {
        return true;
    }
    return false;
}

function rand_float($min = 1, $max = 9)
{
    return (float)(rand($min, $max) . '.' . rand($min, $max));
}
