<?php

declare(strict_types=1);

namespace App\Bootstrappers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Events\DiagnosingHealth;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;

final class RoutingBootstrapper
{
    /**
     * @param Router $router
     * @return void
     */
    public function __invoke(Router $router): void
    {
        $this->configureRateLimiter();

        $this->registerRoutes(router: $router);

        $this->registerHealthCheckRoute(router: $router);

        $this->broadcastingRoutes();
    }

    /**
     * Defines the routes for broadcasting.
     *
     * @param array<int, mixed> $attributes
     */
    private function broadcastingRoutes(array $attributes = []): void
    {
        Broadcast::routes(attributes: ! empty($attributes) ? $attributes : null);

        Broadcast::routes();

        require base_path(path: 'routes/channels/index.php');
    }

    /**
     * Configure rate limits for APIs.
     *
     * @return void
     */
    private function configureRateLimiter(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip(),
            );
        });
    }

    /**
     * Loads a routes file if available.
     *
     * @param Router $router
     * @param string $middleware
     * @param string $prefix
     * @param string $filePath
     * @return void
     */
    private function loadRoutes(Router $router, string $middleware, string $prefix, string $filePath): void
    {
        if (file_exists($path = base_path($filePath))) {
            $router->middleware($middleware)
                ->prefix('api' === $prefix ? 'api' : null)
                ->group($path);
        }
    }

    /**
     * Registers a status check route (/up).
     *
     * @param Router $router
     * @return void
     */
    private function registerHealthCheckRoute(Router $router): void
    {
        $router->middleware('web')->get('up', function () {
            Event::dispatch(new DiagnosingHealth());

            return View::file(
                base_path('/vendor/laravel/framework/src/Illuminate/Foundation/resources/health-up.blade.php'),
            );
        });
    }

    /**
     * Registers API, web, and console route groups.
     *
     * @param Router $router
     * @return void
     */
    private function registerRoutes(Router $router): void
    {
        $this->loadRoutes(
            router: $router,
            middleware: 'api',
            prefix: 'api',
            filePath: 'routes/api/index.php',
        );

        $this->loadRoutes(
            router: $router,
            middleware: 'web',
            prefix: '',
            filePath: 'routes/web/index.php',
        );

        $this->loadRoutes(
            router: $router,
            middleware: 'web',
            prefix: '',
            filePath: 'routes/console/index.php',
        );
    }
}
