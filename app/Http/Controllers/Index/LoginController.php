<?php

namespace App\Http\Controllers\Index;

use App\Events\UserLoginEvent;
use App\Http\Controllers\Controller;
use App\Model\QaecmsUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 重写登录表单
     */
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    /**
     * @param Request $request
     * 验证登录
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string|min:3|max:16',
            'password' => 'required|string|min:6|max:16',
            'captcha' => 'required|captcha',
        ], [
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '请输入正确的验证码',
            $this->username().'.min'=>'用户名在3-16位字符之间',
            $this->username().'.max'=>'用户名在3-16位字符之间',
            'password.min'=>'密码在6-16位字符之间',
            'password.max'=>'密码在6-16位字符之间',
        ]);
    }


    /**
     * @param Request $request
     * @return array|void
     * @throws \Illuminate\Validation\ValidationException
     * 重写登录方法
     */

    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return $this->showLoginForm();
        }
        $this->validateLogin($request);
        $user = QaecmsUser::where(['name'=>$request->input('name')])->where(['status'=>1])->first();
        if(!$user){
            return ['status' => 400, 'msg' => '用户不存在或者状态不可用'];
        }
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if ($this->attemptLogin($request)) {
            event(new UserLoginEvent($this->guard()->user()));
            return ['status' => 200, 'msg' => '登录成功'];
        }
        return ['status'=>400,"msg"=>"账号或者密码错误"];
    }


    /**
     * @return string
     * 重写用户名
     */
    public function username()
    {
        return 'name';
    }


    /**
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     * 重写守卫
     */
    public function guard()
    {
        return Auth::guard();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 注销
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->forget($this->guard()->getName());
        $request->session()->regenerate();
        return redirect(route('qaecmsindex.login'));
    }

    /**
     * @return string
     * 重写重定向地址
     */
    public function redirectTo()
    {
        return route('qaecmsindex.user');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
