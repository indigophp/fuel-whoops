<?php

/*
 * This file is part of the Indigo Whoops package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Whoops\Handler;

/**
 * Logger Handler
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class LoggerHandler extends Handler
{
	/**
	 * {@inheritdoc}
	 */
	public function handle()
	{
		$e = $this->getException();
		$severity = $e->getCode();

		if (isset(\Error::$levels[$severity]))
		{
			$severity = \Error::$levels[$severity];
		}

		logger(\Error::$loglevel, $severity.' - '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());

		return Handler::DONE;
	}
}
