<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('o-nas', 'AboutUs:default');
		$router->addRoute('sluzba<id>', 'Home:detail');
		$router->addRoute('sluzba<service_id>/objednavka_p<program_id>', 'Home:order');
		$router->addRoute('objednavka_<id>/platba', 'Home:payment');
		$router->addRoute('prihlaseni', 'Admin:login');
		$router->addRoute('registrace', 'Admin:register');
		$router->addRoute('profil', 'Admin:profile');
		$router->addRoute('admin/platby', 'Admin:pay');
		$router->addRoute('admin/kariera', 'Admin:career');
		$router->addRoute('admin/stiznosti', 'Admin:complaint');
		$router->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
		return $router;
	}
}
