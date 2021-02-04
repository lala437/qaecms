<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminLoginEvent;
use App\Http\Controllers\Controller;
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
        return view('admin.auth.login');
    }

    /**
     * @param Request $request
     * 验证登录
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ], [
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '请输入正确的验证码'
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
        if($request->isMethod('get')){
           return $this->showLoginForm();
        }
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            event(new AdminLoginEvent($this->guard()->user()));//记录登录事件
            return ['status' => 200, 'msg' => '登录成功'];
        }
        return ['status' => 400, 'msg' => '账号或者密码错误'];
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
        return Auth::guard('admin');
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
          return redirect(route('qaecmsadmin.login'));
    }

    /**
     * @return string
     * 重写重定向地址
     */
    public function redirectTo()
    {
        return route('qaecmsadmin.index');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
}
