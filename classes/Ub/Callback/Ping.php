<?php
class UbCallbackPing implements UbCallbackAction {
	function execute($userId, $object, $userData): void
    {
		echo 'ok';
	}

}