<?php
namespace Framework\core;

class Bootstrap
{
    public static function run(Request $request)
    {
        $control = $request->getController() . 'Controller';
        $method = $request->getMethod();
        $args = $request->getArgs();

        $controller = Factory::controller($control);

        if(is_callable(array($controller, $method))){
            $method = $request->getMethod();
        }
        else{
            $method = 'index';
        }

        if(isset($args)){
            call_user_func_array(array($controller, $method), $args);
        }
        else{
            call_user_func(array($controller, $method));
        }
    }
}