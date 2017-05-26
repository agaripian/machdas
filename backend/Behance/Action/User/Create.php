<?php


namespace Behance\Action\User;

use Behance\Action;
use Behance\Model\User;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Http\Request;
use Slim\Http\Response;

class Create extends Action\AbstractImpl
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws NestedValidationException
     */
    public function run(Request $request, Response $response, array $args) : Response
    {
        // create model
        $model       = new User();
        $model->first_name = $request->getParsedBodyParam('first_name', false);
        $model->last_name = $request->getParsedBodyParam('last_name', false);
        $model->email = $request->getParsedBodyParam('email', false);
        $model->password = $request->getParsedBodyParam('password', false);
        $model->token = bin2hex(openssl_random_pseudo_bytes(8));

        // validation
        User::validators()['first_name']->assert($model->first_name);
        User::validators()['last_name']->assert($model->last_name);
        User::validators()['email']->assert($model->email);
        User::validators()['password']->assert($model->password);

        // create
        $model->saveOrFail();

        // response
        return $response
            ->withStatus(201)
            ->withHeader('Location', sprintf('/auth/signup/%s', $model->id))
            ->withJson($model);
    }
}
