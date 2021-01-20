<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\AdInterface;
use App\Model\QaecmsAd;
use Illuminate\Http\Request;

class AdService implements AdInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function Ad()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "add":
                return $this->AddAd();
                break;
            case "update":
                return $this->UpdateAd();
                break;
            case "delete":
                return $this->DeleteAd();
                break;
            case "list":
                return $this->AdList();
                break;
            case "detect":
                return $this->DetectAd();
                break;
            default:
                return view('admin.page.ad.ad');
                break;
        }
    }

    private function AdList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $count = QaecmsAd::count();
        $data = QaecmsAd::offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => $count, 'data' => $data]);
    }

    private function AddAd()
    {
        $data = $this->request->input('data');
        $res = QaecmsAd::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateAd()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsAd::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');

    }

    private function DeleteAd()
    {
        $data = $this->request->input('data');
        $res = QaecmsAd::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }

}
