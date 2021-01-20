<?php


namespace App\Librarys\Services\Admin;


use App\Librarys\Interfaces\Admin\MenuInterface;
use App\Model\QaecmsMenu;

class MenuService implements MenuInterface
{
    /**
     * @return array
     * 获取菜单列表
     */
    public function GetMenuList(){
        $menuList = QaecmsMenu::select('id','pid','title','icon','href','target')->where('status',1)->orderBy('sort','desc')->get();
        $menuList = $this->buildMenuChild(0, $menuList);
        $homeInfo = [
            'title' => '首页',
            'href'  => route('qaecmsadmin.base'),
        ];
        $logoInfo = [
            'title' => qaecms('name'),
            'image' => qaecms('logo'),
        ];
        $menus = [
            'homeInfo' => $homeInfo,
            'logoInfo' => $logoInfo,
            'menuInfo' => $menuList,
        ];
        return $menus;
    }

    /**
     * @param $pid
     * @param $menuList
     * @return array
     * 递归获取子菜单
     */
    private function buildMenuChild($pid, $menuList){
        $treeList = [];
        foreach ($menuList as $v) {
            if ($pid == $v->pid) {
                $node = ['title'=>$v->title,'icon'=>$v->icon,'href'=>$v->href,'target'=>$v->target];
                $child = $this->buildMenuChild($v->id, $menuList);
                if (!empty($child)) {
                    $node['child'] = $child;
                }
                // todo 后续此处加上用户的权限判断
                $treeList[] = $node;
            }
        }
        return $treeList;
    }

}
