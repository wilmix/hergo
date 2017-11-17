
var EventBus = new Vue;    
    Vue.component('app-task',{
        data: function(){
            return{
                editing:false,
                draft:'',
            }
        },
        template:'#templateModalPrevia',
        props:['task','index'],
        created:function(){
            
            EventBus.$on('editando',function(index){
                console.log("asdasd")
                if(this.index!=index)
                    this.discard();
            }.bind(this));
        },
        methods:{
            toggleStatus:function(){
            this. task.pending=!this.task.pending
            },
            edit:function(){
                /*
                this.tasks.forEach(function(task) {
                    task.editing=false
                }, this);*/
                EventBus.$emit('editando',this.index)
                this.editing=true;
                this.draft=this.task.description;
            
            },
            update:function(){
                this.task.description=this.draft;
                this.editing=false;
            },
            discard:function(){
                this.editing=false
            },
            remove:function(){
                //vm.tasks.splice(this.index,1);
                this.$emit('removertarea',this.index);
            },
        
        }
    });
    var vmVistaPrevia = new Vue({
        el: '#app',
        data:{
            nit:'',
            numero:'',
            autorizacion:'',
            fechaLimiteEmision:'',
            codigoControl:'',
            fecha:'',
            ClienteFactura:'',
            ClienteNit:'',
            glosa:'',
            tipocambio:'',
            llave:'',
            almacen:'',         
            manual:'',   
            moneda:'',
            guardar:false,
            datosFactura:[],

        },
        
        methods:{
    
            lugarFecha: function(){
                var fechaFormato = moment(this.fecha, 'YYYY-MM-DD');
                var dia=fechaFormato.format("DD");
                var mes=fechaFormato.format("MMMM");
                var anio=fechaFormato.format("YYYY");    
                var _lugarFecha=(this.almacen.ciudad+", "+dia+" de "+mes+" de "+anio);
                return _lugarFecha;
            },
            retornarTotal: function(){
                totalfact=0;
                this.datosFactura.forEach(function(detalle) {
                    totalfact+=parseFloat(detalle.facturaCantidad*detalle.facturaPUnitario);
                })
              //  console.log(totalfact);
               return totalfact;
            
                
            },
            dolares:function(value){
                console.log((this.tipocambio))
                return value / parseFloat(this.tipocambio);
            },
           
            generarCodigoControl:function()
            {                      
                if(this.manual==1) return 0;                      
                var autor    = this.autorizacion;
                var nFactura = this.numero;
                var idNIT    = this.ClienteNit;
                var fecha    = moment(this.fecha, 'YYYY-MM-DD');;
                var monto    = this.retornarTotal().toString();
                var llave    = this.llave;
                var nitCasa  = this.nit;   
                                
                var dia=fecha.format("DD");
                var mes=fecha.format("MM");
                var gestion=fecha.format("YYYY");           

                var fechaConcatenar = gestion + mes + dia;
                
                console.log(autor,
                            nFactura,
                            idNIT,
                            fechaConcatenar,
                            monto,
                            llave);

                codigo = generateControlCode(
                            autor,
                            nFactura,
                            idNIT,
                            fechaConcatenar,
                            monto,
                            llave
                        );
               
                this.codigoControl=codigo;          
                return codigo      
            },     
            generarCodigoQr: function(){
                $("#qrcodeimg").html("");
                console.log(this.manual)
                if(parseInt(this.manual)==0) {
                    
                    var fecha    = moment(this.fecha, 'YYYY-MM-DD').format('DD/MM/YYYY');
                    var monto    = this.retornarTotal().toString();
                    console.log(monto);
                    var codigoqr = (this.nit + "|" + this.numero + "|" + this.autorizacion + "|" +fecha + "|" + monto+ "|" + monto +"|" + this.codigoControl +"|" + this.ClienteNit + "|0|0|0|0");
                    
                    console.log(codigoqr)
                    
                    generarQr("qrcodeimg",codigoqr)
                }
                
            },
           
        },
        filters:{
            literal:function(value){
                return NumeroALetras(value)
            },
            moneda:function(value){
                return numeral(value).format('0,0.00');
            },
            fechaCorta:function(value){
                return formato_fecha_corta(value);
            },                        
        },        
        created: function(){
            
            this.$http.post(base_url('index.php/Facturas/datosAlmacen'))
                .then(function(response){                    
                    this.almacen = response.body;
                }, function(){
                    alert('Error!');
            });
        }
    });
