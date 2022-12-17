<?= $this->extend('base/index') ?>

<?= $this->section('body') ?>
    <div class="row">
        <div class="col-12">
            <h4>Sucursales</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12 card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <select class="form-control" id="empresa"></select>

                    </div>
                    <div class="col-6 align-items-end">
                    <Button type="button" id="btnNew" class="btn btn-success float-right" >Nuevo</Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 card shadow">
            <div class="card-body responsive-table">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre de Sucursal</th>
                            <th>Empresa</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nueva Sucursal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Nombre de Sucursal</label>
                            <input type="text" class="form-control" id="nombre"  />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Empresa</label>
                            <select class="form-control" id="empresas"></select>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Departamento</label>
                            <select class="form-control" id="departamentos"></select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Municipios</label>
                            <select class="form-control" id="municipios"></select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Direccion</label>
                            <input type="text" class="form-control" id="direccion"  />
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Encargado</label>
                            <input type="text" class="form-control" id="encargado"  />
                        </div>
                    </div>
                
                        
                    <div class="col-6">
                        <div class="form-group">
                            <label>telefono</label>
                            <input type="number" class="form-control" id="telefono"  />
                        </div>
                    </div>
                </div>
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnCreate" class="btn btn-primary">Guardar</button>
                <button type="button" id="btnUpdate" class="btn btn-primary">Actualizar</button>
            </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xs">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <h3 class="text-danger" >Advertencia!!</h3>
                        <p>Estas seguro de eliminar esta Sucursal?</p>
                    </div>
                </div>
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnDelete" class="btn btn-danger">Eliminar</button>
            </div>
            </div>
        </div>
    </div> 

<?= $this->endSection() ?>
   
<?= $this->section('js') ?>
    <script src="<?php echo base_url('dist/js/custom/sucursal/index.js')?>"></script>
<?= $this->endSection() ?>
