<?php

namespace Waad\RepoMedia\Helpers;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;

class Routing extends OriginalRegistrar
{
    protected $resourceDefaults = ['index', 'indexAll', 'indexMe', 'store', 'show', 'update', 'change', 'destroy', 'delete','restore'];


    protected function addResourceIndexAll($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/index/all';
        $action = $this->getResourceAction($name, $controller, 'indexAll', $options);
        return $this->router->get($uri, $action);
    }

    protected function addResourceIndexMe($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/index/me';
        $action = $this->getResourceAction($name, $controller, 'indexMe', $options);
        return $this->router->get($uri, $action);
    }

    protected function addResourceChange($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/change/{id}';
        $action = $this->getResourceAction($name, $controller, 'change', $options);
        return $this->router->post($uri, $action);
    }

    protected function addResourceDelete($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/delete/{id}';
        $action = $this->getResourceAction($name, $controller, 'delete', $options);
        return $this->router->delete($uri, $action);
    }

    protected function addResourceRestore($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/restore/{id}';
        $action = $this->getResourceAction($name, $controller, 'restore', $options);
        return $this->router->put($uri, $action);
    }


}
