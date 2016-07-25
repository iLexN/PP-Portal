<?php

namespace PP\Portal\Controller\Helper;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Code extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $ar = $this->msgCode;

        foreach ($ar as $k) {
            echo  '<string name="erro_code_'.$k['code'].'">'.$k['title'].'</string>'."\n";
        }
    }
}
