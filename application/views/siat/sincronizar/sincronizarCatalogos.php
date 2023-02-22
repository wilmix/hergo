<!-- Your Page Content Here -->
<div class="row" id="app">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">

      </div>
      <div class="box-body">
          <h3>Ultima Sincronización => {{ ultima }}</h3>
          <ol>
            <li v-for="(value, key, index) in catalogos">
              {{ key }}::: {{ value ? 'CORRECTO' : 'ERROR' }}
            </li>
          </ol>
        </table>
        <div class="col-md-12">
          <button type="button" class="btn btn-primary btn-block hidden" @click="sincronizarManual">Sincronizar Manualmente</button>
        </div>
      </div> <!-- /.box-body -->
    </div> <!-- /.class="box" -->
  </div>
   <!-- /.class="col-xs-12" -->
</div> <!-- /.class="row" -->



