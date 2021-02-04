<?php


namespace App\Http\Controllers\Common;


use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{

    private $mulitedata;
    private $faildurl;
    private $header = ['User-Agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36'];

    public function MuliteRequest($urlarr,$proxy=null)
    {
        $client = new Client();
        $this->mulitedata = [];
        $this->faildurl = [];
        $totalPageCount = count($urlarr);
        $requests = function ($total) use ($client, $urlarr,$proxy) {
            foreach ($urlarr as $url) {
                yield function () use ($client, $url,$proxy) {
                    if(filled($proxy)){
                        $param = ['http'  => 'tcp://'.$proxy,'https' => 'tcp://'.$proxy];
                        return $client->getAsync($url, ['headers' =>$this->header ,'timeout' => 5,'verify' => false,'proxy'=>$param]);
                    }
                    return $client->getAsync($url, ['headers' =>$this->header ,'timeout' => 5,'verify' => false]);
                };
            }
        };
        $pool = new Pool($client, $requests($totalPageCount), [
            'concurrency' => $totalPageCount < 20 ? $totalPageCount : 20,
            'fulfilled' => function ($response, $index) {
                $this->mulitedata[] = @qae_xml_parse($response->getBody()->getContents());
            },
            'rejected' => function ($reason, $index) use ($urlarr) {
                $this->faildurl[] = $urlarr[$index];
                // 失败的响应
            },
        ]);
        //构建请求
        $promise = $pool->promise();
        //销毁请求
        $promise->wait();
        return ['data' => $this->mulitedata, 'failurl' => $this->faildurl];
    }

    public function MuliteDown($urlarr,$proxy=null)
    {
        $urlarr = qae_parse_url($urlarr);
        $client = new Client();
        $this->mulitedata = [];
        $this->faildurl = [];
        $totalPageCount = count($urlarr);
        $requests = function ($total) use ($client, $urlarr) {
            foreach ($urlarr as $url=>$key) {
                if(!file_exists(public_path('upload/image/'.$key.'.jpg'))){
                    yield function () use ($client, $url) {
                        return $client->getAsync($url, ['headers' =>$this->header,'timeout' => 5,'stream' => true,'verify' => false]);
                    };
                }
            }
        };
        $pool = new Pool($client, $requests($totalPageCount), [
            'concurrency' => $totalPageCount < 20 ? $totalPageCount : 20,
            'fulfilled' => function ($response, $index) use($urlarr) {
              $stream = $response->getBody();
              $uri = $stream->getMetadata('uri');
              $path = public_path('upload/image/'.$urlarr[$uri].'.jpg');
              file_put_contents($path,$stream);
              $this->mulitedata[] = ['onlykey'=>$urlarr[$uri],'thumbnail'=>'/upload/image/'.$urlarr[$uri].'.jpg'];
            },
            'rejected' => function ($reason, $index) use ($urlarr) {
                // 失败的响应
            },
        ]);
        //构建请求
        $promise = $pool->promise();
        //销毁请求
        $promise->wait();
        return ['data' => $this->mulitedata, 'failurl' => $this->faildurl];
    }
}
