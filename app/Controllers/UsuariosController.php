<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\RolesModel;
use Throwable;

class UsuariosController extends BaseController
{
    
    private $usuario,$rol;
    protected $request,$session;
    public function __construct(){
             
        $this->request = \Config\Services::request();
        $this->usuario = new UsuariosModel();
        $this->session = \Config\Services::session();
        $this->rol = new RolesModel();
        
    }
    
    public function index()
    {   
        if(!$this->session->has('usuario')){
            return redirect()->to('');
        }
        $data = $this->session->get();
        return view('usuarios/index',['usuario'=>$data]);
    }

    public function listById($idusuario){
        try {
            $usuarios = $this->usuario
            ->select('usuarios.*,roles.rol as nombrerol')
            ->join('roles','roles.idrol=usuarios.idrol','inner')
            ->where(['usuarios.idusuario'=>$idusuario])
            ->first();
            if($usuarios!==null){
                $users= [
                    'key'=>intval($usuarios['idusuario']),
                    'nombres'=>$usuarios['nombres'],
                    'apellidos'=>$usuarios['apellidos'],
                    'dui'=>$usuarios['dui'],
                    'nit'=>$usuarios['nit'],
                    'usuario'=>$usuarios['usuario'],
                    'clave'=>$usuarios['password'],
                    'rol'=>intval($usuarios['idrol'])
                ];
            
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($users);
            }else{
                return $this->response->setStatusCode(404,'Not Content');
            }

        } catch (Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function list(){
        try {
            $usuarios = $this->usuario
            ->select('usuarios.*,roles.rol as nombrerol')
            ->join('roles','roles.idrol=usuarios.idrol','inner')
            ->findAll();

            if(count($usuarios)>0){
                $users = [];
                foreach($usuarios as $row){
                    array_push($users,[
                        'key'=>intval($row['idusuario']),
                        'nombres'=>$row['nombres'],
                        'apellidos'=>$row['apellidos'],
                        'dui'=>$row['dui'],
                        'nit'=>$row['nit'],
                        'usuario'=>$row['usuario'],
                        'clave'=>$row['clave'],
                        'keyrol'=>intval($row['idrol']),
                        'rol'=>intval($row['nombrerol'])
                    ]);
                }
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($users);
        }else{
            return $this->response->setStatusCode(204,'Not Content');
        }

    } catch (Throwable $th) {
        return $this->response->setStatusCode(500,'internal server')
        ->setJSON(['message'=>$th->getMessage()]);
    }
}

    
    public function getRoles(){
        try {
            $respuesta = $this->rol->findAll();
            
            if(count($respuesta)>0){
                 $data =[];
                 foreach($respuesta as $row ){
                    array_push($data,[
                        'keyrol'=>intval($row['idrol']),
                        'rol'=>$row['rol']
                    ]);
                 }   
                return $this->response->setStatusCode(200,'OK')
                    ->setJSON($data);
            }else{
                return $this->response->setStatusCode(404,'Not Content');
            }

        } catch (Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }


    public function create(){
        try {
            $datos = $this->request->getJSON(true);
            $id = $this->usuario
            ->insert([
                'nombres'=>$datos['nombres'],
                'apellidos'=>$datos['apellidos'],
                'dui'=>$datos['dui'],
                'nit'=>$datos['nit'],
                'usuario'=>$datos['usuario'],
                'clave'=>$datos['clave'],
                'idrol'=>$datos['rol']
            ],true);
            if($id>0){
                return $this->response->setStatusCode(200,'OK')
                ->setJSON(['keyuser'=>$id]);
               }else{
                return $this->response->setStatusCode(400,'error')
                ->setJSON(['message'=>'error al guardar el usuario']);
               }
    
        } catch (Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function update($idusuario){
        try {
            $datos = $this->request->getJSON(true);
            $id = $this->usuario
            ->update($idusuario,[
                'nombres'=>$datos['nombres'],
                'apellidos'=>$datos['apellidos'],
                'dui'=>$datos['dui'],
                'nit'=>$datos['nit'],
                'usuario'=>$datos['usuario'],
                'clave'=>$datos['clave'],
                'idrol'=>$datos['rol']

            ]);
            return $this->response->setStatusCode(200,'OK')
             ->setJSON(['keyuser'=>$id]);
        
 
         } catch (Throwable $th) {
             return $this->response->setStatusCode(500,'internal server')
             ->setJSON(['message'=>$th->getMessage()]);
         }
    }

    public function delete ($idusuario){
        try {
            $this->usuario->delete($idusuario);
            return $this->response->setStatusCode(200,'OK')
            ->setJSON(['keyuser'=>$idusuario]);

        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
             ->setJSON(['message'=>$th->getMessage()]);
        }
    }
}
