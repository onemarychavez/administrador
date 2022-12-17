<?php
namespace App\Models;

use CodeIgniter\Model;

class PedidosRepartidorModel extends Model{
    protected $table = "pedidos_repartidor";
    protected $primaryKey = "idepedidrepar";
    protected $returnType = "array";
    protected $allowedFields = [
        'idpedido',
        'idrepartidor',
        'activo'
    ];
    protected $skipValidation = true;
}