<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\PayInterface;
use App\Model\QaecmsOrder;
use App\Model\QaecmsPayConfig;
use Illuminate\Http\Request;

class PayService implements PayInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function Order()
    {
        $action = $this->request->get('action');
        switch ($action) {
            case "delete":
                return $this->DeleteOrder();
                break;
            case "list":
                return $this->OrderList();
                break;
            default:
                return view('admin.page.pay.order');
                break;
        }

    }

    private function OrderList()
    {
        $pageNum = $this->request->input('page');
        $limit = $this->request->input('limit');
        $page = $pageNum - 1;
        if ($page != 0) {
            $page = $limit * $page;
        }
        $data = QaecmsOrder::with(['user:id,name', 'shop:id,name'])->offset($page)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response(['code' => 0, 'msg' => '获取成功', 'count' => count($data), 'data' => $data]);
    }

    private function DeleteOrder()
    {
        $data = $this->request->input('data');
        $res = QaecmsOrder::where(['id' => $data['id']])->delete();
        return responsed($res, '删除成功', '删除失败');
    }

    public function PayConfig()
    {
        $type = $this->request->get('type');
        $data = $this->request->input('data');
        switch ($type) {
            case "mapay":
                foreach ($data as $key=>$v){
                    $data[$key] = trim($v);
                }
                $res = QaecmsPayConfig::UpdateOrCreate(['type'=>'mapay'],['status'=>$data['status'],'arg1'=>$data['arg1'],'arg2'=>$data['arg2']]);
                if($res){
                    return ['status'=>200,'msg'=>'更新成功'];
                }
                break;
            default:
                $mapay = QaecmsPayConfig::where(['type'=>'mapay'])->first();
                return view('admin.page.pay.payconfig',['mapay'=>$mapay]);
                break;
        }
    }

}
