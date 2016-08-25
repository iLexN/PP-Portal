<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ClaimInfo extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $claimInfo = $this->ClaimModule->claim;
        $bankInfo = $claimInfo->bankInfo()->first();

        $out = $claimInfo->toArray();
        $out['file_attachments'] = $claimInfo->fileAttachments()->where('status','Upload')->get()->toArray();
        $out['bank_info'] = is_null($bankInfo)? null : $bankInfo->toArray();

        return $this->ViewHelper->withStatusCode($response, [
                    //'data' => $claimInfo->toArray(),
                    'data' => $out,
                ], 6030);
    }
}
