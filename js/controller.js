SERVER_URL="./modelo";
user_URL=SERVER_URL+'/users/';
client_URL=SERVER_URL+'/clgroups/';
programa_URL=SERVER_URL+'/programas/';
proyecto_URL=SERVER_URL+'/proyects/';
actividad_URL=SERVER_URL+'/actividads/';
alumnos_URL=SERVER_URL+'/meprofiles/';
config_calendar={
      calendar:{
        height: 450,
        editable: true,
        header:{
          left: 'month basicWeek basicDay',
          center: 'title',
          right: 'today prev,next'
        },
        dayNames : ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        dayNamesShort : ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
        monthNames : ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
		monthNamesShort : ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
		buttonText : { today:'Hoy', month:'mes', week:'semana', day:'día'}
      }
};
main = angular.module('principal',['ngRoute','ui.calendar','ui.bootstrap','ui.router','ngResource','angularFileUpload','colorpicker.module']);
main.config(function($routeProvider,$stateProvider,$urlRouterProvider){
	$urlRouterProvider.when('/proyectos/nuevo_proyecto', '/proyectos/nuevo_proyecto/nombre');
	$urlRouterProvider.when('/proyectos/:id', '/proyectos/:id/informacion');
	$stateProvider
        .state('inicio', {url:"/inicio",templateUrl:'views/inicio.html'})
        .state('datos', {url:"/datos",templateUrl:'views/datos.html'})
        .state('buscar', {url:"/buscar",templateUrl:'views/buscar.html'})
        .state('proyectos', {url:"/proyectos",templateUrl:'views/proyectos.html',controller:"homeproyectosCtrl"})
        .state('administrador', {url:"/administrador",templateUrl:'views/administrador.html',controller:"adminCtrl"})
    		.state('cliente', {url: "/administrador/cliente/:idc",templateUrl:'views/administrador/ficha_cliente.html',controller:"clientsCtrl"})
        .state('nuevo_proyecto', {url:"/proyectos/nuevo_proyecto",templateUrl:'views/proyecto/nuevo_proyecto.html',controller:"proyectosCtrl"})
        	.state('nuevo_proyecto.nombre', {url: "/nombre" ,templateUrl:'views/proyecto/nombre_proyecto.html'})
    		.state('nuevo_proyecto.cliente', {url: "/cliente",templateUrl:'views/proyecto/cliente_proyecto.html'})
    		.state('nuevo_proyecto.reclutamiento', {url: "/reclutamiento",templateUrl:'views/proyecto/reclutamiento_proyecto.html'})
    		.state('nuevo_proyecto.programa', {url: "/programa",templateUrl:'views/proyecto/programa_proyecto.html'})
    	.state('proyecto', {url:"/proyectos/:id",templateUrl:'views/proyecto/proyecto.html',controller:"adminproyectosCtrl"})
        	.state('proyecto.informacion', {url: "/informacion" ,templateUrl:'views/proyecto/informacion.html'})
        	.state('proyecto.alunmnos',{url:"/alumnos",templateUrl:'views/proyecto/alumnos.html', controller:'alumnosCtrl'})
    		.state('proyecto.seguimiento', {url: "/seguimiento",templateUrl:'views/proyecto/seguimiento.html'})
    		.state('proyecto.cronograma', {url: "/cronograma",templateUrl:'views/proyecto/cronograma.html'})
    		.state('proyecto.nueva_actividad',{url:"/actividades/agregar",templateUrl:'views/proyecto/actividades/agregar.html'})
        	.state('proyecto.actividad', {url:"/actividades/:ida",templateUrl:'views/proyecto/actividades/actividad.html',controller:'actividadCtrl'});
    $urlRouterProvider.otherwise("/inicio");
})
.config(function ($locationProvider){
	$locationProvider
  	.hashPrefix('!');
})
.config(['$httpProvider', function($httpProvider,$urlRouterProvider) {
        $httpProvider.defaults.useXDomain = true;
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
}]);
main.filter('dateToISO', function() {
  return function(input) {
    input = new Date(input).toISOString();
    return input;
  };
});
main.filter('filterId',function(){
  return function(input){
  	
  };
});
main.factory('calendario', function() {
	return {
	      today : function() {
		    calendario.dt = new Date();
		  },
		  clear: function(){
		  	calendario.dt=null;
		  },
		  open: function($event){
		  	$event.preventDefault();
		    $event.stopPropagation();
		    calendario.opened = true;
		  },
		  dateOptions: {formatYear: 'yy',startingDay:1}
	};
});
main.factory("mensajeria", function($rootScope){ //mensajeria.push({msg:"Usuario o contraseña incorrectos",type:"warning"});
	mensajes=[];
    return mensajes;
});

