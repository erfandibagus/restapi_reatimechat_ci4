<?php

namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table = 'logs';
    protected $allowedFields = ['user_id', 'ip_address', 'login_at', 'leave_at'];
}
