<?php

class Router
{

    private array $routes = [];

    // تسجيل مسار (route) جديد وتحويل {id} في المسار لـ regex
    public function add(string $method, string $path, array $handler): void
    {
        preg_match_all('/\{([a-zA-Z]+)\}/', $path, $matches);
        $paramNames = $matches[1];

        $regexPattern = preg_replace('/\{([a-zA-Z]+)\}/', '([^/]+)', $path);
        $regexPattern = '#^' . $regexPattern . '$#';

        $this->routes[] = [
            'method'     => strtoupper($method),
            'pattern'    => $regexPattern,
            'paramNames' => $paramNames,
            'handler'    => $handler,
        ];
    }

    // البحث عن المسار المطابق للطلب واستدعاء الكنترولر الصحيح
    public function dispatch(string $method, string $requestUri): void
    {
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $requestUri, $matches)) {
                array_shift($matches);

                $params = array_combine($route['paramNames'], $matches);

                $this->invokeHandler($route['handler'], $params);
                return;
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    // استدعاء الدالة الصحيحة في الكنترولر مع الباراميترات
    private function invokeHandler(array $handler, array $params): void
    {
        [$controllerClass, $methodName] = $handler;

        if (!class_exists($controllerClass)) {
            throw new RuntimeException("Controller not found: {$controllerClass}");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            throw new RuntimeException("Method not found: {$controllerClass}::{$methodName}");
        }

        call_user_func_array([$controller, $methodName], array_values($params));
    }
}
