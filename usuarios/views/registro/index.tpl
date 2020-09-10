<!--<link rel="stylesheet" href="../../../views/lte/bootstrap/css/bootstrap.min.css">-->
<link rel="stylesheet" type="text/css" href="../../../public/css/loader.css">

<div class="col-sm-8" style="padding:2px;"> 
	<div class="box box-primary">	
		<!--<div class="box-header with-border">
	        <h2 class="box-title"> {$titulo|default:""}</h2>
	        <div class="box-tools">
	                <a class="btn btn-primary" href="{$_layoutParams.root}usuarios/registro/editarUsuario/"> Nuevo Usuario</a>
			</div>
	        
		</div>		-->	
		<div class="box-footer">
		    <form method="POST" enctype="multipart/form-data" class="form" id="formp" name="formp" action="javascript:guardar()" autocomplete="off">
		        <input type="hidden" value="1" name="enviar" />

		        <!--<p class="p_profesores">
		            <label>Profesor: </label>
		            <select class="form-control select2" data-placeholder="" style="width: 100%;" name="profesor" id="profesor" required>
		            	<option value="0">Seleccione...</option>
		            	{html_options values=$profe_id output=$profe_nombre selected=$profe_selec}
		            </select>
		        </p>
		        <p class="p_directores">
		            <label>Director: </label>
		            <select class="form-control select2" data-placeholder="" style="width: 100%;" name="director" id="director" required>
		            	<option value="0">Seleccione...</option>
		            	{html_options values=$id_director output=$nombre_director selected=$profe_selec}
		            </select>
		        </p>-->
		        <div class="row">
		        	<div class="col-md-4">
		        		<p>
				        	<label>Rol: </label>			
				           	<select class="form-control" class="form-control" name="role" id="role" >
				           	{if $idUsuario|default:0}
				           		disabled
				           	{elseif Session::get('rol') == 15 || Session::get('rol') == 16}
				           		disabled
				           	{/if}>           		
								{html_options values=$role_id output=$role_denomina selected=$role_selec}
							</select>
							{if $idUsuario|default:0 }
				           		<input type="hidden" id="role" name="role" value="{$role_selec|default:0}"/>			           	
				           	{/if}
						</p>
					</div> 
				</div>
				{if Session::get('rol') == 1 || Session::get('rol') == 14}
				<div class="row divPlantel" style="display: none;">
		        	<div class="col-md-4">
		        		<p>
				        	<label>Plantel: </label>			
				           	<select class="form-control" class="form-control" name="plantel" id="plantel" >           		
								{$listaPlanteles}
							</select>
						</p>
					</div> 
				</div>
				{/if}
				<div class="row">			
			        <div class="col-md-8">
			        	<p>
				            <label>Nombre: </label>
				            <input  class="form-control" type="text" id="nombre" name="nombre" value="{$nombre|default:''}"/>
			        	</p>
			        </div>
			    </div>
		        	        	                  
		        <div class="row">			
			        <div class="col-md-8">
			        	<p>
				            <label>Correo electrónico: </label>
				            <input class="form-control" type="text" id="email" name="email" value="{$email|default:''}"/>
				        </p>
				    </div>
			    </div>
		        <div class="row">			
			        <div class="col-md-8">
			        	<p>
				            <label>Usuario: <!--<i class="fa fa-fw fa-question-circle" data-toggle="tooltip" title="escribe aqui el mensaje"></i>--></label>
				            <input class="form-control" type="text" name="usuario" id="usuario" value="{$usuario|default:''}" />
				        </p>
				    </div>
			    </div>
		       
		       	{if $idUsuario|default:0} 
			        <div class="row">			
				        <div class="col-md-8">		        	
					        <label>Contraseña: </label>				        
					    </div>
				    </div>
				    <div class="row">	
				    	<div class="col-md-8">
				    		<button type="button" class="btn btn-default" id="btn_cambiar_pass"><span class="fa fa-lock" aria-hidden="true"></span> Cambiar contraseña</button>	
				    	</div>
				    </div>
				    <p></p>
			    {/if}			

				

		        <div class="row" id="div_pass" {if $idUsuario|default:0} style="display: none;" {/if}>
			        <div class="col-sm-6 col-md-4">
						<p>
				            <label>{if $idUsuario|default:0} Nueva: {else} Contraseña: {/if} </label>
				            <input class="form-control" type="password" name="pass" id="pass" />
				        </p>
		        	</div>	
		        	<div class="col-sm-6 col-md-4">
				        <p>
				            <label>{if $idUsuario|default:0} Repetir contraseña nueva: {else} Repetir contraseña: {/if}  </label>
				            <input class="form-control"type="password" name="confirmar" id="confirmar"/>
				        </p>
				    </div>
		        </div>
		        <input type="hidden" value="0" name="validar_pass" />
		    	
				
		        <!--<p>
		          <label for="imagen">Imagen de perfil</label>
		          <input type="file" id="imagen" name="imagen" />
		          <p class="help-block">Imagen de 160x160 píxeles<!--, las extensiones permitidas son jpg, jpeg, gif y png.--><!--</p>
		          <img src="{$baseurl|default:''}{$img|default:'default.jpg'}" class="img-circle" alt="User Image" id="userImage" width="160px" height="160px">
		          <input type="hidden" id="hiddenImage" name="hiddenImage" value="{$img|default:0}"/>
		        </p>--><br>

		        
		           
					<input type="hidden" id="idUsuario" name="idUsuario" value="{$idUsuario|default:0}"/> 
		        <p>
		        	<div id="mensaje"></div>
		            <a class="btn" href="{$_layoutParams.root}usuarios">Salir</a>
		            <button type="submit" class="btn btn-primary">Guardar</button> 
		            <!--<button type="submit" id="guardaryotro" class="btn btn-primary">Guardar y capturar otro</button>-->
		            <button type="submit" id="guardarsalir" class="btn btn-primary">Guardar y salir</button>

		            <!--<button type="button" onClick="guardarSalir();" class="btn btn-primary">Guardar y salir</button>-->
		             
		           <!--<button type="submit" class="btn btn-primary">Enviar</button>-->
		        </p>
		    </form>
		</div>
	</div>
