<link rel="stylesheet" type="text/css" href="../../../public/css/loader.css">
<!-- DataTables -->
<link rel="stylesheet" href="../lte/plugins/datatables/dataTables.bootstrap.css">
<style type="text/css">
 	.no-sort::after { display: none!important; }
	.no-sort { pointer-events: none!important; cursor: default!important; }
</style>

<script>
function guardaEstado(status, id){
	//alert(status + " _ " + id);

	$.ajax({
                /*data:  $('#formp').serialize(),*/
				data:  'estado='+status+'&id='+id,
                url:   '{$_layoutParams.root}usuarios/index/guardarStatus/',
                type:  'post',
				/*scriptCharset: "ISO-8859-1",*/
				scriptCharset:"utf-8",
                beforeSend: function () {
                	$("#mensaje").html('Guardando...');
                },
                success:  function (response) {
                   // $("#mensaje").html('');
					//$("#idIdioma").val(response);
					if(!isNaN(response)){
						/*if(status == true){
						    $("#btnEdit_"+id).removeClass("disabled");
							$("#btnOpciones_"+id).removeClass("disabled")
						}
						else{
							$("#btnEdit_"+id).addClass("disabled");
							$("#btnOpciones_"+id).addClass("disabled");
						}*/
					}else{
						//$("#mensaje").html("Error: "+response);
						BootstrapDialog.show({
							title: 'Mensaje de salida',
							message: "Error: "+response,
							buttons: [{
								id: 'btn-ok',
								//icon: 'glyphicon glyphicon-check',
								label: 'OK',
								cssClass: 'btn-primary',
								autospin: false,
								action: function(dialogRef)
								{
									dialogRef.close();
								}
							}]
						});
						
					}
                }
        });
		
}
/*function guardaStatus(status, id){
	$.ajax({
                //data:  $('#formp').serialize(),
				data:  'estado='+status+'&id='+id,
                url:   '{$_layoutParams.root}usuarios/index/guardarStatus/',
                type:  'post',
				//scriptCharset: "ISO-8859-1",
				scriptCharset:"utf-8",
                beforeSend: function () {
                	$("#mensaje").html('Guardando...');
                },
                success:  function (response) {
                   // $("#mensaje").html('');
					//$("#idIdioma").val(response);
					if(!isNaN(response)){
					   
					}else{
						//$("#mensaje").html("Error: "+response);
						BootstrapDialog.show({
							title: 'Mensaje de salida',
							message: "Error: "+response,
							buttons: [{
								id: 'btn-ok',
								//icon: 'glyphicon glyphicon-check',
								label: 'OK',
								cssClass: 'btn-primary',
								autospin: false,
								action: function(dialogRef)
								{
									dialogRef.close();
								}
							}]
						});
						
					}
                }
        });
		
}*/
function eliminar(id_usuario, nombreUsuario){
	BootstrapDialog.show({
		title: '¡Advertencia!',
		message: '¿Está seguro de que desea eliminar a <b>'+nombreUsuario+'</b>? ',
		type: BootstrapDialog.TYPE_WARNING,
		buttons: [
		{
			label: ' Eliminar',
			icon: 'glyphicon glyphicon-trash',
			cssClass: 'btn-warning',			
			action: function(dialogItself){
				$.ajax({
						data:  'id='+id_usuario,
						url:   '{$_layoutParams.root}usuarios/index/eliminarUsuario',
						type:  'post',
						/*scriptCharset: "ISO-8859-1",*/
						scriptCharset:"utf-8",
						beforeSend: function () {
								//$("#mensaje").html('Guardando...');
						},
						success:  function (response) {
						    if(!isNaN(response)){
								//$("#fila_"+id_Planeacion).remove();
								var oTable = $('#example1').dataTable();
								// Immediately remove the first row
								oTable.fnDeleteRow( "#fila_"+id_usuario );
						    }else{
								BootstrapDialog.show({
									title: 'Error',
									message: "Error: "+response,
									buttons: [{
										id: 'btn-ok',
										//icon: 'glyphicon glyphicon-check',
										label: 'OK',
										cssClass: 'btn-primary',
										autospin: false,
										action: function(dialogRef)
										{
											dialogRef.close();
										}
									}]
								});

							}
						}
				});
				dialogItself.close();
			}
		}, {
			label: 'Cancelar',
			action: function(dialogItself){
				dialogItself.close();
			}
		}]
	});
}
/*function eliminar(id, nombreIdioma){
	BootstrapDialog.show({
		title: '¡Advertencia!',
		message: '¿Quiere eliminar el idioma <b>' + nombreIdioma + '</b>?',
		buttons: [
		{
			label: ' Eliminar',
			icon: 'glyphicon glyphicon-trash',			
			action: function(dialogItself){
				$.ajax({
						data:  'id='+id,
						url:   '{$_layoutParams.root}usuarios/index/eliminarUsuario',
						type:  'post',
						//scriptCharset: "ISO-8859-1",
						scriptCharset:"utf-8",
						beforeSend: function () {
								//$("#mensaje").html('Guardando...');
						},
						success:  function (response) {
						    if(!isNaN(response)){
								$("#fila_"+id).remove();
						    }else{
								BootstrapDialog.show({
									title: 'Error',
									message: "Error: "+response,
									buttons: [{
										id: 'btn-ok',
										//icon: 'glyphicon glyphicon-check',
										label: 'OK',
										cssClass: 'btn-primary',
										autospin: false,
										action: function(dialogRef)
										{
											dialogRef.close();
										}
									}]
								});

							}
						}
				});
				dialogItself.close();
			}
		}, {
			label: 'Cancelar',
			action: function(dialogItself){
				dialogItself.close();
			}
		}]
	});
}*/
</script>
<div class="box box-primary">
		
	            


	<!-- /.box-header -->
   <div class="box-body">
    	<div class="row box-header ">
	        <div class="col-xs-5">	          
		        
	        </div>
	        <div class="col-xs-4">
	        </div>
	        <div class="col-xs-3">	              	         
		      	<div class="box-tools">  	
		            <div class="input-group pull-right" >					                      
					  <a class="btn btn-primary" href="{$_layoutParams.root}usuarios/registro/editarUsuario/"> Agregar Usuario</a>			  
		            </div>
		        </div>		    
	        </div>
	    </div>
           
      <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
       	  <th>id</th>
       	  <th>Usuario</th>
          <th>Nombre</th>
          <th>Rol</th>
          <th>Plantel</th>
          <th class="no-sort">Estado</th>
          <th class="no-sort">Editar</th>
        </tr>
        </thead>
        <tbody id="contenido">
       


      
        </tbody>
        <tfoot>
        <tr>
          <th>id</th>
       	  <th>Usuario</th>
          <th>Nombre</th>
          <th>Rol</th>
          <th>Plantel</th>
          <th class="no-sort">Estado</th>
          <th class="no-sort">Editar</th>
        </tr>
        </tfoot>
      </table>
    </div>


