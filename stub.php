<?php
Phar::mapPhar('PHPStats.phar');
spl_autoload_register(function ($className) {
	$classPath = 'phar://PHPStats.phar/lib/'.substr(str_replace('\\', '/', $className).'.php', 9);
	@include($classPath);
});
__HALT_COMPILER();