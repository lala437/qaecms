<?php

namespace App\Http\Controllers\Index;

use App\Model\QaecmsUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    public function showRegistrationForm()
    {
        return view('user.auth.reg');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:3','max:16', 'unique:qaecms_users'],
            'email' => ['required', 'string', 'email', 'max:30', 'min:5', 'unique:qaecms_users'],
            'password' => ['required', 'string', 'min:6', 'max:16','confirmed'],
            'nick' => ['required', 'string','min:2','max:16'],
            'captcha' => ['required', 'captcha'],
        ], [
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '请输入正确的验证码',
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

    public function register(Request $request)
    {
        if ($request->isMethod('get')) {
            return $this->showRegistrationForm();
        }
        $data = $request->all();
        $this->validator($data)->validate();
        $res = $this->create($data);
        if ($res) {
            return response(['status' => 200, 'msg' => "注册成功"]);
        } else {
            return response(['status' => 400, 'msg' => "注册失败"]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     */
    protected function create(array $data)
    {
        if (request()->server('HTTP_X_FORWARDED_FOR')) {
            $ip = request()->server('HTTP_X_FORWARDED_FOR');
        } else {
            $ip = request()->getClientIp();
        }
        return QaecmsUser::create([
            'id' => makerandstr(8),
            'name' => $data['name'],
            'registerip' => $ip,
            'email' => $data['email'],
            'nick' => $data['nick'],
            'password' => $data['password'],
        ]);
    }
}
