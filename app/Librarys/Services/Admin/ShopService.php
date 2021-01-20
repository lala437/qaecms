<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\ShopInterface;
use App\Model\QaecmsShop;
use Illuminate\Http\Request;

class ShopService implements ShopInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function Shop()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "add":
                return $this->AddShop();
                break;
            case "update":
                return $this->UpdateShop();
                break;
            case "delete":
                return $this->DeleteShop();
                break;
            case "list":
                return $this->ShopList();
                break;
            default:
                return view('admin.page.shop.shop');
                break;
        }
    }


    private function ShopList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $data = QaecmsShop::offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

    private function AddShop()
    {
        $data = $this->request->input('data');
        $res = QaecmsShop::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateShop()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsShop::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');

    }

    private function DeleteShop()
    {
        $data = $this->request->input('data');
        $res = QaecmsShop::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }
}
