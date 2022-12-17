<?php

namespace App\Controllers;

use App\Models\CategoriasModel;
use App\Models\EmpresasModel;
use App\Models\SucursalesModel;
use App\Models\UsuariosModel;
use CodeIgniter\HTTP\IncomingRequest;

class CategoriaController extends BaseController
{
    
    private $categoria;
    protected $request,$session;
    public function __construct(){
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->categoria = new CategoriasModel();
    }
    
    
    public function list(){
        try {
            $categorias = $this->categoria->where('activo',true)->findAll();
            if(count($categorias)<0){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO HAY CATEGORIAS']);
            }
            $rows = [];
            foreach($categorias  as $row){
                array_push($rows,[
                    'key'=>intval($row['idcategoria']),
                    'nombre'=>$row['nombre']
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
