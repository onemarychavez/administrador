<?php

namespace App\Controllers;


use App\Models\MunicipiosModel;
use CodeIgniter\HTTP\IncomingRequest;

class MunicipiosController extends BaseController
{
    
    protected $request,$session,$municipio;
    public function __construct(){
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->municipio = new MunicipiosModel();
        
        
    }
    
    public function list(){
        try {
            $municipios = $this->municipio->where('activo',true)->findAll();
            if(count($municipios)<0){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO HAY municipios']);
            }
            $rows = [];
            foreach($municipios  as $municipio){
                array_push($rows, [
                    'key'=>intval($municipio['idmunicipio']),
                    'keyDepartamento'=>intval($municipio['iddepartamento']),    
                    'nombre'=>$municipio['nombre']
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
