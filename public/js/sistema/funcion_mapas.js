//VARIABLES GENERALES
        //declaras fuera del ready de jquery
    var nuevos_marcadores = [];
    var marcadores_bd= [];
    var mapa = null; //VARIABLE GENERAL PARA EL MAPA
    //FUNCION PARA QUITAR MARCADORES DE MAPA
    function limpiar_marcadores(lista)
    {
        for(i in lista)
        {
            //QUITAR MARCADOR DEL MAPA
            lista[i].setMap(null);
        }
    }
    $(document).on("ready", function(){
        //alert('hola');
        //VARIABLE DE comercial
        var comercial = $("#comercial");
        
        var punto = new google.maps.LatLng(2.4418778671341825,-76.60649389028549);
        var config = {
            zoom:14,
            center:punto,
            mapTypeId: google.maps.MapTypeId.HYBRID
        };
        mapa = new google.maps.Map( $("#mapa")[0], config );
        //console.log('hola');
        google.maps.event.addListener(mapa, "click", function(event){
           var coordenadas = event.latLng.toString();
           
           coordenadas = coordenadas.replace("(", "");
           coordenadas = coordenadas.replace(")", "");



           var lista = coordenadas.split(",");
           
           var direccion = new google.maps.LatLng(lista[0], lista[1]);
           //PASAR LA INFORMACI�N AL comercial
          
          comercial.find("input[name = 'latitud']").val(lista[0]);
          comercial.find("input[name= 'longitud']").val(lista[1]);
          
           
           var marcador = new google.maps.Marker({
               
               position:direccion,
               map: mapa, 
               animation:google.maps.Animation.DROP,
               draggable:false
           });

            google.maps.event.addListener(marcador, 'click', function(){
                
                console.log("sss");
            });
   
           nuevos_marcadores.push(marcador);
           
           google.maps.event.addListener(marcador, "click", function(){

           });

           
           //BORRAR MARCADORES NUEVOS
           limpiar_marcadores(nuevos_marcadores);
           marcador.setMap(mapa);
        });

      
        
        //CARGAR PUNTOS AL TERMINAR DE CARGAR LA P�GINA
        verMapa();//FUNCIONA, AHORA A GRAFICAR LOS PUNTOS EN EL MAPA
    });
    //FUERA DE READY DE JQUERY
    //FUNCTION PARA RECUPERAR PUNTOS DE LA BD
    function verMapa()
    {
        //ANTES DE verMapa MARCADORES
        //SE DEBEN QUITAR LOS ANTERIORES DEL MAPA
       limpiar_marcadores(marcadores_bd);
       var comercial_edicion = $("#frmEliminar");
       $.ajax({
               type:"POST",
               url:"/josandro/modulos/cliente/modelo.php",
               dataType:"JSON",
               data:"&tipo=FUNCIONMAPA",
               success:function(data){
                   if(data.mensaje=="OK")
                    {
                        //alert("Hay puntos en la BD");
                        $.each(data.mensaje, function(i, item){
                            //OBTENER LAS COORDENADAS DEL PUNTO
                            var posi = new google.maps.LatLng(item.cx, item.cy);//bien
                            //CARGAR LAS PROPIEDADES AL MARCADOR
                            var marca = new google.maps.Marker({
                                idCliente:item.idCliente,
                                position:posi,
                                centro_poblado: item.centroPoblado,
                                latitud:item.latitud,
                                longitud:item.longitud
                            });
                            //AGREGAR EVENTO CLICK AL MARCADOR
                            //MARCADORES QUE VIENEN DE LA BASE DE DATOS
                            google.maps.event.addListener(marca, "click", function(){

                                contenido= "<b>Zona: </b>"+item.centroPoblado+"<br><b>Latitud: </b>"+item.latitud+"<br><b>Longitud: </b>"+item.longitud;

                                var infowindow = new google.maps.InfoWindow({
                                    
                                    content : contenido
                                    
                                });
                                infowindow.open(mapa, marca);

                               //AHORA PASAR LA INFORMACIÓN DEL MARCADOR
                               //AL FORMUALARIO
                                   
                                    
                                    
                                    comercial_edicion.find("input[name = 'latitud']").val(marca.latitud).focus();
                                    comercial_edicion.find("input[name= 'longitud']").val(marca.longitud).focus();
                                    
                               
                               
                            });

                                
                            //AGREGAR EL MARCADOR A LA VARIABLE MARCADORES_BD
                            marcadores_bd.push(marca);
                            //UBICAR EL MARCADOR EN EL MAPA
                            marca.setMap(mapa);
                        });
                    }
                else
                    {
                        alert("NO hay puntos en la BD");
                    }
               },
               beforeSend:function(){
                   
               },
               complete:function(){
                   
               }
           });



    }
    //PLANTILLA AJAX