<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig {

    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     */
    public array $aliases = [
        'csrf' => CSRF::class,
        'toolbar' => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'login' => \App\Filters\LoginFilter::class, //Filtro de login
        'admin' => \App\Filters\AdminFilter::class, //Filtro de admin
        'visitante' => \App\Filters\VisitanteFilter::class, //Filtro de visitantes
        'invalidchars' => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'throttle' => \App\Filters\ThrottleFilter::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            'csrf',
        // 'invalidchars',
        ],
        'after' => [
            'toolbar',
        // 'honeypot',
        // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don’t expect could bypass the filter.
     */
    public array $methods = [
        'post' => ['throttle'],
    ];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     */
    public array $filters = [
        'login' => [
            'before' => [
                'admin/*', //Todos os controllers que estão dentro do namespace 'Admin' só serão acessados após o login
                'Conta(/*)?',
                'Checkout(/*)?',
            ]
        ],
        'admin' => [
            'before' => [
                'admin/*', //Todos os controllers que estão dentro do namespace 'Admin' só serão acessados por um administrador
            ]
        ],
    ];

}
