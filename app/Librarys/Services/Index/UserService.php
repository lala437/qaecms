<?php


namespace App\Librarys\Services\Index;


use App\Librarys\Interfaces\Index\UserInterface;
use App\Model\QaecmsOrder;
use App\Model\QaecmsShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserService implements UserInterface
{
    private $request;
    private $user;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = Auth::user();
    }

    public function User()
    {
        $action = $this->request->input('action');
        switch ($action) {
            case "update":
                return $this->UpdateUser();
                break;
            case "orderlist":
                return $this->GetOrderList();
                break;
            case "shoplist":
                return $this->GetShopList();
                break;
            default:
                return view('user.index.user', ['name' => $this->user->name, 'nick' => $this->user->nick, 'vip' => $this->user->vip, 'integral' => $this->user->integral, 'email' => $this->user->email, 'loginip' => $this->user->loginip, 'lastloginip' => $this->user->lastloginip, 'lastlogintime' => $this->user->lastlogintime]);
                break;
        }
    }

    public function UpdateUser()
    {
        $data = $this->request->all();
        Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:30','min:5','unique:qaecms_users'],
            'nick' => ['required', 'string', 'max:2','max:16'],
        ]);
        $email = $data['email'];
        $nick = $data['nick'];
        $password = $data['password'];
        $updatedata = ['email' => $email, 'nick' => $nick, 'password' => $password];
        if (empty($password)) {
            unset($updatedata['password']);
        }
        $res = $this->user->update($updatedata);
        if ($res) {
            return ['status' => 200, "msg" => "更新成功"];
        } else {
            return ['status' => 400, "msg" => "更新失败"];
        }
    }

    private function GetOrderList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $data = QaecmsOrder::with(['shop:id,name'])->where(['user_id' => $this->user->id])->select(['order_id', 'shop_id', 'money', 'status', 'success_at', 'created_at'])->offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

    private function GetShopList()
    {
        $data = QaecmsShop::where(['vip' => $this->user->vip])->where(['status'=>1])->where('stock','>',0)->select(['id', 'name', 'desc', 'image','price'])->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

}
