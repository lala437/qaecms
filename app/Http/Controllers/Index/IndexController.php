<?php


namespace App\Http\Controllers\Index;


use App\Http\Controllers\Controller;
use App\Librarys\Interfaces\Index\IndexInterface;
use App\Librarys\Interfaces\Index\PayInterface;
use App\Librarys\Interfaces\Index\UserInterface;
use App\Model\QaecmsSinglePage;

class IndexController extends Controller
{
    public function Index(IndexInterface $index)
    {
        return $index->Index();
    }

    public function List(IndexInterface $index, $type, $class = "all", $cat = "all", $page = 1, $limit = 35)
    {
        return $index->List($type, $class, $cat, $page, $limit);
    }

    public function Detail(IndexInterface $index, $type, $id)
    {
        return $index->Detail($type, $id);
    }

    public function Play(IndexInterface $index, $id)
    {
        return $index->Play($id);
    }

    public function Search(IndexInterface $index, $type, $wd = "成龙")
    {
        return $index->Search($type, $wd);
    }

    public function User(UserInterface $user)
    {
        return $user->User();
    }

    public function Pay(PayInterface $pay)
    {
        return $pay->Pay();
    }

    public function Notify(PayInterface $pay)
    {

        return $pay->Notify();
    }

    public function Return(PayInterface $pay)
    {
        return $pay->Return();
    }

    public function SearchComplete(IndexInterface $index, $type)
    {
        return $index->SearchComplete($type);
    }

    public function Comments(IndexInterface $index)
    {
        return $index->Comments();
    }

    public function SinglePage($name)
    {
        $html = QaecmsSinglePage::where(['name'=>$name])->first()->content;
        return htmlspecialchars_decode($html);
    }
}
