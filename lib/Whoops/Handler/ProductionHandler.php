<?php

/*
 * This file is part of the Fuel Whoops package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Whoops\Handler;

use Whoops\Util\Misc;

/**
 * Handler for error messages in a production environment
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class ProductionHandler extends Handler
{
	/**
	 * {@inheritdoc}
	 */
	public function handle()
	{
		if (\Fuel::$env === \Fuel::PRODUCTION)
		{
			if (Misc::canSendHeaders())
			{
				$protocol = \Input::server('SERVER_PROTOCOL') ? \Input::server('SERVER_PROTOCOL') : 'HTTP/1.1';

				header($protocol.' 500 Internal Server Error');
			}

			echo \View::forge('errors'.DS.'production');

			return Handler::QUIT;
		}

		return Handler::DONE;
	}
}
