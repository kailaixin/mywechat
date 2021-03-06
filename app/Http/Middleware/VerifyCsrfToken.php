<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
//        '*' // 所有的页面都不用加csrf验证

        // 去除csrf验证的路由
//        'notify_url','return_url',
//        'liuyan_info','wechat/enent',
//        '/biaobai/notify_url'
        'api/*',
        'test/*',
        'wechat/event',
        'ceshi2/event',
        'ceshi2/ceshi',
        'ceshi2/ceshi2',
        'admin1/login_do',
        'admin1/code',
        'admin1/code1',
        'admin1/bangding_do',
        'admin1/goods/change',
        'ceshi/pact/jiekou',
        'ceshi4/login_do'
    ];
}
