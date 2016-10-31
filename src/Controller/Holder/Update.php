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

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $holderInfo->getFillable());

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $new = new HolderInfoUpdate();
        $inArray = $this->saveData($v->data(),$args['id']);
        foreach ( $inArray as $k=>$v){
            $new->{$k} = $v;
        }
        $new->save();

        return $this->ViewHelper->withStatusCode($response, ['data' => $new->toArray()],
                2641);
    }

    private function saveData($ar,$id){
        $inArray = $ar;

        unset($inArray['id']);
        $inArray['holder_id'] = $id;
        $inArray['status'] = 'Pending';

        return $inArray;
    }

}
