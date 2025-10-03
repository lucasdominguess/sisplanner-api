<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Application;
use App\Http\Middleware\XssCleanMiddleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(XssCleanMiddleware::class);
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
       $exceptions->render(function (ValidationException $exception): JsonResponse {
            $erro = $exception->validator->errors()->all();

            Log::error($exception->getMessage());
            return response()->json([
                'message'  => 'Dados inválidos',
                'error'    => $erro,
            ], 422);
        });
           $exceptions->render(function (\Throwable $exception): JsonResponse {
            Log::error("Final Exception: ".$exception->getMessage());
            // LOG::channel('telegram')->error("Final Exception: ".$exception->getMessage());

            $status = $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;
            return response()->json([
                'message' => 'Ocorreu um erro interno no servidor. Tente novamente mais tarde.',
                'error'   => config('app.debug') ? $exception->getMessage() : null,
            ], $status);
            });
    })->create();
