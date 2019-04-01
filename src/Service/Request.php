<?php


namespace App\Service;


class Request
{
    const PARAM_ORDER_BY = 'orderby';
    const PARAM_ORDER_DIR = 'direction';
    const PARAM_PAGE_NUMBER = 'page';

    /**
     * @var string
     */
    private $requestMethod;

    /**
     * @var string
     */
    private $requestUri;

    /**
     * @var string
     */
    private $serverProtocol;

    /**
     * @var string
     */
    private $body = '';

    /**
     * @var string
     */
    private $token = '';

    /**
     * @var array
     */
    private $parameters = [];

    function __construct()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->requestUri = $_SERVER['REQUEST_URI'];
        $this->serverProtocol = $_SERVER['SERVER_PROTOCOL'];

        $this->grabBody();
        $this->grabToken();
        $this->parseParameters();
    }

    private function grabBody(): void
    {
        if ($this->requestMethod == "POST") {
            $this->body = file_get_contents('php://input');
        }
    }

    private function grabToken(): void
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION']) && strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer') === 0) {
            $bearer = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
            if (count($bearer) == 2) {
                $this->token = $bearer[1];
            }
        }
    }

    private function parseParameters(): void
    {
        $s = '';
        @parse_str(parse_url($this->requestUri)['query'], $s);
        $this->parameters = $s;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->requestUri;
    }

    /**
     * @return string
     */
    public function getServerProtocol(): string
    {
        return $this->serverProtocol;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}