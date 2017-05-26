<?php

namespace Behance\Model;

use Illuminate\Database\Eloquent\Model;
use Respect\Validation\Validator;

/**
 * @property int     $id
 * @property string  $name
 * @property string  $url
 * @property string $description
 * @property int     $userid
 */
class Image extends Model
{

    /**
     * @return Validator[]
     */
    public static function validators() : array
    {
        return [
            'name'        => Validator::stringType()->notEmpty()->setName('name'),
            'url'         => Validator::stringType()->notEmpty()->setName('url'),
            'description' => Validator::stringType()->notEmpty()->setName('description'),
            'userid'      => Validator::intType()->notEmpty()->callback(function ($id) {
                return Image::query()->find($id) instanceof User;
            })->setName('userid')
        ];
    }

    /**
     * @var string
     */
    protected $table = 'images';

    /**
     * @var bool
     */
    public $timestamps = false;
}
