<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\PlayerInterface;
use App\Model\QaecmsPlayer;
use Illuminate\Http\Request;

class PlayerService implements PlayerInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function Player()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "add":
                return $this->AddPlayer();
                break;
            case "update":
                return $this->UpdatePlayer();
                break;
            case "delete":
                return $this->DeletePlayer();
                break;
            case "list":
                return $this->PlayerList();
                break;
            default:
                return view('admin.page.player.player');
                break;
        }
    }

    private function PlayerList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $data = QaecmsPlayer::offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

    private function AddPlayer()
    {
        $data = $this->request->input('data');
        $res = QaecmsPlayer::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdatePlayer()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $res = QaecmsPlayer::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');

    }

    private function DeletePlayer()
    {
        $data = $this->request->input('data');
        $res = QaecmsPlayer::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }


}
