<?php

namespace PP\Portal\Controller\Helper;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Code extends AbstractContainer
{
    /**
     * Router helper.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $ar = $this->msgCode;

        foreach ($ar as $k) {
            echo  '<string name="erro_code_'.$k['code'].'">'.$k['title'].'</string>'."\n";
        }
    }
}
