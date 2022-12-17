<?php
namespace App\Models;

use CodeIgniter\Model;

class DepartamentosModel extends Model{
    protected $table = "departamentos";
    protected $primaryKey = "iddepartamento";
    protected $returnType = "array";
    protected $allowedFields = [
        'idpais',
        'nombre',
        'activo'
    ];
    protected $skipValidation = true;
}