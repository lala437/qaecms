<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\UserInterface;
use App\Model\QaecmsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserService implements UserInterface
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function User()
    {
        $action = $this->request->input('action');
        switch ($action) {
            case "add":
                return $this->AddUser();
                break;
            case "update":
                return $this->UpdateUser();
                break;
            case "status":
                return $this->StatusUser();
                break;
            case "list":
                return $this->UserList();
                break;
            default:
                return view('admin.page.user.user');
                break;
        }
    }

    private function UserList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $data = QaecmsUser::offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

    private function AddUser()
    {
        $data = $this->request->input('data');
        $this->validator($data,'add')->validate();
        if(empty($data['vip_endtime'])){
            $data['vip_endtime']=null;
        }
        $res = QaecmsUser::create($data);
        return responsed($res, '添加成功', '添加失败');
    }

    private function UpdateUser()
    {
        $data = $this->request->input('data');
        $id = $data['id'];
        unset($data['id']);
        if(empty($data['password'])){
            unset($data['password']);
        }
        if(empty($data['vip_endtime'])){
            $data['vip_endtime']=null;
        }
        $this->validator($data,'update',$id)->validate();
        $res = QaecmsUser::find($id)->update($data);
        return responsed($res, '更新成功', '更新失败');
    }

    private function StatusUser()
    {
        $data = $this->request->input('data');
        $res = QaecmsUser::where(['id' => $data['id']])->update(['status'=>($data['status']==1?0:1)]);
        return responsed($res, '修改成功', '修改失败');
    }

    private function validator(array $data,$type,$id=null)
    {
        $validator = ['add'=> [
            'name' => ['required', 'string', 'min:3','max:16', 'unique:qaecms_users'],
            'email' => ['required', 'string', 'email', 'max:30', 'min:5', 'unique:qaecms_users'],
            'password' => ['required', 'string', 'min:6', 'max:16'],
            'nick' => ['required', 'string','min:2','max:16']
        ],'update'=> [
            'name' => ['required', 'string', 'min:3','max:16', 'unique:qaecms_users,name,'.$id.',id'],
            'email' => ['required', 'string', 'email', 'max:30', 'min:5', 'unique:qaecms_users,email,'.$id.',id'],
            'password' => isset($data['password'])?['required', 'string', 'min:6', 'max:16']:[],
            'nick' => ['required', 'string','min:2','max:16']
        ]];

        return Validator::make($data, $validator[$type], [
            'name.unique'=>'用户名已被使用',
            'email.unique'=>'邮箱已被使用',
            'password.min'=>'密码在6-16位字符之间',
            'password.max'=>'密码在6-16位字符之间',
            'email.min'=>'邮箱在5-30位字符之间',
            'email.max'=>'邮箱在5-30位字符之间',
            'name.min'=>'用户名在3-16位字符之间',
            'name.max'=>'用户名在3-16位字符之间',
            'nick.min'=>'昵称在2-16位字符之间',
            'nick.max'=>'昵称在2-16位字符之间',
        ]);
    }


}
