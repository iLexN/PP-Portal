<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ClaimInfo extends AbstractContainer
{
    private $claimInfo;

    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $this->claimInfo = $this->ClaimModule->claim;

        $out = $this->getInfo();

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $out,
                ], 6030);
    }

    private function getInfo()
    {
        $item = $this->pool->getItem('Claim/'.$this->claimInfo->claim_id.'/detailInfo');

        $info = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $info = $this->getOutPut();
            $this->pool->save($item->set($info));
        }

        return $info;
    }

    private function getOutPut()
    {
        $out = $this->claimInfo->toArray();
        $out['file_attachments'] = $this->getFileAttachment();

        switch ($this->claimInfo->payment_method) {
            case 'Bank Transfer':
                $out['bank_info'] = $this->getBankInfo();
                break;
            case 'Cheque':
                $out['cheque'] = $this->getCheque();
                break;
        }

        return $out;
    }

    private function getFileAttachment()
    {
        $groupFileAttachments = $this->claimInfo->fileAttachments()->where('status', 'Upload')->get()->groupBy('file_type');

        return [
            'support_doc'   => $this->getDataByGroup($groupFileAttachments, 'support_doc'),
            'claim_form'    => $this->getDataByGroup($groupFileAttachments, 'claim_form'),
        ];
    }

    private function getBankInfo()
    {
        $bankInfo = $this->claimInfo->bankInfo()->first();

        return is_null($bankInfo) ? null : $bankInfo->toArray();
    }

    private function getCheque()
    {
        $cheque = $this->claimInfo->cheque()->first();

        return is_null($cheque) ? null : $cheque->toArray();
    }
}
