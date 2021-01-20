<?php
//静态资源
function qae_asset($path = '')
{
    return asset("/templates" . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $path));
}

//视频列表
function qae_video($typeid, $vip = 0, $order = "hot", $page = 0, $limit = 10)
{
    switch ($order) {
        case "hot":
            $order = "visitors";
            break;
        case "time":
            $order = "last";
            break;
        case "score":
            $order = "score";
            break;
    }
    $typepid = \App\Model\QaecmsType::where(['id' => $typeid])->select('pid')->first()->pid;
    if ($typepid == 0) {
        $type = \App\Model\QaecmsType::where(['pid' => $typeid])->select('id')->pluck('id')->toArray();
        array_unshift($type, $typeid);
    } else {
        $type = [$typeid];
    }
    $videos = \App\Model\QaecmsVideo::whereIn('type', $type)->where(['vip' => $vip])->where(['status' => 1])->orderBy($order, "desc")->offset($page)->limit($limit)->get();
    return $videos;
}


//导航列表

function qae_nav($pid = 0)
{
    $navs = \App\Model\QaecmsNav::where(['pid' => $pid])->where(['status' => 1])->orderBy('sort', 'desc')->get();
    return $navs;
}

//轮播列表
function qae_carousel($location = "index", $limit = 10)
{
    $carousels = \App\Model\QaecmsCarousel::where(['location' => $location])->where(['status' => 1])->orderBy('sort', 'desc')->offset(0)->limit($limit)->get();
    return $carousels;
}

function qae_class_type($type = "video", $pid = 0)
{
    $types = \App\Model\QaecmsType::where(['pid' => $pid])->where(['type' => $type])->orderBy('sort', 'desc')->get();
    return $types;
}

function qae_page($nowpage, $totalpage, $shownum)
{
    $endpage = bcadd($nowpage, $shownum);
    return $endpage > $totalpage ? $totalpage : $endpage;
}

function qae_parse_video($video)
{
    $videoarr = json_decode($video, 1);
    $data = [];
    $playlist = [];
    foreach ($videoarr as $videoaddress) {
        $tmparr = array_filter(explode("$", $videoaddress));
        if (count($tmparr) == 3) {
            $playlist[] = ['name' => $tmparr[0], 'url' => $tmparr[1], 'type' => strtolower($tmparr[2])];
        } else {
            continue;
        }
    }
    $playlistcollect = collect($playlist);
    $playlist = $playlistcollect->groupBy('type')->all();
    $playerarr = \App\Model\QaecmsPlayer::select(['id', 'type', 'name'])->where(['status' => 1])->orderBy('sort', 'desc')->get() ?? [];
    if (count($playerarr) > 0) {
        foreach ($playerarr as $player) {
            if (array_key_exists($player->type, $playlist)) {
                $play = $playlist[$player->type];
                foreach ($play as $sort => $value) {
                    $js = $sort + 1;
                    $data[$player->name][$js] = ['playerid' => $player->id, 'episode' => $value['name'], "href" => $value['url'], 'js' => $js];
                }
            }
        }
    }
    return $data;
}


function qae_get_playurl($content, $playerid = null, $js = null)
{
    $playurl = "";
    if ($content) {
        if ($playerid) {
            $player = \App\Model\QaecmsPlayer::where(['status' => 1])->find($playerid) ?? null;
            if ($player) {
                $playcontent = $content[$player->name];
                $total = count($playcontent);
                if ($js && $js > 0) {
                    $play = $js > $total ? (array_pop($playcontent))['href'] : $playcontent[$js]['href'];
                    $prev = $js - 1 <= 0 ? $js : $js - 1;
                    $next = $js + 1 >= $total ? $total : $js + 1;
                } else {
                    $play = $playcontent[1]['href'];
                    $js = 1;
                    $prev = 1;
                    $next = $total > 1 ? 2 : 1;
                }
                $playurl = $player->url . $play;
            }
        } else {
            $firstkey = array_key_first($content);
            $player = \App\Model\QaecmsPlayer::where(['status' => 1])->where(['name' => $firstkey])->orderBy('sort', 'desc')->first() ?? null;
            if ($player) {
                $playerid = $player->id;
                $playcontent = $content[$firstkey];
                $total = count($playcontent);
                if ($js && $js > 0) {
                    $play = $js > $total ? (array_pop($playcontent))['href'] : $playcontent[$js]['href'];
                    $prev = $js - 1 <= 0 ? $js : $js - 1;
                    $next = $js + 1 >= $total ? $total : $js + 1;
                } else {
                    $play = $playcontent[1]['href'];
                    $js = 1;
                    $prev = 1;
                    $next = $total > 1 ? 2 : 1;
                }
                $playurl = $player->url . $play;
            }
        }
    }
    return ['playurl' => $playurl, 'playerid' => $playerid, 'prev' => $prev ?? 1, 'next' => $next ?? 1, 'now' => $js];
}


function qae_xml_parse($html)
{
    $xml = @simplexml_load_string($html, null, LIBXML_NOCDATA);
    if (empty($xml)) {
        $labelRule = '<pic>' . "(.*?)" . '</pic>';
        $labelRule = '/' . str_replace('/', '\/', $labelRule) . '/is';
        preg_match_all($labelRule, $html, $temparr);
        $ec = false;
        foreach ($temparr[1] as $dd) {
            if (strpos($dd, '[CDATA') === false) {
                $ec = true;
                $ne = '<pic>' . '<![CDATA[' . $dd . ']]>' . '</pic>';
                $html = str_replace('<pic>' . $dd . '</pic>', $ne, $html);
            }
        }
        if ($ec) {
            $xml = @simplexml_load_string($html, null, LIBXML_NOCDATA);
        }
        if (empty($xml)) {
            return ['code' => 1002, 'msg' => 'XML格式不正确'];
        }
    }
    return $xml;
}


