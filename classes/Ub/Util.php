<?php

class UbUtil
{
    public static function json(array $array): string|false
    {
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }

    public static function echoJson(array $array): void
    {
        echo self::json($array);
    }

    public static function errorVkResponse(array $error): array
    {
        return self::buildErrorResponse(
            'vk_error',
            $error['error_msg'] ?? 'Unknown error',
            $error['error_code'] ?? 0
        );
    }

    public static function echoErrorVkResponse(array $error): void
    {
        self::echoJson(self::errorVkResponse($error));
    }

    public static function buildErrorResponse(string $type, string $message, int $code): array
    {
        return [
            'response' => $type,
            'error_message' => $message,
            'error_code' => $code
        ];
    }

    public static function echoError(string $message, int $code = -1): void
    {
        self::echoJson(self::buildErrorResponse('error', $message, $code));
    }

    public static function getVkErrorText(array $error): ?string
    {
        $code = $error['error_code'] ?? 0;
        $msg  = $error['error_msg'] ?? '';

        return match ($code) {
            VK_BOT_ERROR_ACCESS_DENIED => str_contains($msg, 'already in')
                ? 'Пользователь уже в беседе'
                : (str_contains($msg, 'can\'t add this')
                    ? 'Не могу добавить. Скорее всего пользователь не в моих друзьях.'
                    : null),

            VK_BOT_ERROR_CANT_DELETE_FOR_ALL_USERS =>
                'Невозможно удалить для всех пользователей.' . PHP_EOL .
                'Возможно удаляющий не имеет прав администратора или удаляемые сообщения принадлежат администратору.',

            default => 'Ошибка ВК (' . $code . ')'
        };
    }
}
