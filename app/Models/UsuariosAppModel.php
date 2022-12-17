<?php
namespace App\Models;

use CodeIgniter\Model;

class UsuariosAppModel extends Model{
    protected $table = "usuarios_app";
    protected $primaryKey = "idusuarioapp";
    protected $returnType = "array";
    protected $allowedFields = [
        'nombres',
        'apellidos',
        'usuario',
        'clave',
        'direccion',
        'telefono',
        'idrol',
        'activo'
    ];
    protected $skipValidation = true;
}