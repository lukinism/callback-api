<?php
require_once(CLASSES_PATH . "Ub/MethodResolver.php");
require_once(CLASSES_PATH . "Ub/DataManager.php");
require_once(CLASSES_PATH . "Ub/Callback/Action.php");
require_once(CLASSES_PATH . "Ub/Util.php");
require_once(CLASSES_PATH . "Ub/VkApi.php");
require_once(CLASSES_PATH . "Ub/DbUtil.php");
class UbCallbackCallback {
	function run(): void
    {
		$encode = file_get_contents('php://input');

		if (!$encode) {
			UbUtil::echoError('no data', UB_ERROR_NO_DATA);
			return;
		}
		$data = json_decode($encode, true);

        $resolver = new UbMethodResolver();
		$method = $resolver->getMethodHandler($data['method']);

		if (!$method) {
			UbUtil::echoError('no method found', UB_ERROR_NO_METHOD_FOUND);
			return;
		}

		$ubManager = new UbDataManager();
		$userbot = $ubManager->getByIdSecret($data['user_id'], $data['secret']);
		if (!$userbot) {
			UbUtil::echoError('no such user', UB_ERROR_USER_SECRET);
			return;
		}

		$method->execute($data['user_id'], $data['object'], $userbot);
	}

}