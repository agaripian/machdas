<?php


namespace Behance\Action\User;

use Behance\Action;
use Behance\Model\Image;
use Behance\Utils\DatabaseUtils;
use Slim\Http\Request;
use Slim\Http\Response;

class GetAllImages extends Action\AbstractImpl
{
    public function __construct($app) {
        $this->app = $app;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function run(Request $request, Response $response, array $args) : Response
    {
       $user_id = intval($args['id']);
        //Check to see if user is authorized to add an image
        if ($user_id !== $this->app->user[0]->id) {
            return $response
                ->withStatus(401)
                ->write('Not Authorized');
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $builder = Image::query()->where('userid', '=', $user_id);

        return $response->withJson($builder->get());
    }
}