</div>
<div id="divLoading"> </div>

<form action="{$_layoutParams.root}usuarios/registro/editarUsuario/" method="post" class="form" id="formp" name="formp">
 <input type="hidden" value="0" name="idUsuario" />

</form>

<!-- DataTables -->
<script src="../lte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../lte/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- bootstrap-switch -->
<script src="../../../public/js/bootstrap-switch.min.js"></script>
<script>

function abrirEditarUsuario(IdUser){
	//alert(IdUser);
	$("input[name=idUsuario]").val(IdUser);
	document.getElementById("formp").submit();
}


$(function () {
  	
    idPeriodo = 0;
  	var table = $('#example1').DataTable({
	  	//"ajax": "objects.txt",
	  	"ajax": {
	  		'type': 'POST',
		    "url": "{$_layoutParams.root}usuarios/index/traerUsuarios",
		    /*data: function ( d ) {
		        d.idPeriodo = idPeriodo;
		    },*/
		    beforeSend: function () {
            	$("div#divLoading").addClass('show');                
            }, 
            complete: function ( ) {
                $("div#divLoading").removeClass('show');
            },
		},

	  	//"ajax": "{$_layoutParams.root}planeacion/buscar",  	
	    "columns": [
	        { "data": "id" },
	        { "data": "Usuario" },
	        { "data": "Nombre" },
	        { "data": "Role"},
	        { "data": "Plantel" },
	        { "data": "Estado" },
	        { "data": "Editar"}
	    ],
	  	"language": {
	            "url": "{$_layoutParams.root}views/layout/lte/plugins/datatables/language/Spanish.json"
	    },
	    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]]
	    //Se ejecuta despues de cargar los datos ajax
	    /*"fnInitComplete": function(oSettings, json) {
	      $("[name='switch']").bootstrapSwitch();
	    }*/
	  	/*"columns": [
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    { "width": "12%" }
	  	]*/
	});
	table
		.order( [ 0, 'desc' ] )
	    // .clear()
		.draw();
});
	

   
$('#example1').on( 'draw.dt', function () {
	$("[name='switch']").bootstrapSwitch();
} );

