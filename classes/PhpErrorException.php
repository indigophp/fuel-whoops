<?php

/*
 * This file is part of the Fuel Whoops package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Fuel;

/**
 * Error Exception override
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class PhpErrorException extends \Fuel\Core\PhpErrorException
{
	/**
	 * {@inheritdoc}
	 */
	public function recover()
	{
		// handle the error based on the config and the environment we're in
		if (static::$count <= \Config::get('errors.throttle', 10))
		{
			\Error::getWhoops()->handleError($this->code, $this->message, $this->file, $this->line);

			if ($this->code & error_reporting())
			{
				static::$count++;
			}
		}
		elseif (static::$count == (\Config::get('errors.throttle', 10) + 1)
				and ($this->severity & error_reporting()) == $this->severity)
		{
			static::$count++;
			\Error::notice('Error throttling threshold was reached, no more full error reports are shown.', true);
		}
	}
}
