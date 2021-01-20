<?php


namespace App\Librarys\Cache;

use \Silber\PageCache\Cache as BaseCache;

class Cache extends BaseCache
{
    protected function aliasFilename($filename)
    {
        return $filename ?: 'index';
    }

    protected function getDefaultCachePath()
    {
       return public_path('qaecms_page');
    }
}
