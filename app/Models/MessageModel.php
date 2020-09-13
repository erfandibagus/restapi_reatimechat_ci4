<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages';
    protected $allowedFields = ['user_id', 'message', 'attachment'];
    protected $useTimestamps = true;
}
