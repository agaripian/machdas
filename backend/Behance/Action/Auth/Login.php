<?php


namespace Behance\Action\Auth;

use Behance\Action;
use Behance\Model\User;
use Behance\Utils\DatabaseUtils;
use Slim\Http\Request;
use Slim\Http\Response;

class Login extends Action\AbstractImpl
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function run(Request $request, Response $response, array $args) : Response
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $user = User::query()->where('email', '=', $request->getParsedBodyParam('user_name'))->get();
        $password = $request->getParsedBodyParam('password');

        if (isset($user[0]) && $user[0]->password === $password) {
            $user[0]->token = bin2hex(openssl_random_pseudo_bytes(8));
            $user[0]->saveOrFail();
            $data = array('user_id' => $user[0]->id, 'auth_token' => $user[0]->token);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withJson($data, 200);
        }

        return $response
            ->withStatus(403)
            ->write('Wrong Username or Password');

    }
}
