<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController{

	public function data(){
		//echo '<pre>'; print_r($_POST); echo '</pre>';exit;
		if(!empty($_POST)){

			/* Capturando y organizando las variables POST de DT */
			$draw = $_POST["draw"];//Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 
			$orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)
			$orderBy = $_POST['columns'][$orderByColumnIndex]["data"];//Obtener el nombre de la columna de clasificación de su índice
			$orderType = $_POST['order'][0]['dir'];// Obtener el orden ASC o DESC
			$start  = $_POST["start"];//Indicador de primer registro de paginación.
            $length = $_POST['length'];//Indicador de la longitud de la paginación.

            /* El total de registros de la data */
            $url = "relations?rel=deliveries,typedeliveries,itemdeliveries,resources&type=delivery,typedelivery,itemdelivery,resource&select=id_delivery&linkTo=date_created_delivery&between1=".$_GET["between1"]."&between2=".$_GET["between2"];
			$method = "GET";
			$fields = array();
			$response = CurlController::request($url,$method,$fields);  
			if($response->status == 200){	
				$totalData = $response->total;
			}else{
				echo '{"data": []}';
                return;
			}	

			/* Búsqueda de datos */	
            $select = "id_delivery,id_typedelivery_delivery,name_typedelivery,id_itemdelivery_delivery,name_itemdelivery,number_delivery,date_delivery,id_resource_delivery,id_resource,name_resource,date_created_delivery";

            if(!empty($_POST['search']['value'])){
            	if(preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/',$_POST['search']['value'])){
	            	$linkTo = ["number_delivery","name_typedelivery","name_itemdelivery","name_resource","date_delivery"];
	            	$search = str_replace(" ","_",$_POST['search']['value']);
	            	foreach ($linkTo as $key => $value) {
	            		$url = "relations?rel=deliveries,typedeliveries,itemdeliveries,resources&type=delivery,typedelivery,itemdelivery,resource&select=".$select."&linkTo=".
                            $value."&search=".$search;
	            		$data = CurlController::request($url,$method,$fields)->results;
	            		if($data  == "Not Found"){
	            			$data = array();
	            			$recordsFiltered = count($data);
	            		}else{
	            			$data = $data;
	            			$recordsFiltered = count($data);
	            			break;
	            		}
	            	}
            	}else{
        			echo '{"data": []}';
                	return;
            	}
            }else{

	            /* Seleccionar datos */
	            $url = "relations?rel=deliveries,typedeliveries,itemdeliveries,resources&type=delivery,typedelivery,itemdelivery,resource&select=".$select.
                "&linkTo=date_created_delivery&between1=".$_GET["between1"]."&between2=".$_GET["between2"].
                "&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;
                //echo '<pre>'; print_r($url); echo '</pre>';exit;
	            $data = CurlController::request($url,$method,$fields)->results;
	            $recordsFiltered = $totalData;
            }  

            /* Cuando la data viene vacía */
            if(empty($data)){
            	echo '{"data": []}';
            	return;
            }

            /* Construimos el dato JSON a regresar */
            $dataJson = '{
            	"Draw": '.intval($draw).',
            	"recordsTotal": '.$totalData.',
            	"recordsFiltered": '.$recordsFiltered.',
            	"data": [';

            /* Recorremos la data */	
            foreach ($data as $key => $value) {
            	if($_GET["text"] == "flat"){
	            	$actions = "";
            	}else{
            		$actions = "<a href='/deliveries/edit/".base64_encode($value->id_delivery."~".$_GET["token"])."' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>
								<a href='/deliveries/items/".base64_encode($value->id_delivery."~".$_GET["token"])."' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-plus-circle'></i>
			            		</a>
			            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='".base64_encode($value->id_delivery."~".$_GET["token"])."' table='deliveries' suffix='delivery' deleteFile='no' page='deliveries'>
			            		<i class='fas fa-trash'></i>
			            		</a>";
			        $actions = TemplateController::htmlClean($actions);
            	}	
                
                $name_typedelivery = $value->name_typedelivery;
                $name_itemdelivery = $value->name_itemdelivery;
                $number_delivery = $value->number_delivery;
                $date_delivery = $value->date_delivery;
                $name_resource = $value->name_resource;
                $date_created_delivery = $value->date_created_delivery;

            	$dataJson.='{ 
            		"id_delivery":"'.($start+$key+1).'",
                    "name_typedelivery":"'.$name_typedelivery.'",
                    "name_itemdelivery":"'.$name_itemdelivery.'",
            		"number_delivery":"'.$number_delivery.'",
            		"date_delivery":"'.$date_delivery.'",
            		"name_resource":"'.$name_resource.'",
                    "date_created_delivery":"'.$date_created_delivery.'",
            		"actions":"'.$actions.'"
            	},';
            }
            $dataJson = substr($dataJson,0,-1); // este substr quita el último caracter de la cadena, que es una coma, para impedir que rompa la tabla
            $dataJson .= ']}';
            echo $dataJson;
		}
	}
}

/* Activar función DataTable */ 
$data = new DatatableController();
$data -> data();
