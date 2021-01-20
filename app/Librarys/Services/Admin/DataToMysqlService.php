<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\DataToMysqlInterface;
use App\Librarys\Progress\src\Loading;
use App\Model\QaecmsCollectdata;
use App\Model\QaecmsDatatomysql;
use App\Model\QaecmsType;
use App\Model\QaecmsVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataToMysqlService implements DataToMysqlInterface
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function DataToMysql()
    {
        $action = $this->request->input('action');
        switch ($action) {
            case "add":
                return $this->AddDataToMysql();
                break;
            case "updatebak":
                return $this->UpdateDataToMysql();
                break;
            case "delete":
                return $this->DeleteDataToMysql();
                break;
            case "list":
                return $this->DataToMysqlList();
                break;
            case "parsedata":
                return $this->ParseMetaData();
                break;
            case "push":
                return $this->InsertData();
                break;
            case "mulitepush":
                return $this->MuliteInsertData();
                break;
            default:
                return view('admin.page.data.datatomysql');
                break;
        }
    }

    private function ParseMetaData()
    {
        $data = [];
        $type = $this->request->input('type');
        $types1 = array_filter(QaecmsCollectdata::selectRaw('distinct stype')->where(['type'=>$type])->get()->pluck('stype')->toArray());
        $types2 = QaecmsType::where(['type'=>$type])->get()->pluck('name')->toArray();
        $totaltypes = array_merge($types1,$types2);
        $bindmeta = QaecmsDatatomysql::select('metadata')->where(['type'=>$type])->get()->pluck('metadata')->toArray();
        $types = array_where($totaltypes, function ($value, $key) use ($bindmeta) {
            return !in_array($value, $bindmeta);
        });
        foreach (array_unique($types) as $type) {
            $data[] = ['name' => '<span style="color: red">'.$type .'(当前分类未绑定)</span>', 'value' => $type];
        }
        return $data;
    }

    private function DataToMysqlList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $dataobj = QaecmsDatatomysql::with(['nowtype:id,name']);
        $count = $dataobj->count();
        $data = $dataobj->offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => $count, 'data' => $data]);
    }

    private function AddDataToMysql()
    {
        $data = $this->request->input('data');
        $res = QaecmsDatatomysql::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateDataToMysql()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsDatatomysql::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');

    }

    private function DeleteDataToMysql()
    {
        $data = $this->request->input('data');
        $res = QaecmsDatatomysql::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }


    private function InsertData()
    {
        $id = $this->request->input('id');
        $load = new Loading(Loading::LOAD_TYPE_STRAIGHT);
        $databind = QaecmsDatatomysql::find($id);
        $nowdata = $databind->nowdata;
        $metadata = $databind->metadata;
        $type = $databind->type;
        switch ($type) {
            case "video":
                $databind = [$metadata => $nowdata];
                $insertdata = QaecmsCollectdata::where(['stype' => $metadata])->get();
                $this->VideoToMysql($insertdata, $databind, $load);
                break;
            case "article":
                $this->ArticleToMysql($metadata, $nowdata, $load);
                break;
        }
        QaecmsDatatomysql::where(['id' => $id])->update(['lasttime' => date('Y-m-d H:i:s', time())]);
        unset($load);
    }

    private function MuliteInsertData()
    {
        $load = new Loading(Loading::LOAD_TYPE_STRAIGHT);
        $idjson = $this->request->input('id');
        $idarr = json_decode($idjson, 1);
        $databinds = QaecmsDatatomysql::whereIn('id', $idarr)->select(['type', 'metadata', 'nowdata'])->get()->groupBy('type')->toArray();
        foreach ($databinds as $key => $databind) {
            switch ($key) {
                case "video":
                    $databind = collect($databind)->pluck('nowdata', 'metadata')->toArray();
                    $metadatas = array_keys($databind);
                    $insertdata = QaecmsCollectdata::whereIn('stype', $metadatas)->get();
                    $this->VideoToMysql($insertdata, $databind, $load);
                    break;
                case "article":
                    break;
            }
        }
        QaecmsDatatomysql::whereIn('id', $idarr)->update(['lasttime' => date('Y-m-d H:i:s', time())]);
        unset($load);
    }

    private function VideoToMysql($insertdata, $binddata, $load)
    {
        $count = count($insertdata->toArray());
        if ($count) {
            $load->setTotal($count);
            $load->init();
            foreach ($insertdata as $insert) {
                $insert = $insert->toArray();
                $insert['type'] = $binddata[$insert['stype']];
                $insert['editor'] = $insert['editor'] ?? "未知";
                $insert['score'] = $insert['score'] ?? rand(1, 9);
                $insert['status'] = 1;
                $insert['vip'] = 0;
                $collectid = $insert['id'];
                unset($insert['id']);
                QaecmsVideo::disableSearchSyncing();
                $res = QaecmsVideo::updateOrCreate(['onlykey' => $insert['onlykey']], $insert);
                if ($res) {
                    QaecmsCollectdata::find($collectid)->delete();
                }
                $load->progress();
            }
        } else {
            echo "当前分类下没有未入库数据,请检查未入库数据中是否有该分类内容";
        }
    }

    private function ArticleToMysql($metadata, $nowdata, $load)
    {

    }

    public function NoToSqlData()
    {
        $action = $this->request->input('action');
        switch ($action) {
            case "update":
                return $this->UpdateNoToSql();
                break;
            case "delete":
                return $this->DeleteNoToSql();
                break;
            case "list":
                return $this->NoToSqlList();
                break;
            case "push":
                return $this->PushNoToSql();
                break;
            case "clean":
                return $this->CleanNoToSql();
                break;
            case "createandbind":
                return $this->CreateAndBind();
                break;
            default:
                return view('admin.page.data.notosqldata');
                break;
        }
    }

    private function NoToSqlList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $params = json_decode($this->request->input('params', '[]'), 1);
        $name = $params['name'] ?? false;
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $dataobj = QaecmsCollectdata::with(['source:api,name'])->when($name, function ($query) use ($name) {
            return $query->where('title', 'like', '%' . $name . '%');
        });
        $count = $dataobj->count();
        $data = $dataobj->offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => $count, 'data' => $data]);
    }

    private function DeleteNoToSql()
    {
        $data = $this->request->input('data');
        $res = QaecmsCollectdata::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }

    private function UpdateNoToSql()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsCollectdata::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');
    }

    private function CleanNoToSql()
    {
        $res = QaecmsCollectdata::query()->delete();
        return responsed($res, '删除成功', '删除失败');
    }

    private function PushNoToSql()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        $dataobj = QaecmsCollectdata::find($id);
        $metadata = $dataobj->stype;
        $databindobj = QaecmsDatatomysql::where(['metadata' => $metadata])->where(['type' => "video"]);
        $databind = $databindobj->first();
        if ($databind) {
            $insert = $dataobj->toArray();
            $insert['type'] = $databind->nowdata;
            $insert['editor'] = $insert['editor'] ?? "未知";
            $insert['score'] = $insert['score'] ?? rand(1, 9);
            $insert['status'] = 1;
            $insert['vip'] = 0;
            unset($insert['id']);
            $res = QaecmsVideo::updateOrCreate(['onlykey' => $insert['onlykey']], $insert);
            if ($res) {
                QaecmsCollectdata::find($id)->delete();
                return ['status' => 200, "msg" => "入库成功"];
            }
            $databindobj->update(['lasttime' => date("Y-m-d H:i:s", time())]);
        }
        return ['status' => 400, 'msg' => "入库失败,可能该数据没有绑定相关分类,请检查"];
    }

    private function CreateAndBind()
    {
        $data = $this->request->input('data');
        $metadata = $data['metadata'];
        $typepid = $data['typepid'];
        $typename = $data['name'];
        $type = $data['type'];
        $sort = $data['sort'];
        $typeobj = QaecmsType::updateOrCreate(['type' => $type, 'name' => $typename], ['pid' => $typepid, 'sort' => $sort]);
        $nowdata = $typeobj->id;
        $res = QaecmsDatatomysql::updateOrCreate(['metadata' => $metadata], ['nowdata' => $nowdata, 'type' => $type]);
        return responsed($res, '绑定成功', '绑定失败');
    }
}
