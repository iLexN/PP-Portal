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

        $groupFileAttachments = $claimInfo->fileAttachments()->where('status', 'Upload')->get()->groupBy('file_type');

        $out = $claimInfo->toArray();
        $out['file_attachments'] = [
            'support_doc'   => $this->getDataByGroup($groupFileAttachments, 'support_doc'),
            'claim_form'    => $this->getDataByGroup($groupFileAttachments, 'claim_form'),
        ];

        switch ($claimInfo->payment_method) {
            case 'Bank transfer':
                $bankInfo = $claimInfo->bankInfo()->first();
                $out['bank_info'] = is_null($bankInfo) ? null : $bankInfo->toArray();
                break;
            case 'Cheque':
                $cheque = $claimInfo->cheque()->first();
                $out['cheque'] = is_null($cheque) ? null : $cheque->toArray();
                break;
            default:
                break;
        }

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $out,
                ], 6030);
    }
}