function qaecms_echo($str)
{
    echo $str;
    @ob_get_clean();
    flush();
}


function qae_search($type = "algolia")
{
    $search = \App\Model\QaecmsSearchConfig::where(['type' => $type])->first();
    if ($search && $search->status == 1) {
        return true;
    }
    return false;
}

function qae_link()
{
    $links = \App\Model\QaecmsLink::where(['status' => 1])->orderBy('sort', 'desc')->get();
    return $links;
}

function qae_ad($mark = null)
{
    if ($mark) {
        $ad = \App\Model\QaecmsAd::where(['status' => 1])->where(['mark' => $mark])->first();
        if ($ad) {
            return $ad->content;
        }
    }
    return "";
}

function qae_history($length=10)
{
    $history = \Illuminate\Http\Request::capture()->cookie('history', null);
    if (filled($history)) {
        $historyarr = json_decode($history,1);
        if(count($historyarr)>$length){
            array_splice($historyarr,$length);
        }
        return arrayTransitionObject($historyarr);
    }
    return [];
}

function qae_qrcode($url = "www.qaecms.com")
{
    return \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($url);
}

function qae_rolling_curl($urls)
{
    $mh = curl_multi_init();
    $ch = array();
    $chunck = 10; //并发控制数
    $all = count($urls);//所有的请求url数组
    $output = [];
    $chunck = $all > $chunck ? $chunck : $all;
    $options = array(
        CURLOPT_HEADER => FALSE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20100101 Firefox/6.0'
    );

    for ($i = 0; $i < $chunck; $i++) {
        $ch[$i] = curl_init();
        curl_setopt($ch[$i], CURLOPT_URL, $urls[$i]);
        curl_setopt_array($ch[$i], $options);
        curl_multi_add_handle($mh, $ch[$i]);
    }

    do {
        while (($execrun = curl_multi_exec($mh, $running)) == CURLM_CALL_MULTI_PERFORM) ;
        if ($execrun != CURLM_OK) break;
        // a request was just completed -- find out which one
        while ($done = curl_multi_info_read($mh)) {
            //获取已经返回的url在urls数组里德的index
            $index = array_search($done['handle'], $ch);

            $info = curl_getinfo($done['handle']);

            if ($info['http_code'] == 200) {
                $output[] = qae_xml_parse(curl_multi_getcontent($ch[$index]));
                // start a new request (it's important to do this before removing the old one)
                $next = $i++;// increment i
                if ($next < $all) {
                    $ch[$next] = curl_init();
                    $options[CURLOPT_URL] = $urls[$next];//将下一个请求添加到队列
                    curl_setopt_array($ch[$next], $options);
                    curl_multi_add_handle($mh, $ch[$next]);
                }
                // remove the curl handle that just completed
                curl_multi_remove_handle($mh, $done['handle']);
            } else {
                // request failed.  add error handling.
            }
        }
    } while ($running);
    curl_multi_close($mh);
    logger()->channel('debugs')->debug("总操作:" . $all . "--成功:" . count($output));
    return $output;
}

function qae_play_now($id, $playerid, $js)
{
    $str = implode('-', [$id, $playerid, $js]);
    return route('qaecmsindex.play', ['id' => $str]);
}

function qae_play_prevornext($i)
{
    return route('qaecmsindex.play', ['id' => $i]);
}

function qae_playurl($play, $next = null)
{
    if ($next && mb_strpos($play, '?') !== false) {
        $url = $play . '&next=' . $next;
    } else {
        $url = $play;
    }
    return $url;
}

function qae_user()
{
    $user = \Illuminate\Support\Facades\Auth::user();
    if ($user) {
        return $user;
    }
    return false;
}

function qae_pay()
{
    $pay = \App\Model\QaecmsPayConfig::first();
    if ($pay && $pay->status == 1) {
        return true;
    }
    return false;
}

function qae_sync_image($name, $url)
{
    $path = public_path('upload/image/' . $name . '.jpg');
    return file_exists($path) ? '/upload/image/' . $name . '.jpg' : $url;
}

function qae_parse_url(array $urlarr)
{
    $uriarr = [];
    foreach ($urlarr as $url => $key) {
        $httpsurl = str_replace("http://", "https://", $url);
        $uriarr[$httpsurl] = $key;
    }
    return $uriarr;
}

function qae_verify_comment($data)
{
    $validator = validator($data, [
        'content' => 'required',
        'captcha' => 'required|captcha',
    ], [
        'captcha.required' => '验证码不能为空',
        'captcha.captcha' => '请输入正确的验证码',
    ]);
    if (filled($validator->errors())) {
        return false;
    }
    $content = strip_tags(htmlspecialchars($data['content']));
    $filter = \App\Model\QaecmsCommentConfig::first()->arg1;
    if (filled($filter)) {
        $filterarr = array_filter(explode('|', $filter));
        if (strcontains($content, $filterarr)) {
            return false;
        } else {
            return ['content' => $content];
        }
    }
    return ['content' => $content];
}

function qae_play_history($title, $id)
{
    $oldhistory = \Illuminate\Http\Request::capture()->cookie('history', null);
    $oldarr = [];
    if ($oldhistory) {
        $oldarr = json_decode($oldhistory, 1);
    }
    $newarr = ['title' => $title, 'url' => route('qaecmsindex.play', ['id' => $id])];
    $key = array_search($newarr,$oldarr);
    if($key!==false){
        unset($oldarr[$key]);
        $oldarr = array_values($oldarr);
    }
    array_unshift($oldarr, $newarr);
    if (count($oldarr) > 20) {
        array_splice($oldarr, 20);
    }
    setcookie('history', json_encode($oldarr), time() + 86400,'/');
}

