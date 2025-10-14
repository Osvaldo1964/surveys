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
            $url = "relations?rel=titles,subjects,payorders&type=title,subject,payorder&select=id_title&linkTo=date_created_title&between1=".$_GET["between1"]."&between2=".$_GET["between2"];
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
            $select = "id_title,number_title,date_title,type_title,fullname_subject,amount_title,interest_title,number_payorder";

            if(!empty($_POST['search']['value'])){
            	if(preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/',$_POST['search']['value'])){
	            	$linkTo = ["number_title","date_title","fullname_subject","number_payorder"];
	            	$search = str_replace(" ","_",$_POST['search']['value']);
	            	foreach ($linkTo as $key => $value) {
	            		$url = "relations?rel=titles,subjects,payorders&type=title,subject,payorder&select=".$select."&linkTo=".
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
	            $url = "relations?rel=titles,subjects,payorders&type=title,subject,payorder&select=".$select.
                "&linkTo=date_created_title&between1=".$_GET["between1"]."&between2=".$_GET["between2"].
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
            		$actions = "<a href='/titles/edit/".base64_encode($value->id_title."~".$_GET["token"])."' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>
			            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='".base64_encode($value->id_title."~".$_GET["token"])."' table='titles' suffix='title' deleteFile='no' page='titles'>
			            		<i class='fas fa-trash'></i>
			            		</a>";
			        $actions = TemplateController::htmlClean($actions);
            	}	

                $number_title = $value->number_title;
                $date_title = $value->date_title;
                $type_title = $value->type_title;
            	$fullname_subject = $value->fullname_subject;
                $amount_title = $value->amount_title;
                $interest_title = $value->interest_title;
                $number_payorder = $value->number_payorder;

            	$dataJson.='{ 
            		"id_title":"'.($start+$key+1).'",
                    "number_title":"'.$number_title.'",
                    "date_title":"'.$date_title.'",
            		"type_title":"'.$type_title.'",
            		"fullname_subject":"'.$fullname_subject.'",
            		"amount_title":"'.$amount_title.'",
                    "interest_title":"'.$interest_title.'",
                    "number_payorder":"'.$number_payorder.'",
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
