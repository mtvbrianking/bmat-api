<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

class FrontController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // $this->middleware('auth')->except([
        //     'showApiUsageDoc',
        //     'showApiCodeDoc',
        // ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show API usage documentation.
     *
     * @return \Illuminate\View\View
     */
    public function showApiUsageDoc()
    {
        return view('api-usage');
    }

    /**
     * Show API source code documentation.
     *
     * @return \Illuminate\View\View
     */
    public function showApiCodeDoc()
    {
        return redirect(url('docs'));
    }

    /**
     * Show application routes.
     *
     * Forbidden in production environment.
     *
     * @return \Illuminate\View\View
     */
    public function showApplicationRoutes()
    {
        if (config('app.log_level') == 'production') {
            abort(403);
        }

        $routes = collect(Route::getRoutes());

        $routes = $routes->map(function ($route) {
            return [
                'host' => $route->action['where'],
                'uri' => $route->uri,
                'name' => $route->action['as'] ?? '',
                'methods' => $route->methods,
                'action' => $route->action['controller'] ?? 'Closure',
                'middleware' => $this->getRouteMiddleware($route),
            ];
        });

        return view('routes', [
            'routes' => $routes,
        ]);
    }

    /**
     * Get route middleware.
     *
     * @param  \Illuminate\Routing\Route $route
     *
     * @return string
     */
    protected function getRouteMiddleware($route)
    {
        return collect($route->gatherMiddleware())->map(function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        })->implode(', ');
    }
}
