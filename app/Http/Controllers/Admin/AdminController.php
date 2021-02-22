<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Librarys\Interfaces\Admin\AdInterface;
use App\Librarys\Interfaces\Admin\AnnexInterface;
use App\Librarys\Interfaces\Admin\CacheInterface;
use App\Librarys\Interfaces\Admin\CollectInterface;
use App\Librarys\Interfaces\Admin\CommentInterface;
use App\Librarys\Interfaces\Admin\DataToMysqlInterface;
use App\Librarys\Interfaces\Admin\JobInterface;
use App\Librarys\Interfaces\Admin\LinkInterface;
use App\Librarys\Interfaces\Admin\PayInterface;
use App\Librarys\Interfaces\Admin\PlayerInterface;
use App\Librarys\Interfaces\Admin\SearchInterface;
use App\Librarys\Interfaces\Admin\ShopInterface;
use App\Librarys\Interfaces\Admin\SystemInterface;
use App\Librarys\Interfaces\Admin\ContentInterface;
use App\Librarys\Interfaces\Admin\MenuInterface;
use App\Librarys\Interfaces\Admin\TaskInterface;
use App\Librarys\Interfaces\Admin\UserInterface;
use App\Model\QaecmsArticle;
use App\Model\QaecmsUser;
use App\Model\QaecmsVideo;
use Carbon\Carbon;
use Illuminate\Http\Response;

class AdminController extends Controller
{

    public function Index()
    {
        return view('admin.index.index');
    }

    public function ThemeSet()
    {
        return view('admin.page.tpl.theme');
    }

    public function WorkSpace()
    {
        $view = [];
        $ver = json_decode(file_get_contents(base_path('ver.json')));
        $nowdate = Carbon::now()->toDateString();
        $view['totalvideo'] = QaecmsVideo::count();
        $view['newvideo'] = QaecmsVideo::whereDate('last', $nowdate)->count();
        $view['totalarticle'] = QaecmsArticle::count();
        $view['newarticle'] = QaecmsArticle::whereDate('created_at', $nowdate)->count();
        $view['totaluser'] = QaecmsUser::count();
        $view['newuser'] = QaecmsUser::whereDate('created_at', $nowdate)->count();
        $view['totalvip'] = QaecmsUser::where(['vip' => 1])->count();
        $view['oldvip'] = QaecmsUser::where('vip_endtime', '<', Carbon::now()->toDateTimeString())->count();
        $view['newvideolist'] = QaecmsVideo::offset(0)->limit(15)->orderBy('last', 'desc')->get();
        $view['newarticlelist'] = QaecmsArticle::offset(0)->limit(15)->orderBy('created_at', 'desc')->get();
        $view['newuserlist'] = QaecmsUser::offset(0)->limit(15)->orderBy('created_at', 'desc')->get();
        $view['version'] = $ver->vn."({$ver->version})";
        return view('admin.page.dashboard.workspace', $view);
    }

    public function Console()
    {
        return view('admin.page.dashboard.console');
    }

    public function Menus(MenuInterface $menu)
    {
        return $menu->GetMenuList();
    }

    public function WebConfig(SystemInterface $system)
    {
        return $system->WebConfig();
    }

    public function SeoConfig(SystemInterface $system)
    {
        return $system->SeoConfig();
    }

    public function Nav(SystemInterface $system)
    {
        return $system->Nav();
    }

    public function Carousel(SystemInterface $system)
    {
        return $system->Carousel();
    }

    public function Type(ContentInterface $content)
    {
        return $content->Type();
    }

    public function Article(ContentInterface $content)
    {
        return $content->Article();
    }

    public function Video(ContentInterface $content)
    {
        return $content->Video();
    }

    public function Annex(AnnexInterface $annex)
    {
        return $annex->Annex();
    }

    public function Job(JobInterface $job)
    {
        return $job->Job();
    }

    public function DataToMysql(DataToMysqlInterface $dataToMysql)
    {
        return $dataToMysql->DataToMysql();
    }

    public function NoToSqlData(DataToMysqlInterface $dataToMysql)
    {
        return $dataToMysql->NoToSqlData();
    }

    public function User(UserInterface $user)
    {
        return $user->User();
    }

    public function Shop(ShopInterface $shop)
    {
        return $shop->Shop();
    }

    public function Order(PayInterface $pay)
    {
        return $pay->Order();
    }

    public function PayConfig(PayInterface $pay)
    {
        return $pay->PayConfig();
    }

    public function SearchConfig(SearchInterface $search)
    {
        return $search->SearchConfig();
    }

    public function Link(LinkInterface $link)
    {
        return $link->Link();
    }

    public function Cache(CacheInterface $cache)
    {
        return $cache->Cache();
    }

    public function Player(PlayerInterface $player)
    {
        return $player->Player();
    }

    public function Ad(AdInterface $ad)
    {
        return $ad->Ad();
    }

    public function Task(TaskInterface $task)
    {
        return $task->Task();
    }

    public function Comment(CommentInterface $comment)
    {
        return $comment->Comment();
    }

    public function CommentConfig(CommentInterface $comment)
    {
        return $comment->CommentConfig();
    }

    public function GetVideoInfo(CollectInterface $collect){
        return $collect->GetVideoInfo();
    }

}
