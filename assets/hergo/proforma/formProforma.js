
let url_img = base_url('assets/img_articulos/hergo.jpg')
$(document).ready(function(){
  
  
})
Vue.component("v-select", VueSelect.VueSelect);
Vue.component("card-product",{
  template:`<div class="card mb-12" style="background: #CEE6F5;border: #B1D6ED 2px solid;border-radius: 10px;">
              <div class="row no-gutters">
                <div class="col-md-2 col-sm-3 col-lg-2">
                  <img :src="url_art()" class="card-img img-responsive center-block" width="150" height="150" style="background: #CEE6F5;border-radius: 10px;" >
                </div>
                <div class="col-md-6 col-sm-5 col-lg-7">
                  <div class="card-body" v-if="selectedart">
                    <blockquote>
                    <h4> <b>{{selectedart.codigo}}</b> </h4>
                      <p class="card-text" v-html="selectedart.descrip + ' - ' + selectedart.uni
                                                  + '<br> <b>' +'MARCA: ' + '</b>' + selectedart.marca
                                                  + '<br> <b>' +'LINEA: ' + '</b>' + selectedart.linea"> 
                      </p>
                    </blockquote>
                  </div>
                </div>
                <div class="col-md-4 col-sm-4 col-lg-3">
                  <div class="card-body" v-if="selectedart">
                    <blockquote>
                      <p class="card-text text-center"> 
                        <span class="font-weight-bold">Precio BOB:</span>  {{(selectedart.precio) | moneda }} <br>
                        <span class="font-weight-bold">Precio $u$:</span>  {{(selectedart.precioDol) | moneda }} <br>
                        <span class="font-weight-bold">Saldo:</span>  {{(selectedart.saldo) | moneda }} <br>
                    </p>
                    </blockquote>
                  </div>
                </div>
                </div>
            </div>`,
  props:{
    url_img : String,
    selectedart: Object
  },
  methods:{
    url_art(){
      if (this.selectedart) {
        url = this.selectedart.img ?  base_url('assets/img_articulos/'+this.selectedart.img) : base_url('assets/img_articulos/hergo.jpg')
        this.selectedart.url_img = url
      } else {
        url = base_url('assets/img_articulos/hergo.jpg')
      }
      return url
    },

  },
  filters:{
    moneda:function(value){
        num=Math.round(value * 100) / 100
        num=num.toFixed(2);
        //return(num);
        return numeral(num).format('0,0.00');            
    },                 
  },  
})

