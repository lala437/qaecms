<?php


namespace App\Librarys\Interfaces\Index;


interface IndexInterface
{

    public function Index();

    public function List($type,$class,$cat,$page,$limit);

    public function Detail($type,$id);

    public function Play($id);

    public function Search($type,$wd);

    public function SearchComplete($type);

    public function Comments();
}
