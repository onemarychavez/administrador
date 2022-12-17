<?php
namespace App\Models;

use CodeIgniter\Model;

class PermisosModel extends Model{
    protected $table = "permisos";
    protected $primaryKey = "idpermiso";
    protected $returnType = "array";
    protected $allowedFields = [
        'permiso',
        'activo'
    ];
    protected $skipValidation = true;
}