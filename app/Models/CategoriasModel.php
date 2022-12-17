<?php
namespace App\Models;

use CodeIgniter\Model;

class CategoriasModel extends Model{
    protected $table = "categoria_negocio";
    protected $primaryKey = "idcategoria";
    protected $returnType = "array";
    protected $allowedFields = [
        'nombre',
        'activo'
    ];
    protected $skipValidation = true;
}