<?php

namespace PP\Portal\Controller\Holder;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\HolderInfo;
use PP\Portal\DbModel\HolderInfoUpdate;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Update extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $holderInfo = HolderInfo::find($args['id']);

        if (!$holderInfo) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $holderInfo->getFillable());
        $v->rule('required', ['policy_address_line_2']);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $inArray = $this->processData($v->data(), $args['id']);
        $new = $this->saveData($inArray);
        $this->pool->deleteItem('Holder/'.$args['id']);

        return $this->ViewHelper->withStatusCode(
            $response,
            ['data' => $new->toArray()],
            2641
        );
    }

    private function processData($ar, $id)
    {
        $inArray = $ar;

        unset($inArray['id']);
        $inArray['holder_id'] = $id;
        $inArray['status'] = 'Pending';

        return $inArray;
    }

    private function saveData($inArray)
    {
        $new = new HolderInfoUpdate();

        foreach ($inArray as $k => $v) {
            $new->{$k} = $v;
        }
        $new->save();

        return $new;
    }
}
