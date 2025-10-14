<?php
//require_once 'tcpdf/tcpdf.php';

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";


class ContractController
{
	public $idSubject;
	public $beginDate;
	public $endDate;
	public $valContract;

	public function generate()
	{
		$select = "id_subject,typedoc_subject,document_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,id_department_subject,name_department,id_municipality_subject,name_municipality,address_subject,email_subject,phone_subject,id_place_subject,name_place";
		$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . "&linkTo=id_subject&equalTo=" . $this->idSubject;
		$method = "GET";
		$fields = array();
		$subjects = CurlController::request($url, $method, $fields)->results[0];
		//echo '<pre>'; print_r($subjects->document_subject); echo '</pre>';exit;
		
		/* Variables de Impresión*/
		$nomEmpleado = strtoupper($fullname = $subjects->firstname_subject . " " . $subjects->secondname_subject . " " .
			$subjects->lastname_subject . " " . $subjects->surname_subject);
		$ideEmpleado = (int) $subjects->document_subject;
		//var_dump($ideEmpleado);
		$ideEmpleado = number_format($ideEmpleado, 0, ",", ".");
		$rolEmpleado = $subjects->name_place;
		$fecini = new DateTime($this->beginDate);
		$fecfin = new DateTime($this->endDate);
		
		$intervalo = $fecini->diff($fecfin);
		$numMeses = $intervalo->days / 30;
		$numMeses = (int) $numMeses;
		$diaDifer = $intervalo->days - ($numMeses * 30);
		$valDay = round(intval($this->valContract) / $intervalo->days, 2);
		$valMes = round($valDay * 30, 0);
		$valAdi = round($valDay * $diaDifer, 0);
		$letContract = TemplateController::MontoMonetarioEnLetras($this->valContract);
		$valLetras = TemplateController::MontoMonetarioEnLetras($valMes);
		$diaLetras = TemplateController::MontoMonetarioEnLetras($valAdi);
		$mesLetras = explode(" ", TemplateController::MontoMonetarioEnLetras($numMeses));
		$mesLetras = $mesLetras[0];
		
		$html = '
		<div class="page" id="page1">
			<table>
				<tr>
					<td>
						<img src="views/img/logos/logo_ut.png">
					</td>
					<td>
						<img src="views/img/logos/logout_472.png">
					</td>
					<td>
						CONTRATO DE PRESTACIÓN DE SERVICIOS No. <?php echo $numContract ?> DE 2025
					</td>
				</tr>
			</table>
			<div class="row">
				<div class="col-12" style="text-align: justify; font-size: 10px; line-height: 1.5;">
					Entre OSVALDO JOSE VILLALOBOS CORTINA, identificado con cedula de ciudadanía No. 73.111.404 expedida
					en CARTAGENA, BOLIVAR quien obra en nombre y representación de la UT UNION TEMPORAL JORNADA DEPORTIVA ESCOLAR
					COMPLEMENTARIA CARIBE- JDECC con NIT 901915364-1, quien para efectos del presente contrato se denominará EL
					CONTRATANTE, de una parte, y de la otra ' . $nomEmpleado . ', identificado con cedula de
					ciudadanía No. ' . $ideEmpleado . ', y quien para efectos del presente contrato se denominará EL CONTRATISTA,
					han acordado celebrar un contrato de prestación de servicios el cual se regirá por las siguientes:
				</div>
			</div>';
		
		$html .= '<span style="text-align: center; font-size: 10px; line-height: 1.5;">CLÁUSULAS</span>
			<div class="row">
				<div class="col-12" style="text-align: justify; font-size: 10px; line-height: 1.5;">
		
					PRIMERA. OBJETO. EL CONTRATISTA se compromete con EL CONTRATANTE a prestar de manera diligente y con plena autonomía,
					los servicios como ' . $rolEmpleado . ' en desarrollo del contrato suscrito por la UNION TEMPORAL JORNADA DEPORTIVA ESCOLAR
					COMPLEMENTARIA CARIBE - UT JDECC y SERVICIOS POSTALES NACIONALES S.A.S, cuyo objeto es “UNIR ESFUERZOS ADMINISTRATIVOS,
					OPERATIVOS QUE GARANTICEN PRESTAR SERVICIOS DE REALIZAR EL PROGRAMA JORNADA ESCOLAR COMPLEMENTARIA EN LE REGIÓN CARIBE”
					EN VIRTUD DEL CONTRATO INTERADMINISTRATIVO NO. COI-1083-2024, SUSCRITO CON EL CLIENTE MINDEPORTES.
					SEGUNDA. EJECUCIÓN DEL CONTRATO. Para una adecuada ejecución del presente contrato y conforme al plan y los
					requerimientos señalados por EL CONTRATANTE, EL CONTRATISTA deberá realizar:
				</div>
			</div>
		</div>';

		echo $html;
	}
}


/* Función para validar crear cargos disponibles */
if (isset($_POST["idSubject"])) {
	$ajax = new ContractController();
	$ajax->idSubject = $_POST["idSubject"];
	$ajax->beginDate = $_POST["beginDate"];
	$ajax->endDate = $_POST["endDate"];
	$ajax->valContract = $_POST["valContract"];
	$ajax->generate();
}

?>