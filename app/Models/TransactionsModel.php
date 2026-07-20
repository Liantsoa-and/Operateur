<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'numero_transaction',
        'montant',
        'frais',
        'date_transaction',
        'id_client',
        'id_destinataire',
        'id_bareme',
    ];

    protected $returnType = 'array';
}