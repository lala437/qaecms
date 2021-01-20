<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\CacheInterface;
use App\Model\QaecmsCacheConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CacheService implements CacheInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function Cache()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "clearcache":
                return $this->ClearCache();
                break;
            case "cacheconfig":
                return $this->CacheConfig();
                break;
            default:
                $cache = QaecmsCacheConfig::where(['arg2'=>'cache'])->first();
                return view('admin.page.cache.cache',['cache'=>$cache]);
                break;
        }
    }

    private function ClearCache()
    {
        Artisan::call('qaecms-cache:clear');
        return ['status' => 200, 'msg' => '清理成功'];
    }

    private function CacheConfig()
    {
        $data = $this->request->input('data');
        $res = QaecmsCacheConfig::UpdateOrCreate(['arg2' => 'cache'], ['status' => $data['status'], 'arg1' => "1440"]);
        if ($res) {
            return ['status' => 200, 'msg' => '更新成功'];
        }
        return ['status' => 400, 'msg' => '更新失败'];
    }

}
