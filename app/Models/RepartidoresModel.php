<?php
namespace App\Models;

use CodeIgniter\Model;

class RepartidoresModel extends Model{
    protected $table = "repartidores";
    protected $primaryKey = "idrepartidor";
    protected $returnType = "array";
    protected $allowedFields = [
        'nombre',
        'apellidos',
        'fecha_nacimiento',
        'estado_civil',
        'telefono',
        'foto',
        'n_moto',
        'activo'
    ];
    protected $skipValidation = true;
}