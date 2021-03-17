<?php

namespace App\Librarys\Services\Method;

use App\Http\Controllers\Common\CommonController;
use App\Librarys\Progress\src\Loading;
use App\Model\QaecmsDatatomysql;
use App\Model\QaecmsVideo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MethodService
{

    private $api;
    private $method;
    private $proxy;
    private $videomodel;

    public function __construct($job)
    {
        $this->api = $job->api;
        $this->method = $job->method;
        $this->proxy = $job->proxy;
        $this->videomodel = new QaecmsVideo();
    }

    public function Method()
    {
        switch ($this->method) {
            case "video":
                return $this->ApiVideoCollect();
                break;
        }
    }


    private function ApiVideoCollect()
    {
        $load = is_cli() ? null : new Loading(Loading::LOAD_TYPE_STRAIGHT);
        $res = curl_get($this->api . "?" . http_build_query(['ac' => 'list']), $this->proxy);
        if ($res) {
            $type = IsXmlOrJson($res);
            switch ($type) {
                case "xml":
                    $this->XmlData($res, $load);
                    break;
                case "json":
                    $this->JsonData($res, $load);
                    break;
                default:
                    qaecms_echo("无效的格式");
                    break;
            }
        } else {
            qaecms_echo("无效的格式");
        }
    }

    private function XmlData($res, $load = null)
    {
        if (blank($load)) {
            echo date('Y-m-d H:i:s', time()) . "开启执行采集\n";
        }
        $data = qae_xml_parse($res);
        $attributes['pagecount'] = (string)$data->list->attributes()->pagecount;
        $urls = [];
        for ($i = 1; $i <= $attributes['pagecount']; $i++) {
            $urls[] = $this->api . '?' . http_build_query(['ac' => 'videolist', 'pg' => $i]);
        }
        $array_chunks = array_chunk($urls, 100);
        $databind = QaecmsDatatomysql::where(['type' => 'video'])->get()->pluck('nowdata', 'metadata')->toArray();
        $common = new CommonController();
        if (filled($load)) {
            $load->setTotal(count($array_chunks));
            $load->init();
        }
        foreach ($array_chunks as $key => $urlarr) {
            if (filled($load)) {
                $load->progress();
            }
            $datas = [];
            $videos = [];
            $fail = true;
            $retry = 0;
            do {
                $retry++;
                $res = $common->MuliteRequest($urlarr, $this->proxy, "xml");
                $datas = array_merge($datas, $res['data']);
                $urlarr = $res['failurl'];
                if (count($urlarr) == 0 || $retry == 4) {
                    $fail = false;
                }
            } while ($fail);
            if (!is_array($res) || empty($res)) {
                continue;
            }
            foreach ($datas as $vdata) {
                if (isset($vdata['code'])) {
                    continue;
                }
                if ($vdata) {
                    $list = $vdata->list->children();
                    foreach ($list as $l) {
                        if (Str::contains((string)$l->type, config('qaecms.forbid_type'))) {
                            continue;
                        }
                        $content = [];
                        $content['type'] = "video";
                        $content['title'] = (string)$l->name;
                        $content['sid'] = (string)$l->id;
                        $content['stid'] = (string)$l->tid;
                        $content['stype'] = trim((string)$l->type) ?? "未知";
                        $content['lang'] = (string)$l->lang;
                        $content['area'] = (string)$l->area;
                        $content['year'] = (string)$l->year;
                        $content['note'] = (string)$l->note;
                        $content['actor'] = (string)$l->actor;
                        $content['director'] = (string)$l->director;
                        $content['introduction'] = strip_tags((string)$l->des);
                        $content['seokey'] = $content['title'] ?? "暂无";
                        $content['content'] = dealvideourl($l->dl->dd);
                        $content['last'] = (string)$l->last;
                        $content['shost'] = $this->api;
                        $content['created_at'] = date('Y-m-d H:i:s', time());
                        $content['onlykey'] = md5(json_encode([$content['title'], $content['stid'], $content['shost']]));
                        $content['thumbnail'] = qae_sync_image($content['onlykey'], (string)$l->pic);
                        $videos[] = $content;
                    }
                }
            }
            $this->VideoToSql($videos, $databind);
        }
        $this->UpdateLastToSql();
        if (filled($load)) {
            unset($load);
        } else {
            echo date('Y-m-d H:i:s', time()) . "结束采集\n";
        }
    }

    private function JsonData($res, $load = null)
    {
        if (blank($load)) {
            echo date('Y-m-d H:i:s', time()) . "开启执行采集\n";
        }
        $data = json_decode($res);
        $attributes['pagecount'] = $data->pagecount;
        $urls = [];
        for ($i = 1; $i <= $attributes['pagecount']; $i++) {
            $urls[] = $this->api . '?' . http_build_query(['ac' => 'videolist', 'pg' => $i]);
        }
        $array_chunks = array_chunk($urls, 100);
        $databind = QaecmsDatatomysql::where(['type' => 'video'])->get()->pluck('nowdata', 'metadata')->toArray();
        $common = new CommonController();
        if (filled($load)) {
            $load->setTotal(count($array_chunks));
            $load->init();
        }
        foreach ($array_chunks as $key => $urlarr) {
            if (filled($load)) {
                $load->progress();
            }
            $datas = [];
            $videos = [];
            $fail = true;
            $retry = 0;
            do {
                $retry++;
                $res = $common->MuliteRequest($urlarr, $this->proxy, "json");
                $datas = array_merge($datas, $res['data']);
                $urlarr = $res['failurl'];
                if (count($urlarr) == 0 || $retry == 4) {
                    $fail = false;
                }
            } while ($fail);
            if (!is_array($res) || empty($res)) {
                continue;
            }
            foreach ($datas as $vdata) {
                if ($vdata) {
                    $list = $vdata->list;
                    foreach ($list as $l) {
                        if (Str::contains((string)$l->type_name, config('qaecms.forbid_type'))) {
                            continue;
                        }
                        $content = [];
                        $content['type'] = "video";
                        $content['title'] = (string)$l->vod_name;
                        $content['sid'] = (integer)$l->vod_id;
                        $content['stid'] = (integer)$l->type_id;
                        $content['stype'] = trim((string)$l->type_name) ?? "未知";
                        $content['lang'] = (string)$l->vod_lang;
                        $content['area'] = (string)$l->vod_area;
                        $content['year'] = (string)$l->vod_year;
                        $content['note'] = (string)$l->vod_version;
                        $content['actor'] = (string)$l->vod_actor;
                        $content['director'] = (string)$l->vod_director;
                        $content['introduction'] = strip_tags((string)$l->vod_content);
                        $content['seokey'] = $content['title'] ?? "暂无";
                        $content['content'] = dealvideourl($l->vod_play_url);
                        $content['last'] = (string)$l->vod_time;
                        $content['shost'] = $this->api;
                        $content['created_at'] = date('Y-m-d H:i:s', time());
                        $content['onlykey'] = md5(json_encode([$content['title'], $content['stid'], $content['shost']]));
                        $content['thumbnail'] = qae_sync_image($content['onlykey'], (string)$l->vod_pic);
                        $videos[] = $content;
                    }
                }
            }
            $this->VideoToSql($videos, $databind);
        }
        $this->UpdateLastToSql();
        if (filled($load)) {
            unset($load);
        } else {
            echo date('Y-m-d H:i:s', time()) . "结束采集\n";
        }

    }

    private function VideoToSql($videos, $databind)
    {
        $insertdata = [];
        $notosqldata = [];
        foreach ($videos as $video) {
            if (array_key_exists($video['stype'], $databind)) {
                $video['type'] = $databind[$video['stype']];
                $video['editor'] = $video['editor'] ?? "未知";
                $video['score'] = $video['score'] ?? rand_float(1, 9);
                $video['status'] = 1;
                $video['vip'] = 0;
                unset($video['id']);
                $insertdata[] = $video;
            } else {
                $notosqldata[] = $video;
            }
        }
        if (sizeof($notosqldata) > 0) {
            foreach (array_chunk($notosqldata, 100) as $data) {
                DB::table('qaecms_collectdatas')->insertOrIgnore($data);
            }
        }
        if (sizeof($insertdata) > 0) {
            foreach (array_chunk($insertdata, 100) as $insert) {
                $this->videomodel->insertOrUpdate($insert, ['last' => DB::raw('values(`last`)'), 'content' => DB::raw('values(`content`)'), 'sid' => DB::raw('values(`sid`)'), 'updated_at' => date('Y-m-d H:i:s', time()), 'thumbnail' => DB::raw('values(`thumbnail`)'),]);
            }
//            foreach ($insertdata as $idata) {
//                QaecmsVideo::updateOrCreate(['title' => $idata['title'], 'stid' => $idata['stid'], 'shost' => $idata['shost']], $idata);
//            }
        }
    }

    private function UpdateLastToSql()
    {
        QaecmsDatatomysql::query()->update(['lasttime' => date('Y-m-d H:i:s', time())]);
    }
}
