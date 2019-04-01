<?php


namespace App\Service;


class Router
{
    /**
     * @var Request
     */
    private $request;

    private $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    function __call($name, $args)
    {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHeader();
        }
        $this->{strtolower($name)}[$this->trimRoute($route)] = $method;
    }

    private function invalidMethodHeader()
    {
        header("{$this->request->getRequestMethod()} 405 Method Not Allowed");
    }

    /**
     * @param $route string
     * @return string
     */
    private function trimRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    function __destruct()
    {
        $this->resolve();
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        $methodDictionary = $this->{strtolower($this->request->getRequestMethod())};
        $formattedRoute = $this->trimRoute($this->request->getRequestUri());
        $method = $methodDictionary[$formattedRoute];
        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }
        echo call_user_func_array($method, array($this->request));
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->getServerProtocol()} 404 Not Found");
    }
}