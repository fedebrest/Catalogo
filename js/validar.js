$(function(){
	$("#ok").hide();
	

	$("#formprod").validate({
		
			rules: {
                    "name": {
                        "required": true
						
                    },
                    "mail": {
                        "required": true,
						 "email": true,
                    },
                    "comentario": {
                        "required": true,
						 "minlength": "10"
						
                    },
			
		},
				messages: { "name": "Ingrese su nombre",
				
                    "mail": {
                        "required": "Ingrese su email",
                        "email": "Email inválido",
                    },
					
					"comentario": {
						"required": "Ingrese su consulta",
						 "minlength": "Mínimo 10 caracteres"
					}
					
					
					
                },
		
	
		submitHandler: function(form)
		{
			
			$(form).find("#enviar").attr("disabled", "disabled").attr("value","Enviando...");
            var dataString = 'name='+$('#name').val()+'&email='+$('#mail').val()+'&producto='+$('#producto').val()+'&consulta='+$('#comentario').val()+'&formid=1';
            $.ajax({
                type: "POST",
                url:"comentario_enviar.php",
                data: dataString,
                success: function(data){
                   $("#formularioproducto").hide("slow");
                    $("#ok").show("slow");
                   
                }
            });
        }
	});

});
