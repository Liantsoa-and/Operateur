<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeModel extends Model
{
    protected $table = 'bareme';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'description',
        'min',
        'max',
        'frais',
        'id_type_operation',
    ];

    protected $returnType = 'array';
}