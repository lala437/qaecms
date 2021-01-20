<?php


namespace App\Librarys\Services\Admin;


use App\Http\Controllers\Common\CommonController;
use App\Librarys\Interfaces\Admin\AnnexInterface;
use App\Librarys\Progress\src\Loading;
use App\Model\QaecmsAnnex;
use App\Model\QaecmsArticle;
use App\Model\QaecmsVideo;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnexService implements AnnexInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function Annex()
    {
        $action = $this->request->input('action');
        switch ($action) {
            case "add":
                return $this->AddAnnex();
                break;
            case "update":
                return $this->UpdateAnnex();
                break;
            case "delete":
                return $this->DeleteAnnex();
                break;
            case "list":
                return $this->AnnexList();
                break;
            case "sync":
                return $this->SyncAnnex();
                break;
            default:
                return  view('admin.page.content.annex');
                break;
        }
    }


    public function AnnexList(){
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $params = json_decode($this->request->input('params', '[]'), 1);
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $count = QaecmsAnnex::count();
        $data = QaecmsAnnex::orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => $count, 'data' => $data]);
    }


    private function AddAnnex()
    {
        $files = $this->request->file('file');
        $data = [];
        foreach ($files as $file) {
            if ($file->isValid()) {
                $extension = $file->getClientOriginalExtension();
                $realname = $file->getClientOriginalName();
                $mimetype = $file->getClientMimeType();
                $pathtype = (explode('/', $mimetype))[0];
                if (!array_key_exists($pathtype, config('system.annextype'))) {
                    $pathtype = "file";
                }

                if (!is_dir(public_path('upload/'.$pathtype))) {
                    Storage::disk('upload')->makeDirectory($pathtype);
                }
                $respath = Storage::disk('upload')->put($pathtype,$file);
                $respath = "/upload/".$respath;
                if ($respath) {
                    $insertdata = ['title' => $realname, 'type' => $pathtype, 'suffix' => $extension, 'content' =>$respath];
                    $r = QaecmsAnnex::create($insertdata);
                    if ($r) {
                        $data[] = $respath;
                    }
                }
            }
        }
        return ['errno' => 0, 'data' => $data];
    }

    private function SyncAnnex()
    {
        $load = new Loading(Loading::LOAD_TYPE_STRAIGHT);
        $type = $this->request->input('type');
        $common = new CommonController();
        switch ($type) {
            case "video":
                $count = QaecmsVideo::count();
                $limit = 100;
                $totalpage =round($count/$limit,0,PHP_ROUND_HALF_UP);
                $load->setTotal($totalpage);
                $load->init();
                    for ($i=0;$i<$totalpage;$i++){
                        $load->progress();
                         $page = $i;
                        if($page!=0){
                            $page = $page*$limit;
                        }
                       $datas = QaecmsVideo::select(['onlykey','thumbnail'])->offset($page)->limit($limit)->get()->pluck('onlykey','thumbnail')->toArray();
                       $res = $common->MuliteDown($datas);
                       foreach ($res['data'] as $update){
                           QaecmsVideo::where(['onlykey'=>$update['onlykey']])->update(['thumbnail'=>$update['thumbnail']]);
                       }
                }
                break;
            case "article":
                $datas = QaecmsArticle::select(['id', 'thumbnail'])->pluck('thumbnail', 'id')->toArray();
                break;
        }
        unset($load);
    }

    public function CliSyncAnnex($type)
    {
        $common = new CommonController();
        echo Carbon::now()->toDateTimeString()."--开始同步图片\n";
        switch ($type) {
            case "video":
                $count = QaecmsVideo::count();
                $limit = 100;
                $totalpage =round($count/$limit,0,PHP_ROUND_HALF_UP);
                for ($i=0;$i<$totalpage;$i++){
                    $page = $i;
                    if($page!=0){
                        $page = $page*$limit;
                    }
                    $datas = QaecmsVideo::select(['onlykey','thumbnail'])->offset($page)->limit($limit)->get()->pluck('onlykey','thumbnail')->toArray();
                    $res = $common->MuliteDown($datas);
                    foreach ($res['data'] as $update){
                        QaecmsVideo::where(['onlykey'=>$update['onlykey']])->update(['thumbnail'=>$update['thumbnail']]);
                    }
                }
                break;
            case "article":
                $datas = QaecmsArticle::select(['id', 'thumbnail'])->pluck('thumbnail', 'id')->toArray();
                break;
        }
        echo Carbon::now()->toDateTimeString()."--同步图片完成\n";
    }

    private function UpdateAnnex()
    {
        // TODO: Implement UpdateAnnex() method.
    }

    private function DeleteAnnex()
    {
        // TODO: Implement DeleteAnnex() method.
    }

}
