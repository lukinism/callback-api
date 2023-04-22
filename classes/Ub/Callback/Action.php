<?php

interface UbCallbackAction {
	function execute($userId, $object, $userData);
}