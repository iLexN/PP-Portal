<?php

namespace PP\Portal\Module\Helper;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;

class View extends AbstractContainer
{
    public function toJson(ResponseInterface $response, $array)
    {
        if ($this->c->get('jsonConfig')['prettyPrint']) {
            return $response->withHeader('Content-type', 'application/json')
                   ->write(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        return $response->withHeader('Content-type', 'application/json')
                   ->write(json_encode($array, JSON_UNESCAPED_SLASHES));
    }
}
