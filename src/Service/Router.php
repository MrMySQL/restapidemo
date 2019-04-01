<?php


namespace App\Service;


class Router
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    private $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    function __construct(Request $request, DatabaseManager $databaseManager)
    {
        $this->request = $request;
        $this->databaseManager = $databaseManager;
    }

    function __call($name, $args)
    {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHeader();
        }
        $this->{strtolower($name)}[$this->parseRoute($route)] = $method;
    }

    private function invalidMethodHeader()
    {
        header("{$this->request->getRequestMethod()} 405 Method Not Allowed");
    }

    /**
     * @param $route string
     * @return string
     */
    private function parseRoute($route)
    {
        $url = parse_url($route);
        $result = rtrim($url['path'], '/');
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
        $formattedRoute = $this->parseRoute($this->request->getRequestUri());

        if (!isset($methodDictionary[$formattedRoute])) {
            $this->defaultRequestHandler();
            return;
        }

        $method = $methodDictionary[$formattedRoute];
        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }
        echo call_user_func_array($method, array($this->request, $this->databaseManager));
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->getServerProtocol()} 404 Not Found");
        echo "404 Not found";
    }
}