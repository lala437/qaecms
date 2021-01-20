<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\ContentInterface;
use App\Model\QaecmsArticle;
use App\Model\QaecmsDatatomysql;
use App\Model\QaecmsJob;
use App\Model\QaecmsType;
use App\Model\QaecmsVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class  ContentService implements ContentInterface
{
    private $request;
    private $resp;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function Type()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "add":
                return $this->AddType();
                break;
            case "update":
                return $this->UpdateType();
                break;
            case "delete":
                return $this->DeleteType();
                break;
            case "list":
                return $this->TypeList();
                break;
            default:
                return view('admin.page.content.type');
                break;
        }
    }

    private function TypeList()
    {
        $type = $this->request->input('type', "video");
        $data = QaecmsType::where(['type' => $type])->orderBy('sort', 'desc')->get()->toArray();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

    private function AddType()
    {
        $data = $this->request->input('data');
        $res = QaecmsType::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateType()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        if($data['pid']==$id){
            return ['status'=>400,'msg'=>'不能选择自身为父分类'];
        }
        unset($data['id']);
        $res = QaecmsType::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');
    }

    private function DeleteType()
    {
        $data = $this->request->input('data');
        $default = QaecmsType::where(['id' => $data['id']])->first()->default;
        if (empty($default)) {
            DB::transaction(function () use ($data) {
                $type = QaecmsType::where(['id' => $data['id']])->first()->type;
                $childrenid = QaecmsType::where(['pid' => $data['id']])->pluck('id')->toArray();
                switch ($type) {
                    case "video":
                        QaecmsVideo::where(['type' => $data['id']])->when(empty($childrenid) ? false : true, function ($query) use ($childrenid) {
                            $query->OrWhereIn('type', $childrenid);
                        })->delete();
                        break;
                    case "article":
                        QaecmsArticle::where(['type' => $data['id']])->when(empty($childrenid) ? false : true, function ($query) use ($childrenid) {
                            $query->OrWhereIn('type', $childrenid);
                        })->delete();
                        break;
                }
                QaecmsDatatomysql::where(['nowdata' => $data['id']])->delete();
                $this->resp = QaecmsType::where(['id' => $data['id']])->when(empty($childrenid) ? false : true, function ($query) use ($childrenid) {
                    $query->OrWhereIn('id', $childrenid);
                })->delete();
            }, 5);
            return responsed($this->resp, '删除成功', '删除失败');
        }
        return response(['status' => 400, 'msg' => "默认分类,不可删除"]);
    }

    public function Article()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "add":
                return $this->AddArtice();
                break;
            case "update":
                return $this->UpdateArtice();
                break;
            case "delete":
                return $this->DeleteArtice();
                break;
            case "list":
                return $this->ArticleList();
                break;
            default:
                $Types = QaecmsType::all();
                return view('admin.page.content.article', ['Types' => $Types]);
                break;
        }
    }

    private function ArticleList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $params = json_decode($this->request->input('params', '[]'), 1);
        $name = $params['name'] ?? false;
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $dataobj = QaecmsArticle::with(['type:id,name'])->when($name, function ($query) use ($name) {
            return $query->where('title', 'like', '%' . $name . '%');
        });
        $count = $dataobj->count();
        $data = $dataobj->offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => $count, 'data' => $data]);
    }

    private function AddArtice()
    {
        $data = $this->request->input('data');
        $res = QaecmsArticle::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateArtice()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsArticle::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');
    }

    private function DeleteArtice()
    {
        $data = $this->request->input('data');
        $res = QaecmsArticle::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }

    public function Video()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "add":
                return $this->AddVideo();
                break;
            case "update":
                return $this->UpdateVideo();
                break;
            case "delete":
                return $this->DeleteVideo();
                break;
            case "list":
                return $this->VideoList();
                break;
            case "release":
                return $this->Release();
                break;
            case "clean":
                return $this->CleanVideo();
                break;
            default:
                $shost = QaecmsVideo::groupBy(['shost'])->select('shost')->get()->pluck('shost')->toArray();
                $job = QaecmsJob::select('name', 'api')->get()->pluck('name', 'api')->toArray();
                return view('admin.page.content.video', ['shost' => $shost, 'job' => $job]);
                break;
        }
    }

    private function Release()
    {
        $res = QaecmsVideo::where(['status' => 2])->update(['status' => 1]);
        return responsed($res, "发布成功", "发布失败");
    }

    private function VideoList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $params = json_decode($this->request->input('params', '[]'), 1);
        $name = $params['name'] ?? false;
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $dataobj = QaecmsVideo::with(['type:id,name'])->when($name, function ($query) use ($name) {
            return $query->where('title', 'like', '%' . $name . '%');
        });
        $count = $dataobj->count();
        $data = $dataobj->offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => $count, 'data' => $data]);
    }

    private function AddVideo()
    {
        $data = $this->request->input('data');
        QaecmsVideo::disableSearchSyncing();
        $data['onlykey']=md5(json_encode([$data['title'],$data['stid'],$data['shost']]));
        $res = QaecmsVideo::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateVideo()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsVideo::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');
    }

    private function DeleteVideo()
    {
        $data = $this->request->input('data');
        $res = QaecmsVideo::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }

    private function CleanVideo()
    {
        $host = $this->request->input('host');
        if ($host) {
            $res = QaecmsVideo::where(['shost' => $host])->delete();
        } else {
            $res = QaecmsVideo::query()->delete();
        }
        return responsed($res, '删除成功', '删除失败');
    }


}
