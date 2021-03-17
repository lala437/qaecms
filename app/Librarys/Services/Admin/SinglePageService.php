<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\SinglePageInterface;
use App\Model\QaecmsSinglePage;
use Illuminate\Http\Request;

class SinglePageService implements SinglePageInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function SinglePage()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "add":
                return $this->AddSinglePage();
                break;
            case "update":
                return $this->UpdateSinglePage();
                break;
            case "delete":
                return $this->DeleteSinglePage();
                break;
            case "list":
                return $this->SinglePageList();
                break;
            default:
                return view('admin.page.singlepage.singlepage');
                break;
        }
    }

    private function SinglePageList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $data = QaecmsSinglePage::offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        foreach ($data as $v){
            $v->url = route('qaecmsindex.single',['name'=>$v->name]);
        }
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

    private function AddSinglePage()
    {
        $data = $this->request->input('data');
        $res = QaecmsSinglePage::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateSinglePage()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsSinglePage::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');

    }

    private function DeleteSinglePage()
    {
        $data = $this->request->input('data');
        $res = QaecmsSinglePage::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }

}
