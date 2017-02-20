<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/02/2017
 * Time: 5:22 PM
 */

namespace App\Models;


use IvanCLI\UM\UMGroup;

class Group extends UMGroup
{
    protected $appends = [
        'numberOfUsers', 'urls'
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
            'show' => route('group.show', $this->getKey()),
            'store' => route('group.store'),
            'edit' => route('group.edit', $this->getKey()),
            'update' => route('group.update', $this->getKey()),
            'delete' => route('group.destroy', $this->getKey()),
        );
    }
}