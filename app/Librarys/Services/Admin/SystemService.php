<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\SystemInterface;
use App\Model\QaecmsCarousel;
use App\Model\QaecmsNav;
use App\Model\QaecmsSeoConfig;
use App\Model\QaecmsWebConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemService implements SystemInterface
{
    private $request;
    private $resp;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function WebConfig()
    {
        if ($this->request->isMethod('get')) {
            $templates = get_dir(template_path());
            return view('admin.page.system.webconfig',['templates'=>$templates]);
        } else {
            $data = $this->request->post('data');
            $res = QaecmsWebConfig::updateOrCreate(['id'=>1],$data);
            return responsed($res, '更新成功', '更新失败');
        }
    }


    public function SeoConfig()
    {
        if ($this->request->isMethod('get')) {
            return view('admin.page.system.seoconfig');
        } else {
            $data = $this->request->post('data');
            $res = QaecmsSeoConfig::updateOrCreate(['id'=>1],$data);
            return responsed($res, '更新成功', '更新失败');
        }
    }

    public function Nav()
    {
        $action = $this->request->input('action');
        switch ($action) {
            case "add":
                return $this->AddNav();
                break;
            case "update":
                return $this->UpdateNav();
                break;
            case "delete":
                return $this->DeleteNav();
                break;
            case "list":
                return $this->NavList();
                break;
            default:
                return view('admin.page.system.nav');
                break;
        }
    }


    private function NavList()
    {
        $data = QaecmsNav::orderBy('sort', 'desc')->get()->toArray();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

    private function AddNav()
    {
        $data = $this->request->input('data');
        $res = QaecmsNav::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateNav()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsNav::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');
    }

    private function DeleteNav()
    {
        $data = $this->request->input('data');
        DB::transaction(function () use ($data) {
            $childrenid = QaecmsNav::where(['pid' => $data['id']])->pluck('id')->toArray();
            $this->resp = QaecmsNav::where(['id' => $data['id']])->when(empty($childrenid) ? false : true, function ($query) use ($childrenid) {
                $query->OrWhereIn('id', $childrenid);
            })->delete();
        }, 5);
        return responsed($this->resp, '删除成功', '删除失败');
    }

    public function Carousel()
    {
        $action = $this->request->input('action');
        switch ($action) {
            case "add":
                return $this->AddCarousel();
                break;
            case "update":
                return $this->UpdateCarousel();
                break;
            case "delete":
                return $this->DeleteCarousel();
                break;
            case "list":
                return $this->CarouselList();
                break;
            default:
                return view('admin.page.system.carousel');
                break;
        }
    }

    private function CarouselList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
//        $params = json_decode($this->request->input('params', '[]'), 1);
//        $name = $params['name'] ?? false;
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $count = QaecmsCarousel::count();
        $data = QaecmsCarousel::offset($page)->limit($limit)->orderBy('sort', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => $count, 'data' => $data]);
    }

    private function AddCarousel()
    {
        $data = $this->request->input('data');
        $res = QaecmsCarousel::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateCarousel()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsCarousel::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');
    }

    private function DeleteCarousel()
    {
        $data = $this->request->input('data');
        $res = QaecmsCarousel::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }

    public function OtherConfig()
    {
        // TODO: Implement OtherConfig() method.
    }

}