main.directive('ngReallyClick', [function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            element.bind('click', function() {
                var message = attrs.ngReallyMessage;
                if (message && confirm(message)) {
                    scope.$apply(attrs.ngReallyClick);
                }
            });
        }
    };
}]);
/*main.config(function($stateProvider,$urlRouterProvider,$routeProvider){
$stateProvider
        .state('nombre_proyecto', {url:"/nombre_proyecto",templateUrl: 'views/proyecto/nombre_proyecto.html'});
$urlRouterProvider.otherwise("/nombre_proyecto");
});*/
function mensajesCtrl($scope,mensajeria,$timeout){
	$scope.mensajes=mensajeria;
	$scope.mensajes_a_borrar=[];
	length_i=0;
	$scope.$watchCollection('mensajes', function() {
        if($scope.mensajes.length>0){
        	//length_i=$scope.mensajes.length;
	        $timeout(function(){
	        	$scope.closeAlert(0);
	        	},3600);
        }
    });
    $scope.closeAlert = function(index) {
    	mensajeria.splice(index,1)[0];
    	};
   	
}
function loginCtrl($scope, mensajeria, $http){
	$scope.login_try = function(){
		console.log($scope.User);
		$http.post(user_URL+"login.json",{User:$scope.User}).success(function(data){
			console.log(data);
			if(data.User){
				if(data.User.hasOwnProperty('key')){
					$.cookie("session_key",data.User.key,{expires:1});
				}
			}if(data.mensaje){
				mensajeria.push(traducir(data.mensaje));
			}
		}).error(function(data){
			
		});
	};
	$scope.login_check = function(){
		if($.cookie("session_key")){
			return false;
		}else return true;
	};
	$scope.logout = function(){
		$.cookie("session_key","",{expires:0});
	};
}

