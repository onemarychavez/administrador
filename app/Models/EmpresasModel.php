<?php
namespace App\Models;

use CodeIgniter\Model;

class EmpresasModel extends Model{
    protected $table = "empresas";
    protected $primaryKey = "idempresa";
    protected $returnType = "array";
    protected $allowedFields = [
        'nombre',
        'representante',
        'razonsocial',
        'telefono',
        'nrc',
        'giro',
        'logo',
        'activo'
    ];
    protected $skipValidation = true;
}