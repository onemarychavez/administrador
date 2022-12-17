<?php
namespace App\Models;

use CodeIgniter\Model;

class PedidosModel extends Model{
    protected $table = "pedidos";
    protected $primaryKey = "idpedidoo";
    protected $returnType = "array";
    protected $allowedFields = [
        'fecha',
        'idusuarioapp',
        'idempresa',
        'estado',
        'fecha',
        'direccion_entrega',
        'tipo_pago',
        'subtotal',
        'descuento',
        'comentario',
        'total',
        'activo'
    ];
    protected $skipValidation = true;
}