</div>
<div id="divLoading"> </div>

<script>

var salir = false;
$( "#guardarsalir" ).click(function() {
  salir = true;
});


var nuevo = false;
$( "#guardaryotro" ).click(function() {
  nuevo = true;
});



function guardar(){
	//var formData = new FormData($("#formp")[0]);
	//formData.append("dato", "valor");
	$.ajax({
                /*data:  $('#formp').serialize(),*/
				data:  $('#formp').serialize(), //formData,
                url:   '{$_layoutParams.root}usuarios/registro/guardarUsuario',
                type:  'POST',
				//dataType: 'json',
				/*scriptCharset: "ISO-8859-1",*/
				scriptCharset:"utf-8",
				//contentType: false,
	     		//processData: false,
                beforeSend: function () {
					$("div#divLoading").addClass('show');
                    $("#mensaje").html('Guardando...');
                },
                success:  function (response) {
                	//alert(response);	
                	$("div#divLoading").removeClass('show');
                	$("#mensaje").html('');

					/*if(!isNaN(response['response'])){
						$("#idUsuario").val(response['response']);
						d = new Date();
						$("#userImage").attr("src",response['urlImg']+"?"+d.getTime());						
						//$( "#usuario" ).prop( "disabled", true );
						BootstrapDialog.show({
							title: 'Mensaje de salida',
							message: 'Usuario registrado',
							buttons: [{
								id: 'btn-ok',
								//icon: 'glyphicon glyphicon-check',
								label: 'OK',
								cssClass: 'btn-primary',
								autospin: false,
								action: function(dialogRef)
								{
									dialogRef.close();
									if(salir){
                                        window.location="{$_layoutParams.root}usuarios/";
                                    }
                                    if(nuevo){
                                        window.location="{$_layoutParams.root}usuarios/registro/editarUsuario/";
                                    }                                    
								}
							}],
							onhidden: function(dialogRef){
		                    	if(salir){
                                    window.location="{$_layoutParams.root}usuarios/";
                                }
                                if(nuevo){
                                    window.location="{$_layoutParams.root}usuarios/registro/editarUsuario/";
                                }
				            }
						});
					}else{
						BootstrapDialog.show({
							title: 'Mensaje de salida',
							message: response,
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
					}*/
                },
                error: function (request, status, error) {
			        alert(request.responseText);
			        alert(error);
			    }
    })
    .done(function( data, textStatus, jqXHR ) { 
		try {
			var obj = JSON.parse(data); 

			//alert(obj.response);      
			var tipo = obj.response;
			var idUsuario = obj.nuevoId;
			var mensajeValidacion = obj.mensajeValidacion;
			var idCampoValidacion = obj.idCampo;

			if(tipo == 1)
		        var message = "Usuario actualizado";
		    else if(tipo == 2)
		        var message = "Usuario registrado";
		    else if(tipo == 3)
	        	var message = mensajeValidacion; //Mensaje de error de validación de campos

		    if (typeof idUsuario != 'undefined'){ //¿? tal ves lo puse porque regresaba undefined en algun caso
	          $("#idUsuario").val(idUsuario);
	        }

	        if(tipo == 1 || tipo == 2){
	          BootstrapDialog.show({
	            title: 'Mensaje',
	            message: message,
	            buttons: [{
	              id: 'btn-ok',
	              //icon: 'glyphicon glyphicon-check',
	              label: 'OK',
	              cssClass: 'btn-primary',
	              autospin: false,
	              action: function(dialogRef)
	              {
	                dialogRef.close();
	                    if(salir && (tipo == 1 || tipo == 2)){
	                        window.location="{$_layoutParams.root}usuarios/";
	                    }
	              }
	            }],
	            onhidden: function(dialogRef){
	                if(salir && (tipo == 1 || tipo == 2)){
	                    window.location="{$_layoutParams.root}usuarios/";
	                }
	            }
	          });
	        }else if(tipo == 3){
	          BootstrapDialog.show({
	            title: 'Aviso',
	            message: message,
	            type: BootstrapDialog.TYPE_WARNING,
	            buttons: [{
	              id: 'btn-ok',
	              //icon: 'glyphicon glyphicon-check',
	              label: 'OK',
	              cssClass: 'btn-warning',
	              autospin: false,
	              action: function(dialogRef)
	              {
	                dialogRef.close();                
	                if(salir && (tipo == 1 || tipo == 2)){
	                    window.location="{$_layoutParams.root}usuarios/";
	                }
	              }
	            }],
	            onhidden: function(dialogRef){
	                  if(tipo == 3){
	                    var idCampo = obj.idCampo;
	                    document.getElementById(idCampo).focus();
	                }
	            }
	          });
	        }else{
	            //$("#mensaje").html("Error: "+response);
	            BootstrapDialog.show({
	              title: 'Mensaje de salida',
	              message: "Error: "+obj.response,
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
	    } catch(e) {
	        alert("Error:\nValor inesperado en JSON\n\nMensaje devuelto por el servidor:  \n"+data); // error in the above string (in this case, yes)!
	    }
	})

	.fail(function( jqXHR, textStatus, errorThrown ) {
	    alert( "La solicitud a fallado: " +  textStatus);
	}); 		
}

$("#role").change(function() {
	var id_rol = $("#role").val();

	if(id_rol == 16 || id_rol == 15){	
		$('.divPlantel').show("swing");
	}else{
		$('.divPlantel').hide("swing");
		limpiarSelect('plantel');
	}
});

function limpiarSelect(nombreSelect){
	var $exampleMulti = $("#" + nombreSelect);
	$exampleMulti.val(null).trigger("change");
}

//Funcion que se ejecuta al cargar pagina
$(function() {
    var id_rol = $("#role").val();
	if(id_rol == 16 || id_rol == 15){		
		$('.divPlantel').show();
	}else{
		$('.divPlantel').hide();
		limpiarSelect('plantel');
	}
});

$("#btn_cambiar_pass").click(function(){
	var pass_is_visible = $('#div_pass').is(":visible");
	if(pass_is_visible){ //lo oculto
		$("#btn_cambiar_pass").html('<span class="fa fa-lock" aria-hidden="true"></span> Cambiar contraseña');
		$("input[name=pass]").val('');
		$("input[name=confirmar]").val('');
		$("input[name=validar_pass]").val(0);
		$('#div_pass').hide();
	}else{ //lo muestro
		$("#btn_cambiar_pass").html('<span class="fa fa-lock" aria-hidden="true"></span> Cancelar');
		$("input[name=validar_pass]").val(1);
		$('#div_pass').show();
		$("input[name=pass]").focus();
	}
    
});


</script>