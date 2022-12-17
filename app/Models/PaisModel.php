<?php
namespace App\Models;

use CodeIgniter\Model;

class PaisModel extends Model{
    protected $table = "pais";
    protected $primaryKey = "idpais";
    protected $returnType = "array";
    protected $allowedFields = [
        'nombre',
        'activo'
    ];
    protected $skipValidation = true;
}