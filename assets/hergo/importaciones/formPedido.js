
let url_img = base_url('assets/img_articulos/hergo.jpg')
$(document).ready(function(){

})

Vue.component("v-select", VueSelect.VueSelect);

const app = new Vue({
    el: '#app',
    components: {
      vuejsDatepicker
  },
    data: {
        es: vdp_translation_es.js,
        articulosList: [],
        proveList:[],
        formaPagoList:[{label: 'EFECTIVO', id: '0'},{label: 'CRÉDITO', id: '1'}],
        /* articulo */
        selectedArticulo:null,
        idCodigo:'',
        codigo:'',
        numParte:'',
        descripcion:'',
        descripFabrica:'',
        posicionArancel:'',
        unidad:'',
        saldo:'0.00',
        precio:'0.00',
        rotacion:'0.00',
        cpp:'0.00',
        img:'',
        url_img:base_url('assets/img_articulos/hergo.jpg'),
        cantidad:0.00,
        precioFabrica:0.00,
        /* documento */
        fecha: moment().format('MM-DD-YYYY'),
        formaPago:null,
        selectedProv:null,
        idProveedor:'',
        pedidoPor:'',
        cotizacion:'',
        recepcion:'',
        cliente:'',
        glosa:'',
        totalDoc:0,
        tipoCambio: 0,
        items:[],
       
    },
  created: function () {
    let tc = parseFloat(document.getElementById("mostrarTipoCambio").textContent)
    this.tipoCambio = tc
  },
    methods: {
      addDetalle(){
        if (this.selectedArticulo && this.cantidad > 0 && this.precioFabrica > 0) {
          this.selectedArticulo.cantidad = this.cantidad
          this.selectedArticulo.precioFabrica = this.precioFabrica
          this.selectedArticulo.total = this.cantidad * this.precioFabrica
          this.items.push(this.selectedArticulo)
          app.cleanCard()
          app.total() 
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
          this.totalDoc = this.items.map((item, index, array) => item.total).reduce( (a,b)=> a+b)
          return this.totalDoc
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
          if (this.selectedArticulo) {
            app.addCardInfo(this.selectedArticulo)
          }
      },
      search: _.debounce((loading, search, vm) => {
          fin = moment(vm.fecha).format('YYYY-MM-DD')
          $.ajax({
              type:"POST",
              url: base_url('index.php/Ingresos/searchArticulo'),
              dataType: "json",
              data: {
                  articulo:search,
                  fin:fin
              },
          }).done(function(res){
              vm.articulosList = res
          })
          loading(false);
      }, 100),
      addCardInfo(selected){
        this.idCodigo = selected.id
        this.codigo = selected.codigo
        this.numParte = selected.numParte
        this.descripcion = selected.descripcion
        this.descripFabrica = selected.descripFabrica
        this.unidad = selected.unidad
        this.saldo = selected.saldo
        this.precio = selected.precio
        this.cpp = selected.cpp
        this.img = selected.img
        this.rotacion = selected.rotacion
        this.posicionArancel = selected.posicionArancel
        this.url_img = this.img ? base_url('assets/img_articulos/'+this.img) : url_img
      },
      cleanCard(){
        this.selectedArticulo = null
        this.idCodigo = ''
        this.codigo = ''
        this.numParte = ''
        this.descripcion = ''
        this.descripFabrica = ''
        this.unidad = ''
        this.saldo = ''
        this.precio = ''
        this.cpp = ''
        this.img = ''
        this.rotacion = ''
        this.posicionArancel = ''
        this.url_img = url_img
        this.cantidad = ''
        this.precioFabrica= ''
        this.articulosList =[]
      },
      cleanForm(){
        this.fecha = moment().format('MM-DD-YYYY')
        this.recepcion = ''
        this.selectedProv = null
        this.pedidoPor =''
        this.cotizacion = ''
        this.formaPago = null
        this.glosa = ''
      },
      deleteRow:function(item){
        this.items.splice(item,1);
        this.total()
      },
      onSearchProveedor(search, loading) {
          loading(true)
          this.searchProveedor(loading, search, this)
      },
      searchProveedor: _.debounce((loading, search, vm) => {
          $.ajax({
              type:"POST",
              url: base_url('index.php/Ingresos/searchProveedor'),
              dataType: "json",
              data: {
                search:search,
            },
          }).done(function(res){
              vm.proveList = res
          })
          loading(false);
      }, 100),
      store(e){
        agregarcargando()
        e.preventDefault()
        if (!this.selectedProv || !this.formaPago || !this.items.length>0) {
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
        form.append('items', JSON.stringify(this.items))
        form.append('fecha', moment(this.fecha).format('YYYY-MM-DD'))
        form.append('recepcion', moment(this.recepcion).format('YYYY-MM-DD'))
        form.append('proveedor', this.selectedProv.id)
        form.append('pedidoPor', this.pedidoPor)
        form.append('cotizacion', this.cotizacion)
        form.append('formaPago', this.formaPago.id)
        form.append('glosa', this.glosa)
        /* for(let pair of form.entries()) { console.log(pair[0]+ ', '+ pair[1]); } */
        $.ajax({
          url: base_url('index.php/importaciones/pedidos/store'),
          type: "post",      
          data: form,                                    
          processData: false,
          contentType: false,
          cache:false, 
        }).done(function(res){
          res = JSON.parse(res)
          if (res.status) {
            console.log('ok');
            quitarcargando()
            swal({
              title: "Guardado!",
              text: "El pedido se guardó con éxito",
              type: "success",        
              allowOutsideClick: false,                                                                        
              }).then(function(){
                agregarcargando()
                location.reload()
              })
          } else {
            quitarcargando()
            swal({
              title: 'Error',
              text: "Error al guardar la solicitud, verifique el tipo de cambio para la fecha.",
              type: 'error', 
              showCancelButton: false,
            })
            return
          }
          
        }) 

      }
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

