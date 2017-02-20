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
}