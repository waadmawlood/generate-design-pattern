<?php

namespace Waad\RepoMedia\Helpers;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;

class Routing extends OriginalRegistrar
{
    protected $resourceDefaults = ['index', 'getAll', 'getMe', 'store', 'show', 'update', 'change', 'destroy', 'delete', 'restore'];


    protected function addResourceGetAll($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/get/all';
        $action = $this->getResourceAction($name, $controller, 'getAll', $options);
        return $this->router->get($uri, $action);
    }

    protected function addResourceGetMe($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/get/me';
        $action = $this->getResourceAction($name, $controller, 'getMe', $options);
        return $this->router->get($uri, $action);
    }

    protected function addResourceChange($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/change/{'.$base.'}';
        $action = $this->getResourceAction($name, $controller, 'change', $options);
        return $this->router->post($uri, $action);
    }

    protected function addResourceDelete($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/delete/{'.$base.'}';
        $action = $this->getResourceAction($name, $controller, 'delete', $options);
        return $this->router->delete($uri, $action);
    }

    protected function addResourceRestore($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/restore/{'.$base.'}';
        $action = $this->getResourceAction($name, $controller, 'restore', $options);
        return $this->router->post($uri, $action);
    }


}
