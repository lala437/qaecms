<?php


namespace App\Librarys\Services\Index;


use App\Librarys\Interfaces\Index\IndexInterface;
use App\Model\QaecmsArticle;
use App\Model\QaecmsComment;
use App\Model\QaecmsCommentConfig;
use App\Model\QaecmsType;
use App\Model\QaecmsVideo;
use App\Model\QaecmsWebConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexService implements IndexInterface
{

    private $request;
    private $user;
    private $template;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->template = QaecmsWebConfig::find(1)->template ?? "default";
        $this->user = Auth::user();
    }

    public function Index()
    {
        return view($this->template . '.index');
    }

    public function List($type, $class, $cat, $pageNum, $limit, $order = "last")
    {
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        if ($class == "all") {
            $class = QaecmsType::where(['pid' => 0])->where(['type' => $type])->first()->id;
        }
        if ($cat == "all") {
            $cat_id = QaecmsType::where(['pid' => $class])->pluck('id')->toArray();
            array_unshift($cat_id, $class);
            if ($type == "video") {
                $shost = QaecmsVideo::where(['type' => $cat_id])->selectRaw('shost,count(1) num')->groupBy('shost')->orderBy('num','desc')->first();
                $obj = QaecmsVideo::where(['status' => 1])->when($this->user, function ($query) {
                    return $query->whereIn('vip', [0, $this->user->vip]);
                })->when(!$this->user, function ($query) {
                    return $query->where(['vip' => 0]);
                })->when($shost,function ($query)use ($shost){
                    return $query->where(['shost'=>$shost->shost]);
                })->whereIn('type', $cat_id);
            } else {
                $obj = QaecmsArticle::where(['status' => 1])->when($this->user, function ($query) {
                    return $query->whereIn('vip', [0, $this->user->vip]);
                })->when(!$this->user, function ($query) {
                    return $query->where(['vip' => 0]);
                })->whereIn('type', $cat_id);
            }
        } else {
            $cat_id = $cat;
            if ($type == "video") {
                $shost = QaecmsVideo::where(['type' => $cat_id])->selectRaw('shost,count(1) num')->groupBy('shost')->orderBy('num','desc')->first();
                $obj = QaecmsVideo::where(['status' => 1])->when($this->user, function ($query) {
                    return $query->whereIn('vip', [0, $this->user->vip]);
                })->when(!$this->user, function ($query) {
                    return $query->where(['vip' => 0]);
                })->when($shost,function ($query)use ($shost){
                    return $query->where(['shost'=>$shost->shost]);
                })->where(['type' => $cat_id]);
            } else {
                $obj = QaecmsArticle::where(['status' => 1])->when($this->user, function ($query) {
                    return $query->whereIn('vip', [0, $this->user->vip]);
                })->when(!$this->user, function ($query) {
                    return $query->where(['vip' => 0]);
                })->where(['type' => $cat_id]);
            }
        }
        $count = $obj->count();
        $data = $obj->orderBy($order, 'desc')->offset($page)->limit($limit)->get();
        $list['data'] = $data;
        $list['class'] = $class;
        $list['cat'] = $cat;
        $list['page'] = $pageNum;
        $list['limit'] = $limit;
        $list['totalpage'] = round($count / $limit, 0, PHP_ROUND_HALF_UP);
        return view($this->template . '.list', ['list' => arrayTransitionObject($list)]);
    }

    public function Detail($type, $id)
    {
        $data = $this->Authentication($type, $id);
        $detail = [];
        switch ($type) {
            case "video":
                if ($data['status'] == 200) {
                    $detail = $data['detail'];
                    $detail['content'] = qae_parse_video($detail['content']);
                } else {
                    return redirect("/");
                }
                break;
            case "article":
                if ($data['status'] == 200) {
                    $detail = $data['detail'];
                } else {
                    return redirect("/");
                }
                break;
        }
        return view($this->template . '.detail', ['detail' => arrayTransitionObject($detail)]);
    }

    public function Play($id)
    {
        $type = "video";
        $idarr = array_filter(explode('-', $id));
        $playid = $id;
        $id = $idarr[0];
        $playerid = $idarr[1] ?? null;
        $js = $idarr[2] ?? null;
        $data = $this->Authentication($type, $id, "play");
        if ($data['status'] == 200) {
            $this->NoteView($type, $id);
            $detail = $data['detail'];
            $detail['content'] = qae_parse_video($detail['content']);
            $play = qae_get_playurl($detail['content'], $playerid, $js);
            $detail['now'] = $play['playerid'] . $play['now'];
            $detail['playerid'] = $play['playerid'];
            $detail['prev'] = qae_play_prevornext($id . '-' . $play['playerid'] . '-' . $play['prev']);
            $detail['next'] = qae_play_prevornext($id . '-' . $play['playerid'] . '-' . $play['next']);
            $detail['play'] = qae_playurl($play['playurl'],$detail['next']);
            $detail['type'] = $detail['type']['name'];
            qae_play_history($detail['title'],$id,$playid);
            $samesource = QaecmsVideo::where(['title'=>$detail['title']])->where(['status'=>1])->get();
            return view($this->template . '.play', ['detail' => arrayTransitionObject($detail),'samesource'=>$samesource]);
        }
        return redirect("/");
    }

    public function Search($type, $wd)
    {
        if(filled($wd)){
            switch ($type) {
                case "video":
                    if (qae_search()) {
                        $data = QaecmsVideo::search($wd)->where('status', 1)->get();
                        $data2 = QaecmsVideo::where('title', 'like', '%' . $wd . '%')->where(['status' => 1])->get();
                        $data->merge($data2);
                    } else {
                        $data = QaecmsVideo::where('title', 'like', '%' . $wd . '%')->where(['status' => 1])->get();
                    }
                    $count = count($data);
                    $search['data'] = $data;
                    $search['count'] = $count;
                    return view($this->template . '.search', ['search' => arrayTransitionObject($search)]);
                    break;
            }
        }
    }


    private function NoteView($type, $id)
    {
        switch ($type) {
            case "video":
                DB::transaction(function () use ($id) {
                    $detail = QaecmsVideo::where(['status' => 1])->where(['id' => $id])->first();
                    if ($detail) {
                        $visitors = bcadd($detail->visitors, 1);
                        QaecmsVideo::where(['id' => $id])->update(['visitors' => $visitors]);
                    }
                }, 3);
                break;
            case "article":
                DB::transaction(function () use ($id) {
                    $detail = QaecmsArticle::where(['status' => 1])->where(['id' => $id])->first();
                    if ($detail) {
                        $visitors = bcadd($detail->visitors, 1);
                        QaecmsArticle::where(['id' => $id])->update(['visitors' => $visitors]);
                    }
                }, 3);
                break;
        }
    }

    private function Authentication($type, $id, $action = null)
    {
        $detail = null;
        switch ($type) {
            case "video":
                $detail = QaecmsVideo::with('type:id,name')->where(['status' => 1])->where(['id' => $id])->first();
                break;
            case "article":
                $detail = QaecmsArticle::with('type:id,name')->where(['status' => 1])->where(['id' => $id])->first();
                break;
        }
        if (!$detail) {
            return ["status" => 404, "msg" => "没有找到相关视频"];
        }

        $vip = $detail->vip;
        if ($this->user) {
            $uservip = $this->user->vip;
            if ($vip > $uservip) {
                return ["status" => 403, "msg" => "权限不足"];
            }
            if ($action == "play") {
                $userintegrall = $this->user->integrall;
                $integrall = $detail->integrall;
                if ($integrall > 0) {
                    if ($userintegrall > $integrall) {
                        $this->user->update(['integrall' => $userintegrall - $integrall]);
                    } else {
                        return ["status" => 403, "msg" => "余额不足,请充值"];
                    }
                }
            }
            return ['status' => 200, 'detail' => $detail->toArray()];
        }
        if ($vip == 0) {
            return ['status' => 200, 'detail' => $detail->toArray()];
        }
        return ["status" => 404, "msg" => "没有找到相关视频"];
    }


    public function SearchComplete($type)
    {
        $wd = $this->request->input('keyword');
        switch ($type) {
            case "video":
                if (qae_search()) {
                    $data = QaecmsVideo::search($wd)->where('status', 1)->get();
                    $data2 = QaecmsVideo::where('title', 'like', '%' . $wd . '%')->select('title')->where(['status' => 1])->get();
                    $data->merge($data2);
                } else {
                    $data = QaecmsVideo::where('title', 'like', '%' . $wd . '%')->where(['status' => 1])->select('title')->get();
                }
                $kw = [];
                if ($data) {
                    $kw = $data->pluck('title');
                }
                return ['suglist' => $kw];
                break;
        }
    }

   public function Comments()
   {
       $status = QaecmsCommentConfig::first()->status;
       if($status==0){
           die('留言板未开启');
       }
      if($this->request->isMethod('get')){
          $comments = QaecmsComment::orderBy('created_at','desc')->get();
          if(filled($comments)){
              $comments = $comments->groupBy('pid');
              $comments['root'] = $comments[''];
              unset($comments['']);
          }
          return view('comments.show',['comments'=>$comments]);
      }else{
          $data = $this->request->input();
          $data = qae_verify_comment($data);
          if($data){
              $insterdata = ['name'=>'游客','pid'=>null,'content'=>$data['content']];
              $res = QaecmsComment::create($insterdata);
              if($res){
                  return back();
              }
          }else{
              return back();
          }
      }
   }

}
