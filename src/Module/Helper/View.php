<?php

namespace PP\Portal\Module\Helper;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;

class View extends AbstractContainer
{
    public function toJson(Response $response, $ar)
    {
        $array = $this->appendStatus($ar);

        if ($this->c->get('jsonConfig')['prettyPrint']) {
            return $response->withHeader('Content-type', 'application/json')
                   ->write(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        return $response->withHeader('Content-type', 'application/json')
                   ->write(json_encode($array, JSON_UNESCAPED_SLASHES));
    }

    private function appendStatus($array){
        if ( isset($array['data']['code'])) {
            $array['status_code'] = $array['data']['code'];
        } else if ( isset($array['errors']['code'])) {
            $array['status_code'] = $array['errors']['code'];
        } else if ( !isset($array['status_code'])) {
            $array['status_code'] = $this->c['msgCode'][1530]['code'];
        }
        return $array;
    }
}
