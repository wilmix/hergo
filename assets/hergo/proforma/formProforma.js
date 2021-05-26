
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
      /* datos */
        almacen:document.getElementById("idAlmacenUsuario").value,
        almacenes: [
            { alm: 'CENTRAL HERGO', value: '1' },
            { alm: 'DEPOSITO EL ALTO', value: '2' },
            { alm: 'POTOSI', value: '3' },
            { alm: 'SANTA CRUZ', value: '4' },
        ],
        es: vdp_translation_es.js,
        articulosList: [],
        clienteList:[],
        monedas:[
            { moneda: 'BOLIVIANOS', value: '1' },
            { moneda: 'DOLARES', value: '2' },
        ],
        tipoCambio: parseFloat(document.getElementById("mostrarTipoCambio").textContent),
        /* articulo */
        selectedArticulo:null,
        idCodigo:'',
        codigo:'',
        marca:'',
        linea:'',
        descripcion:'',
        unidad:'',
        saldo:'0.00',
        precio:'0.00',
        precioDol:'0.00',
        img:'',
        url_img:base_url('assets/img_articulos/hergo.jpg'),
        cantidad:0.00,
        precioLista:0.00,
        saldo:0,
        /* documento */
        id:'',
        moneda:1,
        validez:'',
        lugarEntrega:'',
        title:'ProForma',
        n:0,
        fecha: moment().format('MM-DD-YYYY'),
        formaPago:null,
        pedidoPor:'',
        condicionPago:'',
        cliente:'',
        glosa:'',
        porcentajeDescuento:0,
        descuento:0,
        totalFin:0,
        totalDoc:0,
        items:[],
        btnGuardar:'Guardar'
       
    },
  created: function () {
    let id = document.getElementById("idPedido").value
    if (id) {
      console.log(id);
      this.editPedido(id)
    }
  },
    methods: {
      store(e){
        agregarcargando()
        e.preventDefault()
        if (!this.cliente || !this.items.length>0) {
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
        form.append('fecha', moment(this.fecha).format('YYYY-MM-DD'))
        form.append('alm', this.almacen)
        form.append('cliente', this.cliente.id)
        form.append('moneda', this.moneda)
        form.append('condicionPago', this.condicionPago)
        form.append('porcentajeDescuento', this.porcentajeDescuento)
        form.append('validez', this.validez)
        form.append('lugarEntrega', this.lugarEntrega)
        form.append('glosa', this.glosa)
        form.append('items', JSON.stringify(this.items))
        quitarcargando()
        for(let pair of form.entries()) { console.log(pair[0]+ ', '+ pair[1]); } return 
      },
      addDetalle(){
        if (this.saldo <= 0) {
          console.log('no hay stock');
        }
        if (this.selectedArticulo && this.cantidad > 0 && this.precioLista > 0) {
          this.selectedArticulo.cantidad = this.cantidad
          this.selectedArticulo.precioLista = this.precioLista
          this.selectedArticulo.total = this.cantidad * this.precioLista
          this.selectedArticulo.precioDOL = this.precioDol
          this.selectedArticulo.url_img = this.url_img
          this.selectedArticulo.marca = this.marca
          this.selectedArticulo.descrip = this.descripcion
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
          this.totalDoc = this.items.map((item, index, array) => parseFloat(item.total)).reduce( (a,b)=> a+b) 
          this.descuento = this.totalDoc * this.porcentajeDescuento /100
          this.totalFin = this.totalDoc - this.descuento
          //return this.totalFin 
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
          $.ajax({
              type:"POST",
              url: base_url('index.php/Ingresos/searchItem'),
              dataType: "json",
              data: {
                  item:search,
                  alm:document.getElementById("almacen").value
              },
          }).done(function(res){
              vm.articulosList = res
          })
          loading(false);
      }, 350),
      addCardInfo(selected){
        this.idCodigo = selected.id
        this.codigo = selected.codigo
        this.descripcion = selected.descrip
        this.unidad = selected.uni
        this.marca = selected.marca
        this.saldo = selected.saldo
        this.precio = selected.precio
        this.saldo = selected.saldo
        this.precioDol = selected.precioDol
        this.precioLista = this.moneda == 1 ? selected.precio : selected.precioDol
        this.url_img = selected.img ?  base_url('assets/img_articulos/'+selected.img) :base_url('assets/img_articulos/hergo.jpg')
      },
      cleanCard(){
        this.selectedArticulo = null
        this.idCodigo = ''
        this.codigo = ''
        this.descripcion = ''
        this.unidad = ''
        this.saldo = ''
        this.precio = ''
        this.img = ''
        this.url_img = url_img
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
        this.items = []
      },
      deleteRow:function(item){
        this.items.splice(item,1);
        this.total()
      },
      onSearchCliente(search, loading) {
        loading(true)
        this.searchCliente(loading, search, this)
        },
      searchCliente: _.debounce((loading, search, vm) => {
            $.ajax({
                type:"POST",
                url: base_url('index.php/Ingresos/searchCliente'),
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

