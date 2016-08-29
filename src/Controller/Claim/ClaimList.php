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
            'Save'   => $this->getData($group, 'Save'),
            'Submit' => $this->getData($group, 'Submit'),
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

    private function getData($group, $s)
    {
        return $group->has($s) ? $group->get($s) : [];
    }
}
