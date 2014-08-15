<?php

/*
 * This file is part of the Fuel Whoops package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Autoloader::add_core_namespace('Indigo\\Fuel', true);

\Autoloader::add_classes(array(
	'Indigo\\Fuel\\Error'             => __DIR__.'/classes/Error.php',
	'Indigo\\Fuel\\PhpErrorException' => __DIR__.'/classes/PhpErrorException.php',
));
