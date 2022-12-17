<?php
namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model{
    protected $table = "menu";
    protected $primaryKey = "idmenu";
    protected $returnType = "array";
    protected $allowedFields = [
        'idempresa',
        'nombre',
        'idcategoria',
        'activo'
    ];
    protected $skipValidation = true;
}