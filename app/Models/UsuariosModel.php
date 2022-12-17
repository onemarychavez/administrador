<?php
namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model{
    protected $table = "usuarios";
    protected $primaryKey = "idusuario";
    protected $returnType = "array";
    protected $allowedFields = [
        'nombres',
        'apellidos',
        'dui',
        'nit',
        'usuario',
        'idempresa',
        'clave',
        'idrol',
        'activo'
    ];
    protected $skipValidation = true;
}