<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <div class="col-md-5">
            <v-select :options="almacenes" v-model="almacen"/>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-primary" @click="getData">Get</button>
        </div>
      </div>
      <div class="box-body">
        <table id="table" class="table table-hover table-striped table-sm" style="width:100%">
        </table>
        <div class="col-md-12">
          <button type="button" class="btn btn-primary btn-block" @click="sincronizar">Sincronizar</button>
        </div>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div>
   <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->
