<?php

namespace App\Controllers;

use App\Models\PermisosModel;
use App\Models\RolesModel;
use App\Models\RolesPermisosModel;
use App\Models\SucursalesModel;
use CodeIgniter\HTTP\IncomingRequest;

class RolesController extends BaseController
{
    
    protected $request,$session,$rol,$permisos,$perRol;
    public function __construct(){
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->rol = new RolesModel();
        $this->permisos = new PermisosModel();
        $this->perRol = new RolesPermisosModel();
    }
    


    public function list(){
        try {
            $roles = $this->rol->where('activo',true)->findAll();
            if(count($roles)<0){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO HAY ROLES']);
            }
            $rows = [];
            foreach($roles  as $rol){
                array_push($rows, [
                    'key'=>intval($rol['idrol']),
                    'nombre'=>$rol['rol']
                ]);
            }
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($rows);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    } 

    private function getPermisos($idrol){
        $per = [];
        $permisos = $this->perRol->select("roles_permisos.*,permiosos.permiso")
        ->join('permiso','permisos.idpermiso=roles_permisos.idpermiso','inner')
        ->where([
            'roles_permisos.activo'=>true,
            'roles_permisos.idrol'=>$idrol
        ])->findAll();

        foreach($permisos as $permiso){
            array_push($per,[
                'key'=>intval($permiso['idrolpe']),
                'keyrol'=>intval($permiso['idrol']),
                'keypermiso'=>intval($permiso['idpermiso']),
                'nombre'=>$permiso['permiso']
            ]);
        }
        return $per;
    }

    public function listById($id){
        try {
            $rol = $this->rol->find($id);
            if($rol== null){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO SE ENCONTRO EL ROL']);
            }
            
            $row=[
                'key'=>intval($rol['idrol']),
                'nombre'=>$rol['rol'],
                'permisos'=>$this->getPermisos($id)
            ];
            
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($row);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    
   
}
