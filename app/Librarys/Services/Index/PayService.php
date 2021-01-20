<?php


namespace App\Librarys\Services\Index;


use App\Librarys\Interfaces\Index\PayInterface;
use App\Model\QaecmsOrder;
use App\Model\QaecmsPayConfig;
use App\Model\QaecmsShop;
use App\Model\QaecmsUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  PayService implements PayInterface
{

    private $request;
    private $user;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = Auth::user();
    }

    public function Pay()
    {
        $action = $this->request->input('action');
        switch ($action) {
            case "pay":
                return $this->SubmitPay();
                break;
            case "cannel":
                return $this->Cannel();
                break;
            case "nowpay":
                return $this->NowPay();
                break;
            default:
                if ($this->request->isMethod('get')) {
                    $this->request->validate([
                        'shop' => 'required|numeric',
                    ]);
                    $shop = QaecmsShop::find($this->request->input('shop'));
                    if (!$shop) {
                        return redirect(route('qaecmsindex.user'));
                    }
                    return view('user.pay.pay', ['shop' => $shop]);
                }
                break;
        }
    }

    private function SubmitPay()
    {
        if ($this->request->isMethod('post')) {
            $this->request->validate([
                'shopid' => 'required|numeric',
                'paytype' => 'required|alpha_num|max:8',
            ]);
            $shopid = $this->request->input('shopid');
            $paytype = $this->request->input('paytype');
            if (!$shop = $this->ValidationShopPay($shopid, $paytype)) {//验证此商品ID的有效性
                return redirect(route('qaecmsindex.user'));
            }
            $isorder = QaecmsOrder::where(['status' => 0])->where(['user_id' => $this->user->id])->where(['shop_id' => $shopid])->first();
            if ($isorder) {
                return ['status' => 400, 'msg' => '当前已有相同商品订单,不可重复创建'];
            }
            $insertorder = QaecmsOrder::create(['order_id' => date("YmdHis", time()) . uniqid(), 'platform' => $paytype, 'user_id' => $this->user->id, 'shop_id' => $shopid, 'money' => $shop->price, 'currency_type' => 'rmb', 'status' => 0]);
            if ($insertorder) {
                return $this->CreateOrder($insertorder);
            } else {
                return redirect(route('qaecmsindex.user'));
            }
        }
    }


    private function ValidationShopPay($shopid, $paytype)
    {
        $shop = QaecmsShop::where(['id' => $shopid])->where(['status' => 1])->where('stock', '>', 0)->where(['vip' => $this->user->vip])->first();
        $is = in_array($paytype, ['alipay', 'wxpay']);
        if ($shop && $is) {
            return $shop;
        }
        return false;
    }

    private function CreateOrder($order)
    {
        $config = QaecmsPayConfig::where(['type' => 'mapay'])->first();
        $codepay_id = $config->arg1;
        $codepay_key = $config->arg2;
        $paytypes = ['alipay' => 1, 'wxpay' => 3];
        $data = array(
            "id" => $codepay_id,
            "pay_id" => $order->order_id,
            "type" => $paytypes[$order->platform],
            "price" => (float)$order->money,
            "param" => uniqid(),
            "notify_url" => route('notify'),
            "return_url" => route('return'),
        );
        ksort($data);
        reset($data);
        $sign = '';
        $urls = '';
        foreach ($data as $key => $val) { //遍历需要传递的参数
            if ($val == '' || $key == 'sign') continue; //跳过这些不参数签名
            if ($sign != '') { //后面追加&拼接URL
                $sign .= "&";
                $urls .= "&";
            }
            $sign .= "$key=$val"; //拼接为url参数形式
            $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值
        }
        $query = $urls . '&sign=' . md5($sign . $codepay_key); //创建订单所需的参数
        $url = "https://api.xiuxiu888.com/creat_order/?{$query}"; //支付页面
        return ['status' => 200, 'url' => $url];
    }


    private function NowPay()
    {
        $data = $this->request->input('data', "");
        $order = QaecmsOrder::where(['user_id' => $this->user->id])->where(['order_id' => $data['id'] ?? ""])->where(['status' => 0])->first();
        if ($order) {
            return $this->CreateOrder($order);
        } else {
            return ['status' => "400", 'msg' => '非法操作'];
        }
    }

    public function Return()
    {
        $data = $this->request->all();
        $order = QaecmsOrder::where(['order_id' => $data['pay_id']])->first();
        if (isset($data['pay_no']) && $order->money == $data['money']) {
            return view('user.pay.success');
        } else {
            return view('user.pay.fail');
        }
    }

    public function Notify()
    {
        $config = QaecmsPayConfig::where(['type' => 'mapay'])->first();
        $codepay_key = $config->arg2;
        $data = $this->request->all();
        ksort($data);
        reset($data);
        $sign = '';
        foreach ($data as $key => $val) {
            if ($val == '' || $key == 'sign') continue;
            if ($sign) $sign .= '&';
            $sign .= "$key=$val";
        }
        if (isset($data['pay_no']) && md5($sign . $codepay_key) == $data['sign']) {
            return $this->Deal($data);
        }
        return "fail";
    }

    private function Cannel()
    {
        $data = $this->request->input('data', "");
        $order = QaecmsOrder::where(['user_id' => $this->user->id])->where(['order_id' => $data['id'] ?? ""])->where(['status' => 0])->first();
        if ($order) {
            $order->update(['status' => 2]);
            return ['status' => 200, 'msg' => "取消成功"];
        }
        return ['status' => 400, 'msg' => "取消失败"];
    }

    private function Deal($data)
    {
        $pay_id = $data['pay_id'];
        $money = (float)$data['money'];
        $pay_no = $data['pay_no'];
        $order = QaecmsOrder::where(['order_id' => $pay_id])->where(['status' => 0])->first();
        if ($order) {
            if ($money == $order->money) {
                $shop = QaecmsShop::find($order->shop_id);
                $type = $shop->type;
                switch ($type) {
                    case "vip":
                        DB::transaction(function () use ($order, $shop, $pay_no) {
                            $user = QaecmsUser::find($order->user_id);
                            $shop->update(['stock' => DB::raw("stock-1")]);
                            $old_vipendtime = $user->vip_endtime??"1970-01-01 00:00:00";
                            $nowtime = Carbon::now();
                            if ($old_vipendtime) {
                                if ($nowtime->gte($old_vipendtime)) {
                                    $vip_endtime = $nowtime->addDays($shop->number)->toDateTimeString();
                                } else {
                                    $vip_endtime = Carbon::parse($old_vipendtime)->addDays($shop->number)->toDateTimeString();
                                }
                            } else {
                                $vip_endtime = $nowtime->addDays($shop->number)->toDateTimeString();
                            }
                            $user->update(['vip' => 1, 'vip_endtime' => $vip_endtime]);
                            $order->update(['status' => '1', 'platform_id' => $pay_no, 'success_at' => date("Y-m-d H:i:s", time())]);
                        }, 5);
                        break;
                    case "integral":
                        DB::transaction(function () use ($order, $shop, $pay_no) {
                            $shop->update(['stock' => DB::raw("stock-1")]);
                            QaecmsUser::find($order->user_id)->update(['integral' => $shop->number]);
                            $order->update(['status' => '1', 'platform_id' => $pay_no, 'success_at' => date("Y-m-d H:i:s", time())]);
                        }, 5);
                        break;
                }
                return "success";
            }
        } else {
            $order->update(['status' => '2', 'platform_id' => $pay_no]);
        }
        return "fail";
    }

}
