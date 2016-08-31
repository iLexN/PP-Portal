<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

class ClaimList extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        //$status = $request->getQueryParam('status', 'All');

        $claims = $this->ClaimModule->getClaimList($this->UserPolicyModule->userPolicy);

        $group = $claims->groupBy('status');

        $out = [
            'Save'   => $this->getDataByGroup($group, 'Save'),
            'Submit' => $this->getDataByGroup($group, 'Submit'),
        ];
/*
        if ($status !== 'All') {
            return $this->ViewHelper->withStatusCode($response, [
                    'data' => isset($out[$status]) ? $out[$status] : [],
                ], 5030);
        }
*/
        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $out,
                ], 5030);
    }
}
