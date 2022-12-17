<?php
namespace App\Models;

use CodeIgniter\Model;

class RolesPermisosModel extends Model{
    protected $table = "roles_permisos";
    protected $primaryKey = "idrolpe";
    protected $returnType = "array";
    protected $allowedFields = [
        'idrol',
        'idpermiso',
        'activo'
    ];
    protected $skipValidation = true;
}