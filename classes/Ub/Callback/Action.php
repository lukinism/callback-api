<?php

interface UbCallbackAction {
	function execute(int $userId, array $object, array $userData);
}