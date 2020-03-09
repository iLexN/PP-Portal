<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

class ClaimList extends AbstractContainer
{
    public function __invoke(Request $request, Response $response, array $args)
    {
        $claims = $this->ClaimModule->getClaimList($this->UserPolicyModule->userPolicy);

        $group = $claims->groupBy('status');

        $out = [
            'Save'   => $this->getDataByGroup($group, 'Save'),
            'Submit' => $this->getDataByGroup($group, 'Submit'),
        ];

        return $this->ViewHelper->withStatusCode($response, [
            'data' => $out,
        ], 5030);
    }
}
