<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\TaskInterface;
use App\Model\QaecmsTask;
use Illuminate\Http\Request;

class TaskService implements TaskInterface
{
 private $request;

 public function __construct(Request $request)
 {
     $this->request = $request;
 }

 public function Task()
 {
     $action = $this->request->get('action');
     switch ($action) {
         case "add":
             return $this->AddTask();
             break;
         case "update":
             return $this->UpdateTask();
             break;
         case "delete":
             return $this->DeleteTask();
             break;
         case "list":
             return $this->TaskList();
             break;
         case "exc":
             return $this->ExcTask();
             break;
         default:
             return view('admin.page.task.task');
             break;
     }
 }

    private function TaskList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $data = QaecmsTask::offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

    private function AddTask()
    {
        $data = $this->request->input('data');
        $data['command'] = "php ".base_path('artisan')." qaecms:console {$data['task']}";
        $res = QaecmsTask::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateTask()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        $data['command'] = "php ".base_path('artisan')." qaecms:console {$data['task']}";
        $res = QaecmsTask::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');

    }

    private function DeleteTask()
    {
        $data = $this->request->input('data');
        $res = QaecmsTask::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }

    private function ExcTask(){

    }




}
