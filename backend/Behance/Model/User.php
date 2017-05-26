<?php

namespace Behance\Model;

use Illuminate\Database\Eloquent\Model;
use Respect\Validation\Validator;

/**
 * @property int    $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 */
class User extends Model
{

    /**
     * @return Validator[]
     */
    public static function validators() : array
    {
        return [
            'first_name' => Validator::stringType()->notEmpty()->setName('first_name'),
            'last_name' => Validator::stringType()->notEmpty()->setName('last_name'),
            'password' => Validator::stringType()->notEmpty()->setName('password'),
            'email' => Validator::stringType()->notEmpty()->setName('email'),
            'token' => Validator::stringType()->setName('email'),
        ];
    }

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var bool
     */
    public $timestamps = false;
}
