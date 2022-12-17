<?php
namespace App\Models;

use CodeIgniter\Model;

class MunicipiosModel extends Model{
    protected $table = "municipios";
    protected $primaryKey = "idmunicipio";
    protected $returnType = "array";
    protected $allowedFields = [
        'iddepartamento',
        'nombre',
        'activo'
    ];
    protected $skipValidation = true;
}