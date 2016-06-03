<?php

namespace PP\Portal\Controller\Policy;

use League\Fractal\Resource\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PolicyList
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * Login Post action.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if ($this->isUserExist($args['id'])) {
            /* @var $client \PP\Portal\dbModel\Client */
            $client = $this->c['UserModule']->client;

            /* @var $policyList \PP\Portal\dbModel\Policies */
            $policyList = $client->policies()
                    ->order_by_desc('Policy_ID')
                    ->find_many();

            $resource = new Collection($policyList, function (\PP\Portal\dbModel\Policies $policy) {
                return $policy->as_array();
            });

            return $this->c['view']->render($request, $response,
                    $this->c['dataManager']->createData($resource)->toArray());
        }

        return $this->c['view']->render($request, $response, ['errors' => [
            'title' => 'User Info Not Found',
        ]]);
    }

    /**
     * check email in db or not(already user?).
     *
     * @param int $id
     *
     * @return bool
     */
    private function isUserExist($id)
    {
        return $this->c['UserModule']->isUserExistByID($id);
    }
}
