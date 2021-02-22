<?php


namespace App\Librarys\Services\Admin;


use App\Http\Controllers\Common\CommonController;
use App\Librarys\Interfaces\Admin\CollectInterface;
use Illuminate\Http\Request;
use QL\QueryList;

class CollectService implements CollectInterface
{
    private $request;
    private $querylist;
    private $common;
    private $prame = [
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36'
        ]
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->common = new CommonController();
        $this->querylist = new QueryList();
    }

    public function GetVideoInfo()
    {
        $key = $this->request->input('key');
        $url = "http://v.baidu.com/v?word=" . $key . "&ct=301989888&rn=67&pn=0&db=0&s=0&fbl=800&ie=utf-8";
        $html = $this->querylist->get($url);
        $rules = [
            'title' => ['.info-wrap>h3>a', 'title'],
            'director' => ['.info-wrap>.intro-items>.intro-item:eq(0)>span:eq(1)', 'text', '', function ($value) {
                return cleanr($value);
            }],
            'actor' => ['.info-wrap>.intro-items>.intro-item:eq(1)>span:eq(1)', 'text', '', function ($value) {
                return cleanr($value);
            }],
            'area' => ['.info-wrap>.intro-items>.intro-item:eq(2)>span:eq(1)', 'text'],
            'stype' => ['.info-wrap>.intro-items>.intro-item:eq(3)>span:eq(1)', 'text', '', function ($value) {
                return cleanr($value);
            }],
            'introduction' => ['.info-wrap>.intro-items>.brief-item>span:eq(1)', 'text','-a'],
            'thumbnail'=>['.detail-info>.poster>a>img:eq(0)','src','',function($value){
                $image = substr($value,0,strpos($value, '?'));
                if(!file_exists(public_path('upload/image/'.md5($image).'.jpg'))){
                    $this->common->MuliteDown([$image=>md5($image)]);
                }
                return '/upload/image/'.md5($image).'.jpg';
            }]
        ];
        $data = $html->rules($rules)->query()->getData()->all();
        if (filled($data)&&!empty($data['title'])) {
            return ['status' => 200, 'data' => $data];
        }
        return ['status' => 400, 'data' => []];
    }
}
