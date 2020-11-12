
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
            //llave:'',
            almacen:'',         
            manual:'',   
            moneda:'',
            guardar:false,
            pedido:'',
            datosFactura:[],
            glosa01:'',
            glosa02:'',
            glosa03:'',
            msjPrueba:''


        },
        
        methods:{
    
            lugarFecha: function(){
                var fechaFormato = moment(this.fecha, 'DD/MM/YYYY');
                var dia=fechaFormato.format("DD");
                var mes=fechaFormato.format("MMMM");
                var anio=fechaFormato.format("YYYY");    
                var _lugarFecha=(this.almacen.ciudad+", "+dia+" de "+mes+" de "+anio);
                return _lugarFecha;
            },
            retornarTotal: function(){
                totalfact=0;

                    this.datosFactura.forEach(function(detalle) {
                        pu = detalle.facturaPUnitario
                        totalfact+=parseFloat(detalle.facturaCantidad*pu);
                    })
                //totalfact = this.moneda == 2 ? 100 : 500
                //console.log(totalfact);
               return totalfact;
            
                
            },
            dolares:function(value){
                return value / parseFloat(this.tipocambio);
            },
           
           /* generarCodigoControl:function()
            {                      
                if(this.manual==1) return 0;    
                var autor    = this.autorizacion;
                var nFactura = this.numero;
                var idNIT    = this.ClienteNit;
                var fecha    = moment(this.fecha, 'DD-MM-YYYY');;
                var monto    = this.retornarTotal().toString();
                var llave    = this.llave;
                var nitCasa  = this.nit;  
                if (this.moneda == 2) {
                    monto =this.retornarTotal()*this.tipocambio
                    monto = monto.toString()

                }  
                                
                var dia=fecha.format("DD");
                var mes=fecha.format("MM");
                var gestion=fecha.format("YYYY");

                
                var fechaConcatenar = gestion + mes + dia;
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
            },  */   
            generarCodigoQr: function(){
                $("#qrcodeimg").html("");
              
                if(parseInt(this.manual)==0) {
                    let monto    = this.moneda == 1 ? this.retornarTotal() :  this.retornarTotal()*this.tipocambio
                    monto = monto.toFixed(2)
                    let fechaqr = moment(this.fecha, 'DD/MM/YYYY')
                    let dia=fechaqr.format("DD");
                    let mes=fechaqr.format("MM");
                    let gestion=fechaqr.format("YYYY");
                    fechaqr = dia + '/' + mes + '/' + gestion
              
                    let codigoqr = (this.nit + "|" + this.numero + "|" + this.autorizacion + "|" + fechaqr + "|" + monto+ "|" + monto +"|" + this.codigoControl +"|" + this.ClienteNit + "|0|0|0|0");
                   
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