function traducir(mensaje){
		switch(mensaje.cuerpo){
			case "100":
				return {cuerpo:"Ingrese un usuario y contraseña",tipo:"warning"};
			break;
			case "101":
				return {cuerpo:"Debe iniciar sesión primero",tipo:"warning"};
			break;
			case "102":
				return {cuerpo:"Usuario o contraseña incorrectos",tipo:"warning"};
			break;
			case "103":
				return {cuerpo:"Debes ser administrador para hacer eso",tipo:"warning"};
			break;
		}
}
function adminCtrl($scope,$resource,mensajeria,$http,$upload){
	$scope.uiConfig = config_calendar;
	var Users=$resource(user_URL,{cookie:$.cookie("session_key")},{
		get: {method:'GET',url:SERVER_URL+"/users.json",isArray:true},
		add: {method:'POST',url:user_URL+'save.json'},
		destroy: {method:'POST',url:user_URL+'delete.json'}
	});
	var Clients=$resource(user_URL,{cookie:$.cookie("session_key")},{
		get: {method:'GET',url:SERVER_URL+"/Clgroups.json",isArray:true},
		add: {method:'POST',url:client_URL+'save.json'},
		destroy: {method:'POST',url:client_URL+'delete.json'}
	});
	var Programas=$resource(programa_URL,{cookie:$.cookie("session_key")},{
		get: {method:'GET',url:SERVER_URL+"/programas.json",isArray:true},
		add: {method:'POST',url:programa_URL+'save.json'},
		destroy: {method:'POST',url:programa_URL+'delete.json'}
	});
	$scope.files = [];
	$scope.archivos={};
    //listen for the file selected event
	$scope.subir_programa=function($files,tipo){
		 //$files: an array of files selected, each file has name, size, and type.
    for (var i = 0; i < $files.length; i++) {
      var file = $files[i];
      $scope.upload = $upload.upload({
        url: programa_URL+'uploadProgram.json', //upload.php script, node.js route, or servlet url
        //method: 'POST' or 'PUT',
        //headers: {'header-key': 'header-value'},
        //withCredentials: true,
        data: {cookie:$.cookie("session_key"),myObj: $scope.myModelObj},
        file: file, // or list of files ($files) for html5 only
        //fileName: 'doc.jpg' or ['1.jpg', '2.jpg', ...] // to modify the name of the file(s)
        // customize file formData name ('Content-Disposition'), server side file variable name. 
        //fileFormDataName: myFile, //or a list of names for multiple files (html5). Default is 'file' 
        // customize how data is added to formData. See #40#issuecomment-28612000 for sample code
        //formDataAppender: function(formData, key, val){}
      }).progress(function(evt) {
 		eval("$scope.porcent_"+tipo+"=parseInt(100.0 * evt.loaded / evt.total)");
        //console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
      }).success(function(data, status, headers, config) {
        eval("$scope.archivos."+tipo+"="+data);
        //console.log($scope.archivos);
        //$scope.archivos.push(data);
        // file is uploaded successfully
        //console.log(data);
      });
  	}
    };
    $scope.setear_programa=function($index){
		$scope.new_programa=angular.copy($scope.Programas[$index].Programa);
		$scope.programa_edit_index=$index;
	};
    $scope.guardar_programa=function(){
    	Programas.add({archivos:$scope.archivos,Programa:$scope.new_programa},function($promise){
    		if($promise.Programa){
				if($scope.new_programa.id){
					$scope.Programas[$scope.programa_edit_index]=$promise;
					$scope.programa_edit_index="";
				}else{
					$scope.Programas.push($promise);
				}
				$scope.new_programa={};
			}
    	});
    };
    $scope.borrar_programa=function(id,id2){
    	Programas.destroy({id:id},function($promise){
			if($promise.mensaje=502){
				$scope.Programas.splice(id2,1);
			}
		});
    };
	$scope.Users=Users.get();
	$scope.Clients=Clients.get();
	$scope.Programas=Programas.get();
	console.log($scope.Programas);
	$scope.setear_usuario=function($index){
		$scope.new_user=angular.copy($scope.Users[$index].User);
		$scope.user_edit_index=$index;
	};
	$scope.guardar_cliente=function(){
		Clients.add({Clgroup:$scope.new_client},function($promise){
			console.log($promise);
			if($promise.Clgroup){
				if($scope.new_client.id){
					$scope.Clients[$scope.client_edit_index]=$promise;
					$scope.client_edit_index="";	
				}else{
					$scope.Clients.push($promise);
				}
				$scope.new_client={};
			}
		});
	};
	$scope.borrar_cliente=function(id,id2){
		Clients.destroy({id:id},function($promise){
			if($promise.mensaje=502){
				$scope.Clients.splice(id2,1);
			}
		});
	};
	$scope.setear_cliente=function($index){
		$scope.new_client=angular.copy($scope.Clients[$index].Clgroup);
		$scope.client_edit_index=$index;
	};
	$scope.guardar_usuario=function(){
		Users.add({User:$scope.new_user},function($promise){
			if($promise.User){
				if($scope.new_user.id){
					$scope.Users[$scope.user_edit_index]=$promise;
					$scope.user_edit_index="";	
				}else{
					$scope.Users.push($promise);
				}
				$scope.new_user={};
			}
		});
	};
	$scope.borrar_usuario=function(id,id2){
		Users.destroy({id:id},function($promise){
			if($promise.mensaje=502){
				$scope.Users.splice(id2,1);
			}
		});
	};
}
function clientsCtrl($scope,$resource,mensajeria,$stateParams,$filter){
	var Clients=$resource(user_URL,{cookie:$.cookie("session_key")},{
		get: {method:'GET',url:client_URL+"view/"+$stateParams["idc"]+".json"},
		save: {method:'POST',url:client_URL+'update.json'},
		destroy: {method:'POST',url:client_URL+'delete.json'},
		destroy_contact: {method:'POST',url:client_URL+'deleteContact.json'},
		save_contact: {method:'POST',url:client_URL+'saveContact.json'},
		destroy_center: {method:'POST',url:client_URL+'deleteCenter.json'},
		save_center: {method:'POST',url:client_URL+'saveCenter.json'},
		destroy_event: {method:'POST',url:client_URL+'deleteEvent.json'},
		save_event: {method:'POST',url:client_URL+'saveEvent.json'}
	});
	$scope.client=Clients.get({id:$stateParams["idc"]},function($promise){
		console.log($scope.client);	
	});
	$scope.setear_contacto=function($index){
		$scope.new_contact=angular.copy($scope.client.Clprofile[$index]);
		$scope.contact_edit_index=$index;
	};
	$scope.guardar_contacto=function(){
		$scope.new_contact.clgroup_id=$scope.client.Clgroup.id;
		Clients.save_contact({Clprofile:$scope.new_contact},function($promise){
			if($scope.new_contact.id){
				$scope.client.Clprofile[$scope.contact_edit_index]=$promise.Clprofile;
				$scope.contact_edit_index="";	
			}else{
				$scope.client.Clprofile.push($promise.Clprofile);
			}
			$scope.new_contact={};
		});
	};
		$scope.borrar_contacto=function(id,id2){
		Clients.destroy_contact({id:id},function($promise){
			if($promise.mensaje=502){
				$scope.client.Clprofile.splice(id2,1);
			}
		});
	};
	$scope.setear_centro=function($index){
		$scope.new_center=angular.copy($scope.client.Centro[$index]);
		$scope.center_edit_index=$index;
	};
	$scope.guardar_centro=function(){
		$scope.new_center.clgroup_id=$scope.client.Clgroup.id;
		Clients.save_center({Centro:$scope.new_center},function($promise){
			if($scope.new_center.id){
				$scope.client.Centro[$scope.center_edit_index]=$promise.Centro;
				$scope.center_edit_index="";	
			}else{
				$scope.client.Centro.push($promise.Centro);
			}
			$scope.new_center={};
		});
	};
		$scope.borrar_centro=function(id,id2){
		//console.log(id);
		Clients.destroy_center({id:id},function($promise){
			if($promise.mensaje=502){
				$scope.client.Centro.splice(id2,1);
			}
		});
	};    
	$scope.setear_evento=function($index){
		$scope.new_event=angular.copy($scope.client.Evento[$index]);
		date=new Date($scope.new_event.fecha_inicio);
		$scope.new_event.hora_inicio=new Date(0,0,0,date.getHours(),date.getMinutes());
		$scope.new_event.fecha_inicio = date;
		date=new Date($scope.new_event.fecha_termino);
		$scope.new_event.hora_termino=new Date(0,0,0,date.getHours(),date.getMinutes());
		$scope.new_event.fecha_termino = date;
		//$scope.new_event.fecha_inicio = $filter('date')(date,"dd-MM-yyyy");
	    //$scope.new_event.fecha_termino = $filter('date')(new Date($scope.new_event.fecha_termino),"dd-MM-yyyy");
		$scope.event_edit_index=$index;
	};
	$scope.vaciar_evento=function(){
		console.log("!aaaaah!");
		$scope.new_event={};
		$scope.new_event.fecha_inicio=new Date();
		$scope.new_event.fecha_termino=new Date();
		$scope.new_event.hora_inicio=new Date();
		$scope.new_event.hora_termino=new Date();
	};
	$scope.guardar_evento=function(){
		$scope.new_event.clgroup_id=$scope.client.Clgroup.id;
		date=$scope.new_event.fecha_inicio;
		time=$scope.new_event.hora_inicio;
		$scope.new_event.fecha_inicio = new Date(date.getFullYear(), date.getMonth(), date.getDate(), time.getHours(), time.getMinutes()-time.getTimezoneOffset());
		date=$scope.new_event.fecha_termino;
		time=$scope.new_event.hora_termino;
		$scope.new_event.fecha_termino = new Date(date.getFullYear(), date.getMonth(), date.getDate(), time.getHours(), time.getMinutes()-time.getTimezoneOffset());
		
		Clients.save_event({Evento:$scope.new_event},function($promise){
			//console.log($promise);
			if($scope.new_event.id){
				date=new Date($promise.Evento.fecha_inicio);
				date.setMinutes(date.getMinutes()+date.getTimezoneOffset());
				$promise.Evento.fecha_inicio=date;
				date=new Date($promise.Evento.fecha_termino);
				date.setMinutes(date.getMinutes()+date.getTimezoneOffset());
				$promise.Evento.fecha_termino=date;
				$scope.client.Evento[$scope.event_edit_index]=$promise.Evento;
				$scope.event_edit_index="";	
			}else{
				$scope.client.Evento.push($promise.Evento);
			}
			$scope.new_event={};
		});
	};
		$scope.borrar_evento=function(id,id2){
		//console.log(id);
		Clients.destroy_event({id:id},function($promise){
			if($promise.mensaje=502){
				$scope.client.Evento.splice(id2,1);
			}
		});
	};
}
function datosCtrl($scope,$resource,mensajeria,$http){
	var Datos=$resource(user_URL,{cookie:$.cookie("session_key")},{
		get: {method:'GET',url:user_URL+'fetchProfile.json'},
		save: {method:'PUT',url:user_URL+'saveProfile.json'}
	});
	Datos.get(function($promise){
		console.log($promise);
		$scope.Profile=$promise.profile.Profile;
		$scope.proyectos=$promise.profile.Proyect;
		if($promise.mensaje){
			mensajeria.push(traducir(data.mensaje));
		}
	});
	$scope.editable=function(seccion){
		$scope.editar=true;
	};
	$scope.save=function(){
		console.log($scope.Profile);
		Datos.save({Profile:$scope.Profile},function($promise){
			if($promise.mensaje){
				mensajeria.push(traducir(data.mensaje));
			}
			$scope.Profile=$promise.Profile;
			$scope.editar=false;
		});
		
	};
}
function homeproyectosCtrl($scope,$resource,$stateParams){
	$scope.uiConfig = config_calendar;
    $scope.calendario=[];
    $scope.actividades=[$scope.calendario];
	var Proyectos=$resource(proyecto_URL,{cookie:$.cookie("session_key")},{
		get:{method:'GET',url:SERVER_URL+'/Proyects.json',isArray:true},
		edit: {method:'POST',url:proyecto_URL+"save.json"}
	});
	$scope.proyectos=Proyectos.get();
	$scope.proyectos.$then(function(data){
		angular.forEach($scope.proyectos,function(value,key){
			console.log(value);
			angular.forEach(value.Actividad,function(value2,key2){ 
				console.log(value2);
				actividad={
					title:value.Proyect.nombre+" "+value2.nombre+" "+new Date(value2.hora_inicio).format("HH:mm"),
					start:new Date(value2.fecha),
					url:'#!/proyectos/'+value.Proyect.id+"/actividades/"+value2.id,
					color:value.Proyect.color
					};
				$scope.calendario.push(actividad);
			});
		});
		console.log($scope.actividades);
	});
	$scope.progreso=function(proyecto){
		n_actividades=proyecto.Actividad.length;
		progreso=0;
		angular.forEach(proyecto.Actividad,function(value,key){
			if(value.realizada==1){
				progreso=progreso+1;
			}
		});
		return progreso/n_actividades*100;
	};
	$scope.progreso_texto=function(proyecto){
		n_actividades=proyecto.Actividad.length;
		progreso=0;
		angular.forEach(proyecto.Actividad,function(value,key){
			if(value.realizada==1){
				progreso=progreso+1;
			}
		});
		return progreso+"/"+n_actividades;
	};
}
function alumnosCtrl($scope,$resource,$stateParams){
	var Alumnos=$resource(alumnos_URL,{cookie:$.cookie("session_key")},{
		find:{method:'POST',url:alumnos_URL+'find.json',isArray:true},
		save:{method:'POST',url:alumnos_URL+'save.json'}
	});
	$scope.encontrar=function(){
		$scope.encontrados=Alumnos.find({objetivo:$scope.objetivo});
	};
	$scope.agregar_alumno=function(indice){
		keepGoing=true;
		angular.forEach($scope.proyecto.Meprofile,function(value,key){
			if(keepGoing){
				if(value.id==$scope.encontrados[indice].Meprofile.id){
					keepGoing=false;
				}
			}
		});
		if(!keepGoing){return;}
		$scope.encontrados[indice].Meprofile.Student={};
		$scope.encontrados[indice].Meprofile.Student.proyect_id=$scope.proyecto.Proyect.id;
		$scope.encontrados[indice].Meprofile.Student.meprofile_id=$scope.encontrados[indice].Meprofile.id;
		$scope.proyecto.Meprofile.push($scope.encontrados[indice].Meprofile);
		$scope.guardar_todo();
	};
	$scope.quitar_alumno=function(indice){
		$scope.proyecto.Meprofile.splice(indice,1);
		$scope.guardar_todo();
	};
}
function adminproyectosCtrl($scope,$resource,$stateParams){
	$scope.uiConfig = config_calendar;
	var Proyectos=$resource(proyecto_URL,{cookie:$.cookie("session_key")},{
		get:{method:'GET',url:proyecto_URL+'view.json'},
		save_activity:{method:'POST',url:proyecto_URL+'saveActivity.json'},
		update: {method:'POST',url:proyecto_URL+"update.json"},
		destroy: {method:'POST',url:proyecto_URL+"delete.json"}
	});
	var Alumnos=$resource(alumnos_URL,{cookie:$.cookie("session_key")},{
		find:{method:'POST',url:alumnos_URL+'find.json',isArray:true},
		save:{method:'POST',url:alumnos_URL+'save.json'}
	});
	var Clients=$resource(user_URL,{cookie:$.cookie("session_key")},{
		get: {method:'GET',url:SERVER_URL+"/Clgroups.json",isArray:true}
	});
	var Profiles=$resource(SERVER_URL+"/Profiles/",{cookie:$.cookie("session_key")},{
		get:{method:'GET',url:SERVER_URL+"/Profiles.json",isArray:true}
	});
    $scope.editando_basicos=false;
    $scope.calendario=[];
    $scope.new_alumno={};
	$scope.calendario_actividades=[$scope.calendario];
	Proyectos.get({id:$stateParams["id"]}).$then(function(resp){
		$scope.proyecto=resp.data;
		console.log($scope.proyecto);
		$scope.new_actividad={proyect_id:resp.data.Proyect.id,Profile:[]};
		$scope.perfiles=Profiles.get({id:resp.data.Proyect.id});
		
		angular.forEach(resp.data.Profile,function(value,key){
			value.Asistenciaeq={profile_id:value.id};
			$scope.new_actividad.Profile.push(value);
		});
		
		angular.forEach(resp.data.Actividad,function(value,key){
			actividad={
				title:value.nombre,
				start:new Date(value.fecha),
				url:'#!/proyectos/'+value.proyect_id+"/actividades/"+value.id
				};
			$scope.calendario.push(actividad);
		});
		
	});
	$scope.borrar_proyecto=function(){
		Proyectos.destroy({id:$scope.proyecto.Proyect.id}).$then(function(resp){
			if(resp.data.mensaje.cuerpo==502){
				location.href="#!/proyectos";
			}
		});
	};
	$scope.editar_cliente=function(){
		$scope.clientes=Clients.get();
		$scope.editando=true;
	};
	$scope.editar_miembros=function(){
		$scope.perfiles=Profiles.get({id:$scope.proyecto.Proyect.id});
		$scope.editando=true;
		//quitar_repetidos($scope.perfiles,$scope.miembros);
		//console.log($scope.miembros);
	};
	$scope.editar_basicos=function(){
		$scope.editando=true;
	};
	$scope.guardar_todo=function(){
		Proyectos.update({proyecto:$scope.proyecto}).$then(function(resp){
			$scope.proyecto=resp.data;
		});
		$scope.editando=false;
	};
	$scope.barra=function(string){
		url = location.href;
		if(url.indexOf(string)!=-1) return "active";
	};
	$scope.setear_cliente=function(indice){
		$scope.sel_cliente=$scope.clientes[indice];
		$scope.proyecto.Clgroup=$scope.sel_cliente.Clgroup;
		$scope.proyecto.Proyect.clgroup_id=$scope.sel_cliente.Clgroup.id;
		$scope.proyecto.Clprofile="";
		$scope.proyecto.Centro="";
		$scope.sel_contacto="";
		$scope.sel_centro="";
	};
	$scope.setear_contacto=function(obj){
		$scope.sel_contacto=obj;
		$scope.proyecto.Clprofile=obj;
		$scope.proyecto.Proyect.clprofile_id=obj.id;
	};
	$scope.setear_centro=function(obj){
		$scope.sel_centro=obj;
		$scope.proyecto.Centro=obj;
		$scope.proyecto.Proyect.centro_id=obj.id;
	};
	$scope.guardar_alumno=function(){
		$scope.new_alumno.Student={};	
		Alumnos.save({Meprofile:$scope.new_alumno}).$then(function(resp){
			$scope.new_alumno.id=resp.data.id;
			$scope.new_alumno.Student.meprofile_id=resp.data.id;
			$scope.new_alumno.Student.proyect_id=$scope.proyecto.Proyect.id;
			$scope.proyecto.Meprofile.push($scope.new_alumno);
			$scope.guardar_todo();
		});
	};
	$scope.agregar_recluta=function(indice){
		//new_member={profile_id:$scope.perfiles[indice].Profile.id};
		$scope.perfiles[indice].Profile.Member={proyect_id:$scope.proyecto.Proyect.id,profile_id:$scope.perfiles[indice].Profile.id};
		$scope.proyecto.Profile.push($scope.perfiles[indice].Profile);
		$scope.perfiles.splice(indice,1);
		console.log($scope.proyecto);
	};
	$scope.quitar_recluta=function(indice){
		$scope.perfiles.push({Profile:$scope.proyecto.Profile[indice]});
		$scope.proyecto.Profile.splice(indice,1);
	};
	$scope.guardar_actividad=function(){
		actividad=Proyectos.save_activity({Actividad:$scope.new_actividad});
		actividad.$then(function(data){
			console.log(data.data);
			if(data.data.Actividad){
				$scope.proyecto.Actividad.push(data.data.Actividad);
				$scope.actualizar();
				location.href="#!/proyectos/"+$scope.proyecto.Proyect.id+"/cronograma";
			}
		});
	};
	$scope.agregar_invitado=function(indice){
		//new_member={profile_id:$scope.perfiles[indice].Profile.id};
		$scope.perfiles[indice].Profile.Asistenciaeq={profile_id:$scope.perfiles[indice].Profile.id};
		$scope.new_actividad.Profile.push($scope.perfiles[indice].Profile);
		$scope.perfiles.splice(indice,1);
	};
	$scope.quitar_invitado=function(indice){
		$scope.perfiles.push({Profile:$scope.proyecto.Profile[indice]});
		$scope.new_actividad.Profile.splice(indice,1);
	};
	$scope.check_assist=function(arreglo,id){
		mensaje=false;
		angular.forEach(arreglo,function(value,key){
			if(value.profile_id==id && value.evaluacion != ""){
				mensaje=true;
			}
		});
		return mensaje;
	};
	$scope.actualizar=function(){
		Proyectos.get({id:$stateParams["id"]}).$then(function(resp){
					$scope.proyecto=resp.data;
		});
	};
	$scope.check_assist_emp=function(arreglo,id){
		mensaje=false;
		angular.forEach(arreglo,function(value,key){
			if(value.meprofile_id==id && value.evaluacion != ""){
				mensaje=true;
			}
		});
		return mensaje;
	};
}
function actividadCtrl($scope,$resource,$stateParams){
	Actividades=$resource(actividad_URL,{cookie:$.cookie("session_key")},{
		get: {method:"GET", url:actividad_URL+"view.json"},
		save: {method:"POST", url:actividad_URL+"save.json"},
		save_observacion: {method:"POST", url:actividad_URL+"addObservacion.json"},
		destroy: {method:"POST", url:actividad_URL+"delete.json"}
	});
	var Profiles=$resource(SERVER_URL+"/Profiles/",{cookie:$.cookie("session_key")},{
		get:{method:'GET',url:SERVER_URL+"/Profiles/getActivity.json",isArray:true}
	});
	$scope.actividad=Actividades.get({id:$stateParams["ida"]});
	$scope.editar=function(){
		$scope.editando=true;
	};
	$scope.agregar_miembros=function(){
		//console.log($scope.actividad.Actividad.id);
		$scope.profiles=Profiles.get({id:$scope.actividad.Actividad.id});
		console.log($scope.profiles);
	};
	$scope.agregar_miembro=function(indice){
		$scope.profiles[indice].Profile.Asistenciaeq={profile_id:$scope.profiles[indice].Profile.id};
		$scope.actividad.Profile.push($scope.profiles[indice].Profile);
		$scope.profiles.splice(indice,1);
	};
	$scope.quitar_miembro=function(indice){
		$scope.actividad.Profile.splice(indice,1);
		console.log($scope.actividad);
	};
	$scope.borrar=function(){
		borrado=Actividades.destroy({id:$scope.actividad.Actividad.id}).$then(function(data){
			if(data.data.mensaje.cuerpo=="501"){
				$scope.actualizar();
					location.href="#!/proyectos/"+$scope.actividad.Proyect.id+"/cronograma";
			}
		});
	};
	$scope.guardarlo_todo=function(){
		if($scope.actividad.Meprofile.length==0){
			$scope.actividad.Meprofile=$scope.proyecto.Meprofile;
			angular.forEach($scope.actividad.Meprofile,function(value,key){
					value.Asistenciame.actividad_id=$scope.actividad.Actividad.id;
					value.Asistenciame.meprofile_id=value.id;
					$scope.actividad.Meprofile.push(value);
			});
		}
		//console.log($scope.actividad);
		Actividades.save({Actividad:$scope.actividad}).$then(function(resp){
			$scope.actividad=resp.data;
		});
		$scope.actualizar();
		$scope.editando=false;
	};
}
function proyectosCtrl($scope,calendario,$resource,$stateParams){
	var Clients=$resource(user_URL,{cookie:$.cookie("session_key")},{
		get: {method:'GET',url:SERVER_URL+"/Clgroups.json",isArray:true}
	});
	var Profiles=$resource(SERVER_URL+"/Profiles/",{cookie:$.cookie("session_key")},{
		get:{method:'GET',url:SERVER_URL+"/Profiles.json",isArray:true}
		});
	var Programas=$resource(programa_URL,{cookie:$.cookie("session_key")},{
		get: {method:'GET',url:SERVER_URL+"/programas.json",isArray:true}
	});
	var Proyectos=$resource(proyecto_URL,{cookie:$.cookie("session_key")},{
		save: {method:'POST',url:proyecto_URL+"save.json"}
	});
	$scope.perfiles=Profiles.get();
	$scope.clientes=Clients.get();
	$scope.proyecto={};
	$scope.profiles=[];
	$scope.programas=Programas.get();
	$scope.miembros=[];
	$scope.setear_cliente=function(indice){
		$scope.sel_cliente=$scope.clientes[indice];
		$scope.proyecto.clgroup_id=$scope.sel_cliente.Clgroup.id;
		$scope.sel_contacto="";
		$scope.sel_centro="";
		$scope.proyecto.clprofile_id="";
		$scope.proyecto.centro_id="";
	};
	$scope.setear_contacto=function(obj){
		$scope.sel_contacto=obj;
		$scope.proyecto.clprofile_id=obj.id;
	};
	$scope.setear_centro=function(obj){
		$scope.sel_centro=obj;
		$scope.proyecto.centro_id=obj.id;
	};
	$scope.agregar_recluta=function(indice){
		//new_member={profile_id:$scope.perfiles[indice].Profile.id};
		$scope.perfiles[indice].Profile.Member={profile_id:$scope.perfiles[indice].Profile.id};
		$scope.profiles.push($scope.perfiles[indice].Profile);
		console.log($scope.profiles);
		$scope.perfiles.splice(indice,1);
		};
	$scope.quitar_recluta=function(indice){
		$scope.perfiles.push($scope.reclutas[indice]);
		$scope.profiles.splice(indice,1);
	};
	$scope.generar_proyecto=function(){
		//var aux = new Proyectos();
		aux2=Proyectos.save({proyecto:$scope.proyecto});
		aux2.$then(function(http){
			//console.log(http.data);
			location.href="#!/proyectos/"+http.data.id;
		});
	};
	$scope.setear_semestre=function(string){
		$scope.proyecto.semestre=string;
	};
	$scope.semestre = function(string){
		if($scope.proyecto.semestre==string){
			return "btn-info";
		}else{
			return "btn-default";
		}
	};
	$scope.barra=function(string){
		url = location.href;
		if(url.indexOf(string)!=-1) return "active";
	};
}
function searchCtrl($scope){
	$scope.lista_campos=[
	{nombre:'Nombre',name:'nombre',activo:true},
	{nombre:'Apellido',name:'apellido',activo:true},
	{nombre:'Teléfono',name:'fono',activo:true},
	{nombre:'Comuna',name:'comuna',activo:true}];
	
	$scope.resultados2=[];
	
	$scope.resultados=[
	{nombre:'Juanito', apellido:'Perez',fono:'9-8751412',comuna:'Constantinopla'}
	];
	
	$scope.evaluar = function(obj,str){
		return eval ("obj."+str);
	};
	$scope.congelar = function(){
		for (i=0; i < $scope.resultados.length; i++) {
		  if($scope.resultados[i].activo){
		  	obj=$scope.resultados[i];
		  	obj.activo=false;
		  	$scope.resultados2.push(obj);
		  	$scope.resultados.splice(i,1);
		  }
		}
	};
	$scope.descongelar = function(){
		for (i=0; i < $scope.resultados2.length; i++) {
		  if($scope.resultados2[i].activo){
		  	obj=$scope.resultados2[i];
		  	obj.activo=false;
		  	$scope.resultados.push(obj);
		  	$scope.resultados2.splice(i,1);
		  }
		}
	};
}

function topMenuCtrl($scope){
	$scope.active = function(string){
		url = location.href;
		if(url.indexOf(string)!=-1) return "active";
	};
}

function todoCtrl($scope){
	$scope.todo_list=[{name:"todo 1",ready:false},{name:"todo 2",ready:true}];
	$scope.agregar = function(){
		$scope.todo_list.push($scope.new_todo);
		$scope.new_todo="";
	};
	$scope.quitar = function (i){
		$scope.todo_list.splice(i,1);
	};
}
function obj_serializer(obj,padre){
	aux=[];
	for (var i=0; i < obj.length; i++){
		value=obj[i];
		eval("aux[value."+padre+".id]=value");
	}
	return aux;
}
function removeFromArray(target,id){
	$.each(target,function(k,v){
		if(v)if(v.id)if(v.id==id) target.splice(k,1);
	});
}
