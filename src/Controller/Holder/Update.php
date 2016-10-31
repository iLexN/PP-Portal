<?php

namespace PP\Portal\Controller\Holder;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use PP\Portal\DbModel\HolderInfo;
use PP\Portal\DbModel\HolderInfoUpdate;

class Update extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $holderInfo = HolderInfo::find($args['id']);

        if (!$holderInfo) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $data = $request->getParsedBody();

        $v = new \Valitron\Validator($data, $holderInfo->getFillable());

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $new = new HolderInfoUpdate();
        $inArray = $v->data();

        unset($inArray['id']);
        $inArray['holder_id'] = $args['id'];
        $inArray['status'] = 'Pending';
        foreach ( $inArray as $k=>$v){
            $new->{$k} = $v;
        }
        $new->save();

        return $this->ViewHelper->withStatusCode($response, ['data' => $new->toArray()],
                2641);
    }

}