//funcion para que no discrimine acentos
jQuery.fn.DataTable.ext.type.search.string = function ( data ) {
    return ! data ?
        '' :
        typeof data === 'string' ?
            data
                .replace( /έ/g, 'ε')
                .replace( /ύ/g, 'υ')
                .replace( /ό/g, 'ο')
                .replace( /ώ/g, 'ω')
                .replace( /ά/g, 'α')
                .replace( /ί/g, 'ι')
                .replace( /ή/g, 'η')
                .replace( /\n/g, ' ' )
                .replace( /[áÁ]/g, 'a' )
                .replace( /[éÉ]/g, 'e' )
                .replace( /[íÍ]/g, 'i' )
                .replace( /[óÓ]/g, 'o' )
                .replace( /[úÚ]/g, 'u' )
                .replace( /ê/g, 'e' )
                .replace( /î/g, 'i' )
                .replace( /ô/g, 'o' )
                .replace( /è/g, 'e' )
                .replace( /ï/g, 'i' )
                .replace( /ü/g, 'u' )
                .replace( /ã/g, 'a' )
                .replace( /õ/g, 'o' )
                .replace( /ç/g, 'c' )
                .replace( /ì/g, 'i' ) :
            data;
};

/*
 <div class="box-header with-border">
				
	<div  class="pull-left"><!--<h2>Usuarios</h2>--> </div>
	<div class=" pull-right">
	<a class="btn btn-primary" href="{$_layoutParams.root}usuarios/registro/editarUsuario/"> Agregar Usuario</a>
	</div>
	</div>
	<div class="box-footer">
	{if isset($usuarios) && count($usuarios)}
	    <table class="table table-bordered table-striped table-condensed">
	        <tr>
	            <!--<th>ID</th>-->
	            <th>Usuario</th>
				<th>Nombre</th>            
	            <th>Role</th>
	            <th>Editar</th>
	        </tr>
	        
	        {foreach from=$usuarios item=us}
	        <tr id="fila_{$us.id}">
	            <!--<td>{$us.id}</td>-->
	            <th>{$us.usuario}</th>
				<td>{$us.nombre}</td>            
	            <td>{$us.role}</td>
	            <td>
	                <a class="btn btn-sm btn-default" href="{$_layoutParams.root}usuarios/index/permisos/{$us.id}">
	                   Permisos
	                </a>

	                <input type="checkbox" name="switch" id="{$us.id}" data-size="small" data-on-text="Activo" data-off-text="Inactivo" data-label-width="0" onChange="guardaStatus(this.checked, this.id)" {if $us.estado == 1}checked{/if}>
	                <a class="btn btn-default dropdown-toggle dd-nodrag"  href="{$_layoutParams.root}usuarios/registro/editarUsuario/{$us.id}">
	                	<span class="glyphicon glyphicon-pencil"></span>
	                </a>
					<a class="btn btn-default dropdown-toggle dd-nodrag" onClick="eliminar({$us.id}, '{$us.usuario}')" >
	                	<span class="glyphicon glyphicon-trash"></span>
	                </a>

	            </td>
	        </tr>
	            
	        {/foreach}
	    </table>

	{/if}
	</div>*/
 </script>


<!--<script src="{$_layoutParams.root}public/js/bootstrap-switch.min.js"></script>
<script>$("[name='switch']").bootstrapSwitch();</script>-->