<?php

namespace App\Http\Middleware;

use App\Librarys\Cache\Cache;
use App\Model\QaecmsCacheConfig;
use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheMiddleware
{
    /**
     * The cache instance.
     *
     * @var \Silber\PageCache\Cache
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @var \Silber\PageCache\Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldCache($request, $response)) {
            $this->cache->cache($request, $response);
        }
        return $response;
    }

    /**
     * Determines whether the given request/response pair should be cached.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return bool
     */
    protected function shouldCache(Request $request, Response $response)
    {
        if ($request->getQueryString()) {
            return false;
        }
        $cachestatus = QaecmsCacheConfig::where(['id' => 1])->first()->status??0;
        if ($cachestatus == 1) {
            return $request->isMethod('GET') && $response->getStatusCode() == 200;
        }
    }
}
