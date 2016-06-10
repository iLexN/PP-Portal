<?php

namespace PP\Module\Helper;

use Psr\Http\Message\ResponseInterface;

class View
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    public function toJson( ResponseInterface $response , $array)
    {
       if ( $this->c->get('jsonConfig')['prettyPrint'] ) {
           return $response->withHeader('Content-type','application/json')
                   ->write(json_encode($array , JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES ));
       }

           return $response->withHeader('Content-type','application/json')
                   ->write(json_encode($array , JSON_UNESCAPED_SLASHES ));
    }
}
