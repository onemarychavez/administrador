<?= $this->extend('base/index') ?>

<?= $this->section('body') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                <div class="col-sm-12">
                <h1 class="m-0">Usuarios</h1>
                </div><!-- /.col -->
                    <div class="col-12">
                    <button type="button" class="btn btn-success" onClick="Nuevo()" > Nuevo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 ">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>NOMBRE</th>
                        <th>USUARIO</th>
                        <th>ROL</th>
                        <th>OPCIONES</th>
                    </tr>
                    </thead>
                    <tbody id="tabla"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detalle de Usuarios</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="">Nombres</label>
                        <input type="text" id="nombres" class="form-control"/>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-12">
                <div class="form-group">
                        <label for="">Apellidos</label>
                        <input type="text" id="apellidos" class="form-control"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="">Usuario</label>
                        <input type="text" id="usuario" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="">Clave</label>
                        <input type="password" id="clave" class="form-control"/>
                    </div>
                </div>
                
                <div class="col-lg-6 col-xs-12">
                <div class="form-group">
                        <label for="">Tipo de Rol</label>
                        <select id="rol" class="form-control" ></select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" id="btncreate" onclick="create()" class="btn btn-primary">guardar</button>
            <button type="button" id="btnupdate" onclick="update()" class="btn btn-warning">Save changes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
   
<?= $this->section('js') ?>
    <script src="<?php echo base_url('dist/js/custom/usuario/index.js?v1')?>"></script>
<?= $this->endSection() ?>
