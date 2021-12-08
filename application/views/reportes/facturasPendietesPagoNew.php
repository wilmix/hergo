<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <div class="forPrint col-md-3">
          <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
          </div>
        </div>
        <div class="form-group col-sm-6 col-md-2" id="almacen" @change="onChangeAlm()">
            <select class="form-control" 
                        :disabled="disabled"
                        v-model="almacen" 
                        id="almacen" 
                        name="almacen">
                <option v-for="option in almacenes" 
                        v-bind:value="option.value"
                        v-text="option.alm">
                </option>
            </select>
        </div>
      </div>
      <div class="box-body">
        <table id="table" class="table table-hover table-striped table-sm" style="width:100%">
        </table>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div>
  <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->



