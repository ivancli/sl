<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/02/2017
 * Time: 5:22 PM
 */

namespace App\Models;


use IvanCLI\UM\UMRole;

class Role extends UMRole
{
    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    protected $appends = [
        'numberOfUsers', 'urls',
    ];


    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/
    public function getNumberOfUsersAttribute()
    {
        return $this->users()->count();
    }

    /**
     * an attribute with an array of routes related to this object
     * @return array
     */
    public function getUrlsAttribute()
    {
        return array(
            'show' => route('role.show', $this->getKey()),
            'store' => route('role.store'),
            'edit' => route('role.edit', $this->getKey()),
            'update' => route('role.update', $this->getKey()),
            'delete' => route('role.destroy', $this->getKey()),
        );
    }
}