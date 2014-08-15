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

use Whoops\Run;
use Whoops\Handler\LoggerHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\ProductionHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\PlainTextHandler;

/**
 * Error class override to use whoops for errors
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Error extends \Fuel\Core\Error
{
	/**
	 * Whoops Run object
	 *
	 * @var Run
	 */
	protected static $whoops;

	public static function _init()
	{
		static::$whoops = new Run;

		$pagehandler = new PrettyPageHandler;

		$pagehandler->addDataTableCallback('Fuel Application', function() {
			return array(
				'Environment' => \Fuel::$env,
				'Locale'      => \Fuel::$locale,
				'Timezone'    => \Fuel::$timezone,
				'Encoding'    => \Fuel::$encoding,
				'Version'     => \Fuel::VERSION,
			);
		});

		$pagehandler->addDataTableCallback('Fuel Request', function() {
			$request = \Request::main();

			return array(
				'Original URI' => $request->uri->get(),
				'Mapped URI'   => $request->route->translation,
				'Controller'   => $request->controller,
				'Action'       => 'action_'.$request->action,
				'HTTP Method'  => \Input::method(),
			);
		});

		static::$whoops->pushHandler($pagehandler);

		if (\Input::is_ajax())
		{
			static::$whoops->pushHandler(new JsonResponseHandler);
		}

		if (\Fuel::$env === \Fuel::PRODUCTION)
		{
			static::$whoops->pushHandler(new ProductionHandler);
		}

		if (\Fuel::$is_cli === true)
		{
			static::$whoops->pushHandler(new PlainTextHandler);
			static::$whoops->allowQuit(false);
		}

		static::$whoops->pushHandler(new LoggerHandler);
	}

	/**
	 * Returns the Whoops Run object
	 *
	 * @return Run
	 */
	public static function getWhoops()
	{
		return static::$whoops;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function exception_handler(\Exception $e)
	{
		if (method_exists($e, 'handle'))
		{
			return $e->handle();
		}

		static::$whoops->handleException($e);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function shutdown_handler()
	{
		static::$whoops->handleShutdown();
	}
}
