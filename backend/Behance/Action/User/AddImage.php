<?php


namespace Behance\Action\User;

use Behance\Action;
use Behance\Model\User;
use Behance\Model\Image;
use Behance\Utils\DatabaseUtils;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Http\Request;
use Slim\Http\Response;

class AddImage extends Action\AbstractImpl
{

    public function __construct($app) {
        $this->app = $app;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws NestedValidationException
     */
    public function run(Request $request, Response $response, array $args) : Response
    {
        /* @var User $user */
        $user = User::query()->findOrFail($args['id']);

        //Check to see if user is authorized to add an image
        if ($user->id !== $this->app->user[0]->id) {
            return $response
                ->withStatus(401)
                ->write('Not Authorized');
        }

        // add image
        $model               = new Image();
        $model->name         = $request->getParsedBodyParam('name');
        $model->url          = $request->getParsedBodyParam('url');
        $model->description  = $request->getParsedBodyParam('description');
        $model->userid       = $user->id;

        // validation
        Image::validators()['name']->assert($model->name);
        Image::validators()['url']->assert($model->url);
        Image::validators()['description']->assert($model->description);

        // save
        $model->saveOrFail();

        // response
        return $response
            ->withStatus(201)
            ->withHeader('Location', sprintf('/user/addimage/%d', $model->id))
            ->withJson($model);
    }
}
