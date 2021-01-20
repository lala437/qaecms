<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\SearchInterface;
use App\Model\QaecmsSearchConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SearchService implements SearchInterface
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function SearchConfig()
    {
        $type = $this->request->get('type');
        $data = $this->request->input('data');
        switch ($type) {
            case "algolia":
                foreach ($data as $key=>$v){
                    $data[$key] = trim($v);
                }
                $res = QaecmsSearchConfig::UpdateOrCreate(['type'=>'algolia'],['status'=>$data['status'],'arg1'=>$data['arg1'],'arg2'=>$data['arg2']]);
                if($res){
                    return ['status'=>200,'msg'=>'更新成功'];
                }
                break;
            case "sync":
                try{
                    Artisan::call('scout:import',['model'=>"App\Model\QaecmsVideo"]);
                    return ['status'=>200,'msg'=>'同步成功'];
                }catch (\Exception $exception){
                    return ['status'=>200,'msg'=>'同步失败'];
                }
                break;
            default:
                $algolia = QaecmsSearchConfig::where(['type'=>'algolia'])->first();
                return view('admin.page.search.searchconfig',['algolia'=>$algolia]);
                break;
        }
    }

}
