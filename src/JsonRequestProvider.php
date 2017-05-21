<?php

/**
 * Parse request params, if JSON request is submitted to Silex app
 */

namespace Phizzl\Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class JsonRequestProvider implements ServiceProviderInterface
{
    public function register(Container $container){
        if($container instanceof Application){
            $container->before([$this, 'doJsonRequestHandling']);
        }
    }

    public function doJsonRequestHandling(Request $request){
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
    }
}