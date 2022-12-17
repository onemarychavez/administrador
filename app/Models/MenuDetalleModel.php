<?php
namespace App\Models;

use CodeIgniter\Model;

class MenuDetalleModel extends Model{
    protected $table = "menu_detalle";
    protected $primaryKey = "idmenudetalle";
    protected $returnType = "array";
    protected $allowedFields = [
        'idmenu',
        'nombre',
        'precio',
        'foto',
        'extras',
        'descripcion',
        'activo'
    ];
    protected $skipValidation = true;
}