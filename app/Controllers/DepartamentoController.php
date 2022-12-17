<?php

namespace App\Controllers;

use App\Models\DepartamentosModel;
use App\Models\PaisModel;
use CodeIgniter\HTTP\IncomingRequest;

class DepartamentoController extends BaseController
{
    
    protected $request,$session,$departamento,$pais;
    public function __construct(){
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->pais = new PaisModel();
        $this->departamento = new DepartamentosModel();
        
    }
    
    public function list(){
        try {
            $departamentos = $this->departamento->where('activo',true)->findAll();
            if(count($departamentos)<0){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO HAY DEPARTAMENTOS']);
            }
            $rows = [];
            foreach($departamentos  as $departamento){
                array_push($rows, [
                    'key'=>intval($departamento['iddepartamento']),
                    'nombre'=>$departamento['nombre']
                ]);
            }
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($rows);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    } 

    

    

    
   
}
