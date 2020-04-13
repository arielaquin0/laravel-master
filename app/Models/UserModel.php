<?php

namespace App\Models;

use App\Models\BaseModel;

class UserModel extends BaseModel
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    const MEMBER_STATUS_ACTIVE = 1;
    const MEMBER_STATUS_INACTIVE = 0;

    const UPDATED_AT = null;
    const CREATED_AT = null;



}
