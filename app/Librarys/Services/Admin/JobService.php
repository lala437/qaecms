<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\JobInterface;
use App\Librarys\Services\Method\MethodService;
use App\Model\QaecmsDatatomysql;
use App\Model\QaecmsJob;
use App\Model\QaecmsType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function GuzzleHttp\Psr7\str;

class JobService implements JobInterface
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function Job()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "bindtype":
                return $this->BindType();
                break;
            case "add":
                return $this->AddJob();
                break;
            case "update":
                return $this->UpdateJob();
                break;
            case "delete":
                return $this->DeleteJob();
                break;
            case "list":
                return $this->JobList();
                break;
            case "exc":
                return $this->ExcJob();
                break;
            default:
                return view('admin.page.job.job');
                break;
        }
    }


    private function BindType()
    {
        $data = $this->request->input('data');
        $job = QaecmsJob::find($data['id']);
        $api = $job->api;
        $res = curl_get($api,$job->proxy);
        $datatype = IsXmlOrJson($res);
        $types = [];
        switch ($datatype){
            case "xml":
                $content = qae_xml_parse($res);
                $types = (array)$content->class->ty;
                unset($types['@attributes']);
            break;
            case "json":
                $content = json_decode($res,1);
                $types = array_column($content['class'],'type_name');
                break;
        }
        $faild = [];
        $success = [];
        foreach ($types as $type) {
            if (Str::contains($type, config('qaecms.forbid_type'))) {
                continue;
            }
            $nowtype = QaecmsType::where(['name' => $type])->where(['type' => 'video'])->select('id')->first();
            if ($nowtype) {
                QaecmsDatatomysql::updateOrCreate(['type' => "video", 'metadata' => $type, 'nowdata' => $nowtype->id], []);
                $success[] = $type;
            } else {
                $faild[] = $type;
            }
        }
        if (count($success) > 0) {
            $job->update(['bindstatus' => 1]);
            return response(['status' => 200, 'faild' => $faild]);
        }else{
            return response(['status' => 400, 'faild' => $faild]);
        }

    }


    private function JobList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $data = QaecmsJob::offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

    private function AddJob()
    {
        $data = $this->request->input('data');
        $res = QaecmsJob::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateJob()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsJob::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');

    }

    private function DeleteJob()
    {
        $data = $this->request->input('data');
        $res = QaecmsJob::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }

    private function ExcJob()
    {
        $id = $this->request->input('id');
        $job = QaecmsJob::find($id);
        $bindstatus = $job->bindstatus;
        if($bindstatus){
            $method = new MethodService($job);
            $method->Method();
            $job->update(['lasttime' => date("Y-m-d H:i:s", time())]);
        }
    }

}
