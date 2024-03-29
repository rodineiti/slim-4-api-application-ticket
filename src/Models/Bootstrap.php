<?php 

namespace App\Models;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

final class Bootstrap
{
    public static function load($container)
    {        
        $settings = $container->get('settings');            
        $capsule = new Capsule();        
        $capsule->addConnection($settings['db_dev']);
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}