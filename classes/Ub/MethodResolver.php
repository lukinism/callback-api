<?php

declare(strict_types=1);

class UbMethodResolver
{
    private const METHOD_MAP = [
        '#confirm'         => 'Ub.Callback.Confirm',
        'ping'             => 'Ub.Callback.Ping',
        'banExpired'       => 'Ub.Callback.AddUser',
        'addUser'          => 'Ub.Callback.AddUser',
        'bindChat'         => 'Ub.Callback.Bind',
        'subscribeSignals' => 'Ub.Callback.Bind',
        'deleteMessages'   => 'Ub.Callback.DeleteMessages',
        'printBookmark'    => 'Ub.Callback.PrintBookmark',
    ];

    public function getMethodHandler(string $method): ?object
    {
        $actionName = self::METHOD_MAP[$method] ?? null;
        if ($actionName === null) {
            return null;
        }

        return $this->getClassInstance($actionName);
    }

    private function getClassInstance(string $pathClass): object
    {
        $classPath = str_replace('.', '/', $pathClass) . '.php';
        $className = str_replace('.', '', $pathClass);
        $fullPath = CLASSES_PATH . $classPath;

        if (!file_exists($fullPath)) {
            throw new RuntimeException("Class file not found: $fullPath");
        }

        require_once $fullPath;

        if (!class_exists($className)) {
            throw new RuntimeException("Class not found: $className");
        }

        return new $className();
    }
}
