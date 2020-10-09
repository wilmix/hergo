<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div id="ordenForm" class="box">
    <div class="box-header with-border">
      <div class="forPrint col-md-3">
      <input type="text" value="<?= $editarBack ?>" id="editBack" hidden>
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
          <i class="fa fa-calendar"></i>&nbsp;
          <span></span> <i class="fa fa-caret-down"></i>
        </div>
      </div>
      <select class="btn btn-primary btn-sm" id="filter" name="filter">
        <option value="pendientes">EN TRANSITO</option>
        <option value="ingresados">INGRESADOS</option>
        <option value="todos">TODOS</option>
      </select>
    </div>
      <div class="box-body">
        <table id="table" class="table table-hover display compact" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div> <!-- /.class="col-xs-12" -->
  <!-- use the modal component, pass in the prop -->
  <modal v-if="showModal" @close="showModal = false">
  </modal>
</div> <!-- /.class="row" -->


<!-- template for the modal component -->
<script type="text/x-template" id="modal-template">
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <slot name="header">
              <h3>Estado</h3>
            </slot>
          </div>

          <div class="modal-body">
            <slot name="body">
              <form method="post"  id="form">
                <div class="table-responsive row">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Proveedor</th>
                            <th>Còdigo</th>
                            <th>Descripciòn</th>
                            <th>Estimada</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ pedido }}</td>
                            <td>{{ proveedor }}</td>
                            <td>{{ codigo }}</td>
                            <td>{{ descripcion }}</td>
                            <td>{{ estimada }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3 col-md-3">
                        <label>Fecha Recepcion: </label>
                        <vuejs-datepicker  v-model="recepcion" :language="es" :format="customFormatter" input-class="form-control">
                        </vuejs-datepicker>
                    </div>
                    <div class="form-group col-sm-3 col-md-3">
                        <label>Embarque: </label>
                        <input type="text" name="embarque" class="form-control" v-model="embarque">
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <label >Status:</label>
                        <select class="form-control" v-model="status" name="status">
                            <option value="0">EN TRANSITO</option>
                            <option value="1">INGRESADO</option>
                        </select>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <label >Nivel:</label>
                        <select class="form-control" v-model="pedidoItem" name="pedidoItem">
                            <option value="pedido">PEDIDO</option>
                            <option value="item">ITEM</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12">
                        <label>Estado: </label>
                        <input type="text" class="form-control"  v-model="estado" name="estado">
                    </div>
                </div>
              </form>
                <div class="row">
                  <div class="alert alert-danger text-center" role="alert" v-if="errors">
                  {{ errors }}
                  </div>
                </div>
            </slot>
          </div>

          <div class="modal-footer text-center">
            <slot name="footer"  class="col-xs-12 ">
            <button type="button" class="btn btn-success" @click="saveStatus">Guardar</button>
            <button type="button" class="btn btn-default modal-default-button" @click="$emit('close')">Cerrar</button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</script>