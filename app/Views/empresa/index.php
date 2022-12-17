<?= $this->extend('base/index') ?>

<?= $this->section('body') ?>
    <div class="row">
        <div class="col-12">
            <h4>Empresa</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12 card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <!-- <input type="text" id="filtro" className="form-control" placeholder="Buscar" />
                        <Button type="button" class="btn btn-info" >Buscar</Button> -->

                    </div>
                    <div class="col-6 align-items-end">
                    <?php

                        $session = \Config\Services::session(); ?>
                        <?php if(intval($session->get('idempresa')) == 0){echo '<Button type="button" id="btnNew" class="btn btn-success float-right" >Nuevo</Button>'; }?>
                            
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
                            <th>Nombre de Empresa</th>
                            <th>N° de Registro</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Nueva Empresa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Nombre de Empresa</label>
                            <input type="text" class="form-control" id="nombre"  />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Representante</label>
                            <input type="text" class="form-control" id="representante"  />
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Razon Social</label>
                            <input type="text" class="form-control" id="razon"  />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Giro</label>
                            <input type="text" class="form-control" id="giro"  />
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>nrc</label>
                                <input type="number" class="form-control" id="nrc"  />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>telefono</label>
                                <input type="number" class="form-control" id="telefono"  />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Logo</label>
                                <img  class="img-thumbnail" alt="" src="" id="logo"  >
                            </div>
                            <div class="form-group">
                                <input type="file" class="form-control-file" id="logobtn" accept="image/png, .jpeg, .jpg" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Categorias</label>
                                <select id="categoria"  class="form-control" multiple></select>
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
                        <p>Estas seguro de elimnar esta empresa?, se eliminaran tambien las sucursales de esta</p>
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
    <script src="<?php echo base_url('dist/js/custom/empresas/index.js')?>"></script>
<?= $this->endSection() ?>
