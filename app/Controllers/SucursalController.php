<?php

namespace App\Controllers;

use App\Models\SucursalesModel;
use CodeIgniter\HTTP\IncomingRequest;

class SucursalController extends BaseController
{
    
    protected $request,$session,$sucursal,$db;
    public function __construct(){
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->sucursal = new SucursalesModel();
        $this->db = \Config\Services::database();
    }
    
    public function index()
    {   
        if(!$this->session->has('usuario')){
            return redirect()->to('');
        }
        return view('sucursal/index');
    }

    public function listById($id){
        try {
            
            $sucursal = $this->sucursal->find($id);
            if($sucursal == null){
                return  $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'SUCURSAL NO ENCONTRADA']);
            }
            $row = [
                'key'=>intval($sucursal['idsucursal']),
                'keyempresa'=>intval($sucursal['idempresa']),
                'nombre'=> $sucursal['nombre'],
                'telefono'=>$sucursal['telefono'],
                'keydepartamento'=>intval($sucursal['iddepartamento']),
                'keymunicipio'=>intval($sucursal['idmunicipio']),
                'direccion'=>$sucursal['direccion'],
                'encargado'=>$sucursal['encargado']
            ];
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($row);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function list(){
        try {
            $empresa = intval($this->session->get('idempresa'));
            $filtros = ["sucursales.activo"=>true ];
            if($empresa > 0){
               $filtros["sucursales.idempresa"]=$empresa;
            }
            $sucursales = $this->sucursal->select("sucursales.*,empresas.nombre as empresa,departamentos.nombre as departamento,municipios.nombre as municipio")
            ->join("empresas","empresas.idempresa=sucursales.idempresa","inner")
            ->join("departamentos","departamentos.iddepartamento=sucursales.iddepartamento","inner")
            ->join("municipios","municipios.idmunicipio=sucursales.idmunicipio","inner")
            ->where($filtros)->findAll();
            if(count($sucursales)<0){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO HAY SUCURSALES']);
            }
            $rows = [];
            foreach($sucursales  as $sucursal){
                array_push($rows, [
                    'key'=>intval($sucursal['idsucursal']),
                    'keyempresa'=>intval($sucursal['idempresa']),
                    'empresa'=>$sucursal['empresa'],
                    'nombre'=> $sucursal['nombre'],
                    'telefono'=>$sucursal['telefono'],
                    'keydepartamento'=>intval($sucursal['iddepartamento']),
                    'departamento'=>$sucursal['departamento'],
                    'keymunicipio'=>intval($sucursal['idmunicipio']),
                    'municipio'=>$sucursal['municipio'],
                    'direccion'=>$sucursal['direccion'],
                    'encargado'=>$sucursal['encargado']
                ]);
            }
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($rows);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function listByEmpresa($id){
        try {
            $sucursales = $this->sucursal->select("sucursales.*,empresas.nombre as empresa,departamentos.nombre as departamento,municipios.nombre as municipio")
            ->join("empresas","empresas.idempresa=sucursales.idempresa","inner")
            ->join("departamentos","departamentos.iddepartamento=sucursales.iddepartamento","inner")
            ->join("municipios","municipios.idmunicipio=sucursales.idmunicipio","inner")
            ->where([
               "sucursales.activo"=>true,
               "sucursales.idempresa"=>$id,
               "sucursales.iddepartamento"=>$id,
               "sucursales.idmunicipio"=>$id 
            ])->findAll();
            if(count($sucursales)<0){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'NO HAY SUCURSALES']);
            }
            $rows = [];
            foreach($sucursales  as $sucursal){
                array_push($rows, [
                    'key'=>intval($sucursal['idsucursal']),
                    'keyempresa'=>intval($sucursal['idempresa']),
                    'empresa'=>$sucursal['empresa'],
                    'nombre'=> $sucursal['nombre'],
                    'telefono'=>$sucursal['telefono'],
                    'keydepartamento'=>intval($sucursal['iddepartamento']),
                    'departamento'=>$sucursal['departamento'],
                    'keymunicipio'=>intval($sucursal['idmunicipio']),
                    'municipio'=>$sucursal['municipio'],
                    'direccion'=>$sucursal['direccion'],
                    'encargado'=>$sucursal['encargado']
                ]);
            }
            return $this->response->setStatusCode(200,'OK')
            ->setJSON($rows);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }
    

    public function create(){
        try {
            $data = $this->request->getJSON(true);
            $campos = ['empresa','nombre','departamento','municipio','direccion','telefono','encargado'];
            $validacion = false;
            foreach($campos as $row){
                if(!isset($data[$row])){
                    $validacion = true;
                    break;
                }
            }
            if($validacion){
                return $this->response->setStatusCode(400,'Validation Error')
                ->setJSON(['message'=>'PARAMETROS INVALIDOS']); 
            }
            $newSucursal = [
                'idempresa'=>$data['empresa'],
                'nombre'=>trim($data['nombre']),
                'telefono'=>trim($data['telefono']),
                'iddepartamento'=>$data['departamento'],
                'idmunicipio'=>$data['municipio'],
                'direccion'=>trim($data['direccion']),
                'encargado'=>trim($data['encargado'])
            ];
            $keySucursal = $this->sucursal->insert($newSucursal);
            return $this->response->setStatusCode(201,'CREATED')
            ->setJSON(['key'=>$keySucursal]);
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function update($id){
        try {
            $data = $this->request->getJSON(true);
            $campos = ['empresa','nombre','departamento','municipio','direccion','telefono','encargado'];
            $validacion = false;
            foreach($campos as $row){
                if(!isset($data[$row])){
                    $validacion = true;
                    break;
                }
            }
            if($validacion){
                return $this->response->setStatusCode(400,'Validation Error')
                ->setJSON(['message'=>'PARAMETROS INVALIDOS']); 
            }
            $sucursal = [
                'idempresa'=>$data['empresa'],
                'nombre'=>trim($data['nombre']),
                'telefono'=>trim($data['telefono']),
                'iddepartamento'=>$data['departamento'],
                'idmunicipio'=>$data['municipio'],
                'direccion'=>trim($data['direccion']),
                'direccion2'=>trim($data['direccion2']),
                'direccion3'=>trim($data['direccion3']),
                'encargado'=>trim($data['encargado'])
            ];
           
            $this->sucursal->update($id,$sucursal);
            return $this->response->setStatusCode(200,'OK')
            ->setJSON(['message'=>'Sucursal Actualizada']);
        } catch (\Throwable $th) {
           
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }

    public function delete($id){
        try {
            $existe = $this->sucursal->find($id);
            if($existe == null){
                return $this->response->setStatusCode(404,'NOT FOUND')
                ->setJSON(['message'=>'SUCURSAL NO ENCONTRADA']); 
            }
            $this->sucursal->update($id,[
                'activo'=>false
            ]);
            return $this->response->setStatusCode(200,'OK')
            ->setJSON(['message'=>'Sucursal Eliminada']);
            
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return $this->response->setStatusCode(500,'internal server')
            ->setJSON(['message'=>$th->getMessage()]);
        }
    }
   
}
