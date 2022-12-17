<?php
namespace App\Models;

use CodeIgniter\Model;

class PedidosDetalleModel extends Model{
    protected $table = "pedidos_detalle";
    protected $primaryKey = "idpedidodetalle";
    protected $returnType = "array";
    protected $allowedFields = [
        'idpedido',
        'idmenudetalle',
        'cantidad',
        'extras',
        'total',
        'activo'
    ];
    protected $skipValidation = true;
}