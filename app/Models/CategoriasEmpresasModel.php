<?php
namespace App\Models;

use CodeIgniter\Model;

class CategoriasEmpresasModel extends Model{
    protected $table = "categorias_empresas";
    protected $primaryKey = "idcateemp";
    protected $returnType = "array";
    protected $allowedFields = [
        'idempresa',
        'idcategoria',
        'activo'
    ];
    protected $skipValidation = true;
}