<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\LinkInterface;
use App\Model\QaecmsLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkService implements LinkInterface
{

    private $request;

    public function __construct(Request $request)
    {
      $this->request = $request;
    }

    public function Link()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "add":
                return $this->AddLink();
                break;
            case "update":
                return $this->UpdateLink();
                break;
            case "delete":
                return $this->DeleteLink();
                break;
            case "list":
                return $this->LinkList();
                break;
            case "detect":
                return $this->DetectLink();
                break;
            default:
                return view('admin.page.link.link');
                break;
        }
    }

    private function LinkList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $count = QaecmsLink::count();
        $data = QaecmsLink::offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => $count, 'data' => $data]);
    }

    private function AddLink()
    {
        $data = $this->request->input('data');
        $res = QaecmsLink::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateLink()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsLink::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');

    }

    private function DeleteLink()
    {
        $data = $this->request->input('data');
        $res = QaecmsLink::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }

    private function DetectLink(){
        $data = $this->request->input('data');
        $link = QaecmsLink::find($data['id']);
        $url = $link->link;
        $html = curl_get($url);
        $res = Str::contains($html,qaecms('domin'));
        return responsed($res,"对方添加了您","对方删除了您");
    }

}
