<?php

namespace App\Models;

use CodeIgniter\Model;

class VSoldeModel extends Model
{
    protected $table = 'v_solde';
    protected $primaryKey = 'id_client';

    protected $returnType = 'array';

    protected $allowedFields = [];

    protected $useAutoIncrement = false;
}