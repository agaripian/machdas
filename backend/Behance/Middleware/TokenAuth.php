<?php

namespace Behance\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use Behance\Model\User;

class TokenAuth {
    const WHITELIST = ['\/auth'];

    public function __construct($app) {
        $this->app = $app;
    }

    /**
     * Check against the DB if the token is valid
     *
     * @param string $auth_token
     * @return bool
     */
    public function authenticate_and_set_user($auth_token) {
       $this->user = $this->app->user = User::query()->where('token', '=', $auth_token)->get();
       if (isset($this->user[0])) {
           return true;
       }
       return false;
    }

    /**
     * This function will compare the provided url against the whitelist and
     * return wether the $url is public or not
     *
     * @param string $url
     * @return bool
     */
    public function isPublicUrl($url) {
        $patterns_flattened = implode('|', self::WHITELIST);
       // var_dump($patterns_flattened); die();
        $matches = null;
        preg_match('/' . $patterns_flattened . '/', $url, $matches);
        return (count($matches) > 0);
    }

    public function __invoke(Request $request, Response $response, $next) {
        //Get the token sent from jquery
        $auth_token = $request->getHeader('auth_token');
        //We can check if the url requested is public or protected
        if ($this->isPublicUrl($request->getUri())) {
            //if public, then we just call the next middleware and continue execution normally
            return $next($request, $response);
        } else {
            //If protected url, we check if our token is valid and set the user
            if ($request->hasHeader('auth_token') && $this->authenticate_and_set_user($request->getHeader('auth_token')[0])) {
                return $next($request, $response);
            } else {
                return $response
                    ->withStatus(403)
                    ->write('Not Authorized');
            }
        }
    }

}
