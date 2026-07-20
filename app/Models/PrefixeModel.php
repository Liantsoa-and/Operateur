<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table = 'prefixe';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'debut_numero',
        'id_operateur',
    ];

    protected $returnType = 'array';
}