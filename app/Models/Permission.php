<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/02/2017
 * Time: 5:22 PM
 */

namespace App\Models;


use IvanCLI\UM\UMPermission;

class Permission extends UMPermission
{
    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    protected $appends = [
        'urls',
    ];

    /**
     * an attribute with an array of routes related to this object
     * @return array
     */
    public function getUrlsAttribute()
    {
        return array(
            'show' => route('permission.show', $this->getKey()),
            'store' => route('permission.store'),
            'edit' => route('permission.edit', $this->getKey()),
            'update' => route('permission.update', $this->getKey()),
            'delete' => route('permission.destroy', $this->getKey()),
        );
    }
}