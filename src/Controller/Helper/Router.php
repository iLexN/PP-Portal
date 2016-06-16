<?php

namespace PP\Portal\Controller\Helper;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router extends AbstractContainer
{
    /**
     * Login Post action.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

        /* @var $router \Slim\Router */
        $router = $this->c->router;

        //exclude route
        $router->removeNamedRoute('helperRouter');

        return $this->c['twigView']->render($response, 'helper/router.html.twig', [
            'router' => $router,
        ]);
    }
}
