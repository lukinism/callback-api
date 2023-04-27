<?php

const UB_ICON_WARN = "⚠️";
const UB_ICON_SUCCESS = "✅";
const UB_ICON_SUCCESS_OFF = "❎";
const UB_ICON_NOTICE = "📝";
const UB_ICON_INFO = "🗓";
const UB_ICON_DANGER = "📛";
const UB_ICON_COMMENT = "💬";
const UB_ICON_CONFIG = "⚙️";
const UB_ICON_CATALOG = "🗂";
const UB_ICON_STATS = "📊";

class UbUtil {

	public static function json(array $array): false|string
    {
		return json_encode($array, JSON_UNESCAPED_UNICODE);
	}

	public static function echoJson(array $array): void
    {
		echo json_encode($array, JSON_UNESCAPED_UNICODE);
	}

	public static function errorVkResponse(array $error): array
    {
		return self::buildErrorResponse('vk_error', $error['error_msg'], $error['error_code']);
	}

	public static function echoErrorVkResponse($error): void
    {
		self::echoJson(self::errorVkResponse($error));
	}

	public static function buildErrorResponse($type, $message, $code): array
    {
		return ['response' => $type, 'error_message' => $message, 'error_code' => $code];
	}

	public static function echoError($message, $code = -1): void
    {
		echo json_encode(self::buildErrorResponse('error', $message, $code), JSON_UNESCAPED_UNICODE);
	}

    public static function getVkErrorText($error): ?string
    {
        $errorCode = $error['error_code'];
        $eMessage = $error['error_msg'];
        $errorMessage = null;
        switch ($errorCode) {
            case VK_BOT_ERROR_ACCESS_DENIED :
                if (str_contains($eMessage, 'already in'))
                    $errorMessage = 'Пользователь уже в беседе';
                else if (str_contains($eMessage, 'can\'t add this'))
                    $errorMessage = 'Не могу добавить. Скорее всего пользователь не в моих друзьях.';
                break;
            case VK_BOT_ERROR_CANT_DELETE_FOR_ALL_USERS : $errorMessage = 'Невозможно удалить для всех пользователей.' . PHP_EOL . 'Возможно удаляющий не имеет прав администратора или удаляемые сообщения принадлежат администратору.'; break;
            default : $errorMessage = ' Ошибка ВК (' . $errorCode . ')'; break;
        }
        return $errorMessage;
    }
}