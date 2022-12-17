<?php

namespace App\Controllers;

use App\Models\RepartidoresModel;
use App\Models\UsuariosModel;
use CodeIgniter\HTTP\IncomingRequest;

class RepartidorController extends BaseController
{
    
    private $repartidor;
    protected $request,$session,$db;
    public function __construct(){
        $this->request = \Config\Services::request();
        $this->repartidor = new RepartidoresModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Services::database();
    }
    
    public function index()
    {   
        if(!$this->session->has('usuario')){
            return redirect()->to('');
        }
        return view('welcome_message');
    }

    public function list(){
        try {
            $repartidores = $this->repartidor->where('activo',true)->findAll();
            if(count($repartidores)<=0){
                return $this->response->setStatusCode(404,'not found')
                ->setJSON(['message'=>'NO SE ENCONTRARON REPARTIDORES']);
            }
            $repa = [];
            foreach($repartidores as $row){
                array_push($repa,[
                    'key'=>intval($row['idrepartidor']),
                    'nombres'=>$row['nombre'],
                    'apellidos'=>$row['apellidos'],
                    'fecha'=>$row['fecha_nacimiento'],
                    'estado'=>$row['estado_civil'],
                    'telefono'=>$row['telefono'],
                    'foto'=>$row['foto'],
                    'moto'=>$row['n_moto']
                ]);
            }

            return $this->response->setStatusCode(200,'OK')
            ->setJSON($repa);


        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function listById($id){
        try {
            $repartidor = $this->repartidor->find($id);
            if($repartidor==null){
                return $this->response->setStatusCode(404,'not found')
                ->setJSON(['message'=>'REPARTIDOR NO ENCONTRADO']);
            }
        
    
            $repa= [
                'key'=>intval($repartidor['idrepartidor']),
                'nombres'=>$repartidor['nombre'],
                'apellidos'=>$repartidor['apellidos'],
                'fecha'=>$repartidor['fecha_nacimiento'],
                'estado'=>$repartidor['estado_civil'],
                'telefono'=>$repartidor['telefono'],
                'foto'=>$repartidor['foto'],
                'moto'=>$repartidor['n_moto']
            ];
            
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($repa);


        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    private function saveImagen($image,$name,$id){
        $part = explode(".",$name);
        $imageName = '/images/repartidor/'.$id.$part[0].date('Ymdhis').'.'.$part[1];
        $decode = base64_decode($image);
        file_put_contents($imageName,$decode);
        return $imageName;
    }

    public function create(){
        try {
            $obligatorios = ['nombres','apellidos','fecha','estado','telefono','foto','n_moto'];
            $datos = $this->request->getJSON(true);
            $error = false;
            foreach($obligatorios as $camp){
                if(!isset($datos[$camp])){
                    $error = true;
                    break;
                }
            }//VALIDA LOS CAMPOS OBLIGATORIOS
            if($error){
                return $this->response->setStatusCode(400,'Client Error')
                ->setJSON(['message'=>'CAMPOS INVALIDOS']);
            }
            $this->db->transBegin();
            $img = trim($datos['foto']);
            $newRepartidor = [
                'nombre'=>trim($datos['nombres']),
                'apellidos'=>trim($datos['apellidos']),
                'fecha_nacimiento'=>trim($datos['fecha']),
                'estado_civil'=>$datos['estado'],
                'telefono'=>trim($datos['telefono']),
                'foto'=>'',
                'n_moto'=>trim($datos['n_moto'])
            ];

            $key = $this->repartidor->insert($newRepartidor);
            if(strlen($img)>0){
                $url = $this->saveImagen($img,$datos['imgname'],$key);
                $this->repartidor->update($key,[
                    'foto'=>$url
                ]);
            }
            $this->db->transCommit();
            return $this->response->setStatusCode(201,'CREATED')
            ->setJSON(['userKey'=>$key]);
            
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function update($id){
        try {
            $repa = $this->repartidor->find($id);
            if($repa== null){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO SE ENCONTRO REPARTIDOR']);
            }

            $obligatorios = ['nombres','apellidos','fecha','estado','telefono','foto','n_moto'];
            $datos = $this->request->getJSON(true);
            $error = false;
            foreach($obligatorios as $camp){
                if(!isset($datos[$camp])){
                    $error = true;
                    break;
                }
            }//VALIDA LOS CAMPOS OBLIGATORIOS
            if($error){
                return $this->response->setStatusCode(400,'Client Error')
                ->setJSON(['message'=>'CAMPOS INVALIDOS']);
            }
            $this->repartidor->update($id,[
                'nombre'=>trim($datos['nombres']),
                'apellidos'=>trim($datos['apellidos']),
                'fecha_nacimiento'=>trim($datos['fecha']),
                'estado_civil'=>$datos['estado'],
                'telefono'=>trim($datos['telefono']),
                'foto'=>'',
                'n_moto'=>trim($datos['n_moto'])
            ]);
            
            return $this->response->setStatusCode(200,'OK')
            ->setJSON(['message'=>'REPARTIDOR ACTUALIZADO']);


        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function delete($id){
        try {
            $repa = $this->repartidor->find($id);
            if($repa== null){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO SE ENCONTRO REPARTIDOR']);
            }
            $this->repartidor->update($id,['activo'=>false]);
            return $this->response->setStatusCode(200,'OK')
            ->setJSON(['message'=>'REPARTIDOR ELIMINADO']);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }
}