const app = new Vue({
    el: '#app',
    components: {
      vuejsDatepicker
  },
    data: {
      /* datos */
        articulosArray:[],
        articulosArraySelected:null,
        almacen:document.getElementById("idAlmacenUsuario").value,
        disabled: document.getElementById("nacional").value == '' ? true : false,
        almacenes: [],
        tipo:1,
        tipos: [],
        es: vdp_translation_es.js,
        articulosList: [],
        clienteList:[],
        monedas:[],
        edit:false,
        tipoCambio: parseFloat(document.getElementById("mostrarTipoCambio").textContent),
        /* articulo */
        selectedart:null,
        cantidad:0.00,
        precioLista:0.00,
        tiempoEntrega:'',
        industria: '',
        marca:'',
        /* documento */
        id:0,
        btnGuardar:'Guardar',
        moneda:1,
        validez:'',
        lugarEntrega:'',
        garantia:'',
        tiempoEntregaC:'',
        title:'Crear Proforma',
        n:0,
        fecha: moment().format('MM-DD-YYYY'),
        formaPago:null,
        pedidoPor:'',
        condicionPago:'',
        cliente:'',
        clienteDato:'',
        complemento:'',
        glosa:'La disponibilidad de inventario está sujeta a cambios sin previo aviso.',
        porcentajeDescuento:0,
        descuento:0,
        totalFin:0,
        totalDoc:0,
        items:[],
        btnGuardar:'Guardar'
       
    },
  created: function () {
    agregarcargando()
    this.getTipos()
    let id = document.getElementById("idProforma").value
    if (id>0) {
      console.log(id);
      this.editProforma(id)
    }
  },
    methods: {
      store(e){
        agregarcargando()
        this.edit = false
        this.total()
       
        e.preventDefault()
        if (!this.clienteDato || !this.items.length>0) {
          quitarcargando()
          swal({
            title: 'Error',
            text: "Por favor llene correctamente el formulario",
            type: 'error', 
            showCancelButton: false,
          })
          return
        }
        let form = new FormData();
        form.append('id', this.id)
        form.append('fecha', moment(this.fecha).format('YYYY-MM-DD'))
        form.append('almacen', this.almacen)
        form.append('clienteDato', this.clienteDato)
        form.append('complemento', this.complemento)
        form.append('n', this.n)
        form.append('moneda', this.moneda)
        form.append('condicionesPago', this.condicionPago)
        form.append('porcentajeDescuento', this.porcentajeDescuento)
        form.append('descuento', this.descuento)
        form.append('totalFin', this.totalFin)
        form.append('validez', this.validez)
        form.append('tipo', this.tipo)
        form.append('lugarEntrega', this.lugarEntrega)
        form.append('glosa', this.glosa)
        form.append('tiempoEntregaC', this.tiempoEntregaC)
        form.append('garantia', this.garantia)
        form.append('items', JSON.stringify(this.items))
        /* for(let pair of form.entries()) { console.log(pair[0]+ ', '+ pair[1]); };  quitarcargando(); return; */
        $.ajax({
          url: base_url('index.php/Proforma/store'),
          type: "post",      
          data: form,                                    
          processData: false,
          contentType: false,
          cache:false, 
        }).done(function(res){
          res = JSON.parse(res)
          if (res.status == true) {
            quitarcargando()
            if (app.id) {
              swal({
                title: "Editado!",
                text: "La proforma se modificó con éxito",
                type: "success",        
                allowOutsideClick: false,                                                                        
                }).then(function(){
                  agregarcargando()
                  let print = base_url("pdf/Proforma/generar/") + res.id;
                  window.open(print);
                  window.location.href=base_url("index.php/Proforma");
                })
            } else {
              swal({
                title: "Guardado!",
                text: "La proforma se guardó con éxito",
                type: "success",        
                allowOutsideClick: false,                                                                        
                }).then(function(){
                  agregarcargando()
                  let print = base_url("pdf/Proforma/generar/") + res.id;
                  window.open(print);
                  window.location.href=base_url("index.php/Proforma");
                })
            }
          } else {
            quitarcargando()
            swal({
              title: 'Error',
              text: "Error al guardar la proforma, verifique los datos.",
              type: 'error', 
              showCancelButton: false,
            })
            return
          }
          
        }) 

      },
      getTipos(){
        agregarcargando()
        $.ajax({
          url: base_url('index.php/Proforma/getInfoProformaForm'),
          type: "post",      
        }).done(function(res){
          info = JSON.parse(res)
          app.tipos = info.tipos
          app.almacenes = info.almacenes
          app.monedas = info.monedas
          app.articulosArray = info.articulos 
          quitarcargando()
        }) 
      },
      addDetalle(){
        if (this.saldo <= 0) {
          console.log('no hay stock');
        }
        //if (this.selectedart && this.cantidad > 0 && this.precioLista > 0) {
        if (this.selectedart && this.cantidad >= 0 && this.precioLista >= 0) {

          this.selectedart.cantidad = this.cantidad
          this.selectedart.marcaSigla = this.marca
          this.selectedart.tiempoEntrega = this.tiempoEntrega
          this.selectedart.industria = this.industria
          this.selectedart.precioLista = this.precioLista
          this.items.push(this.selectedart)
          app.cleanCard()
          app.total() 
          const codigo = document.getElementsByClassName('vs__search');
          codigo[0].focus()
        }
        else{
          swal({
            title: 'Error',
            text: "Seleccione artículo, cantidad y precio correctos",
            type: 'error', 
            showCancelButton: false,
          })
        }
      },
      total(){
        if (this.items.length>0) {
          this.items.map(function (item,index,array) {
            item.total = item.cantidad * item.precioLista
          })
          this.totalDoc = this.items.map((item, index, array) => parseFloat(item.total)).reduce( (a,b)=> a+b) 
          this.descuento = this.totalDoc * this.porcentajeDescuento /100
          this.totalFin = this.totalDoc - this.descuento
        }
      },
      customFormatter(date) {
        return moment(date).format('D MMMM  YYYY');
      },
      onSearch(search, loading) {
          if (search.length > 1) {
            loading(true)
            this.search(loading, search, this)
          }
          if (this.selectedart) {
            this.precioLista = this.moneda == 1 ? this.selectedart.precio : this.selectedart.precioDol
          }
      },
      search: _.debounce((loading, search, vm) => {
          $.ajax({
              type:"POST",
              url: base_url('index.php/Proforma/searchItem'),
              dataType: "json",
              data: {
                  item:search,
                  alm:document.getElementById("almacen").value
              },
          }).done(function(res){
              vm.articulosList = res
          })
          loading(false);
      }, 350
      ),
      cleanCard(){
        this.selectedart = null
        this.cantidad = ''
        this.precioLista= 0.00
        this.articulosList =[]
      },
      cleanForm(){
        this.fecha = moment().format('MM-DD-YYYY')
        this.almacen = document.getElementById("idAlmacenUsuario").value
        this.cliente = null
        this.moneda ='1'
        this.condicionPago = ''
        this.porcentajeDescuento = 0
        this.validez = ''
        this.lugarEntrega = ''
        this.glosa = ''
        this.industria = ''
        this.tiempoEntrega = ''
        this.items = []
        this.selectedart = null
      },
      deleteRow:function(item){
        this.items.splice(item,1);
        this.total()
      },
      editRow(){
        if (this.edit === true) {
          this.total()
          this.edit = false
        } else {
          this.edit = true
        }

      },
      onSearchCliente(search, loading) {
        loading(true)
        this.searchCliente(loading, search, this)
      },
      searchCliente: _.debounce((loading, search, vm) => {
            $.ajax({
                type:"POST",
                url: base_url('index.php/Proforma/searchCliente'),
                dataType: "json",
                data: {
                search:search,
            },
            }).done(function(res){
                app.clienteList = res
            })
            loading(false);
        }, 350),
        cancel(e){
        e.preventDefault()
      },
      editProforma(id){
        agregarcargando()
        this.id = id
        this.btnGuardar = 'Editar'
        
        $.ajax({
          type: "POST",
          url: base_url('index.php/Proforma/getProforma'),
          dataType: "json",
          data: {
                  id:id,
                },
        }).done(function (res) {
          console.log(res);
          agregarcargando()
          items = res.items
          items.forEach(e => {
            if (e.img == '' || e.img == null) {
              e.url_img = url_img
            } else {
              e.url_img = base_url('assets/img_articulos/'+ e.img)
            }
          });
          app.n = res.proforma.num
          app.title = `Editar Proforma ${app.n}`
          app.fecha = moment(res.proforma.fecha).format('MM-DD-YYYY')
          app.almacen = res.proforma.idAlm
          app.clienteDato = res.proforma.clienteDatos
          app.complemento = res.proforma.complemento
          app.cliente = {
            id: res.proforma.idCliente,
            label: res.proforma.clienteNombre

          }
          app.moneda = res.proforma.idMoneda
          app.condicionPago = res.proforma.condicionesPago
          app.validez = res.proforma.validezOferta
          app.lugarEntrega = res.proforma.lugarEntrega
          app.porcentajeDescuento = res.proforma.porcentajeDescuento
          app.tipo = res.proforma.idTipo
          app.garantia = res.proforma.garantia
          app.tiempoEntregaC = res.proforma.tiempoEntregaC
          app.items = res.items
          app.descuento = res.proforma.descuento
          let regex = new RegExp('<br />', 'g')
          glosa = res.proforma.glosa.toString()
          glosa = glosa.replace(regex,'')
          app.glosa = glosa
          app.total()
          quitarcargando()
        })
      },
    },
    filters:{
      moneda:function(value){
          num=Math.round(value * 100) / 100
          num=num.toFixed(2);
          //return(num);
          return numeral(num).format('0,0.00');            
      },                 
    },  
    watch:{
      articulosArraySelected: function (newArt, oldArt) {
          if (newArt) {
            $.ajax({
              type:"POST",
              url: base_url('index.php/Proforma/getArticulo'),
              dataType: "json",
              data: {
                  id:newArt.value,
                  alm:document.getElementById("almacen").value
              },
            }).done(function(res){
                app.selectedart = res
                app.precioLista = app.moneda == 1 ? app.selectedart.precio : app.selectedart.precioDol
                app.marca = app.selectedart.marcaSigla
                app.articulosArraySelected = null

            })
          }
      }
    }

})

