<?php

namespace PP\Portal\Controller\Helper;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class Router extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {

        /* @var $router \Slim\Router */
        $router = $this->c->router;

        //exclude route
        $router->removeNamedRoute('helperRouter');

        return $this->twigView->render($response, 'helper/router.html.twig', [
            'router' => $router,
        ]);
    }
}
