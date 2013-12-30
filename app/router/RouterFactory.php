<?php

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList;

		$router[] = new Route('[<command>/]','Homepage:default');
		$router[] = new Route('<presenter>/<action>[/<id>]','Homepage:default');

		return $router;
	}

}
