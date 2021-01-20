<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\CommentInterface;
use App\Model\QaecmsComment;
use App\Model\QaecmsCommentConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentService implements CommentInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function Comment()
    {
        $action = $this->request->input('action');
        switch ($action) {
            case "reply":
                return $this->ReplyComment();
                break;
            case "delete":
                return $this->DeleteComment();
                break;
            case "list":
                return $this->CommentList();
                break;
            case "clean":
                return $this->CleanComment();
                break;
            default:
                return view('admin.page.comments.comment');
                break;
        }
        // TODO: Implement Comment() method.
    }

    private function CommentList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $params = json_decode($this->request->input('params', '[]'), 1);
        $name = $params['name'] ?? false;
        $id = $this->request->input('id',false);
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $dataobj = QaecmsComment::select(['id','content','created_at'])->when($id,function ($query)use ($id){
            return $query->where(['pid'=>$id]);
        })->when(!$id,function ($query)use ($id){
            return $query->whereNull('pid');
        })->when($name, function ($query) use ($name) {
            return $query->where('content', 'like', '%' . $name . '%');
        });
        $count = $dataobj->count();
        $data = $dataobj->offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => $count, 'data' => $data]);
    }

    private function ReplyComment()
    {
        $data = $this->request->input('data');
        $pid = $data['id'];
        $content = $data['content'];
        $res = QaecmsComment::create(['name' => '管理员', 'pid' => $pid, 'content' => $content]);
        return responsed($res, '回复成功', '回复失败');
    }

    private function DeleteComment()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        DB::transaction(function () use ($id) {
            QaecmsComment::where(['id' => $id])->delete();
            QaecmsComment::where(['pid' => $id])->delete();
        }, 2);
        return ['status' => 200, 'msg' => '删除成功'];
    }

    private function CleanComment()
    {
        $res = QaecmsComment::query()->delete();
        return responsed($res, '删除成功', '删除失败');
    }

    public function CommentConfig()
    {
        if ($this->request->isMethod('get')) {
            $comment = QaecmsCommentConfig::first();
            return view('admin.page.comments.commentconfig',['comment'=>$comment]);
        } else {
            $data = $this->request->input('data');
            $res = QaecmsCommentConfig::updateOrCreate(['id' => 1], $data);
            return responsed($res, '修改成功', '修改失败');
        }
    }

}
