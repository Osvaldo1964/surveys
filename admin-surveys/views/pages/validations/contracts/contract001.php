<?php

require_once '../../../../extensions/vendor/autoload.php';
require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";
require_once "../../../../config/config.php";
require_once "../../../assets/custom/helpers/helpers.php";

class ContractController
{
	public $idSubject, $beginDate, $endDate, $valContract, $numContract, $token_user,
		$nomEmpleado, $ideEmpleado;

	public function generate()
	{
		setlocale(LC_TIME, 'spanish');
		$url = "settings";
		$method = "GET";
		$fields = array();
		$response = CurlController::request($url, $method, $fields);
		$settings = $response->results[0];
		$this->numContract = $settings->numcontract_setting + 1;

		// Parametros del Sitio
		$url = "validations?seelct=*&id=" . $_POST["idSubject"] . "&nameId=id_subject_validation";
		$method = "GET";
		$fields = array();
		$response = CurlController::request($url, $method, $fields);
		$validations = $response->results[0];

		/* Busco el documento de la persona para generar la ruta para guardar el PDF */
		$select = "*";
		$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . "&linkTo=id_subject&equalTo=" .
			$this->idSubject;
		$method = "GET";
		$fields = array();
		$subjects = CurlController::request($url, $method, $fields)->results[0];

		/* Asigno las variables para la generación del contrato y la autorización */
		$this->nomEmpleado = strtoupper($subjects->firstname_subject . " " . $subjects->secondname_subject . " " .
			$subjects->lastname_subject . " " . $subjects->surname_subject);
		$this->ideEmpleado = $subjects->document_subject;
		$rolEmpleado = $subjects->name_place;
		$typerolEmpleado = $subjects->subplace_subject;
		/* Fin Asigno las variables para la generación del contrato y la autorización */

		/* Genero el PDF del Contrato*/
		$css = file_get_contents('../../../../views/pages/validations/contracts/informes.css');
		$plantilla = '';
		$directory = "../../../../views/img/subjects/" . trim($subjects->document_subject) . "/contrato_sin_firma.pdf"; // Directorio para guardar el contrato
		$mpdf = new \Mpdf\Mpdf([
			'format' => 'Legal',  // Tamaño de papel A4
			'margin_left' => 15,  // margen izquierdo en milímetros
			'margin_right' => 15, // margen derecho en milímetros
			'margin_top' => 5,   // margen superior en milímetros
			'margin_bottom' => 20 // margen inferior en milímetros

		]);
		$mpdf->AddPage("P");

		$plantilla = page01($this->idSubject, $this->nomEmpleado, $this->ideEmpleado, $rolEmpleado, $typerolEmpleado);
		$plantInit = initPage();
		$plantHead = pageHead($this->numContract);
		$plantFood = pageFoot();
		$mpdf->SetFooter($plantFood);
		$plantClose = pageClose($this->nomEmpleado, $this->ideEmpleado);

		$mpdf->writeHtml($plantInit, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->writeHtml($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);


		if ($rolEmpleado == "COORDINADOR REGIONAL") {
			$plantilla = pageCords($this->idSubject, $this->beginDate, $this->endDate, $this->valContract, $rolEmpleado);
			$mpdf->writeHtml($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

			$mpdf->AddPage("P");
			$plantilla2 = page02Cord($this->idSubject, $this->beginDate, $this->endDate, $this->valContract);
			$mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
			$mpdf->writeHtml($plantilla2, \Mpdf\HTMLParserMode::HTML_BODY);

			$mpdf->AddPage("P");
			$plantilla3 = page03Cord($this->nomEmpleado, $this->ideEmpleado);
			$mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
			$mpdf->writeHtml($plantilla3, \Mpdf\HTMLParserMode::HTML_BODY);
		}
		if ($rolEmpleado == "PROFESIONAL PSICOSOCIAL") {
			$plantilla = pagePsicosocial($this->idSubject, $this->beginDate, $this->endDate, $this->valContract, $rolEmpleado);
			$mpdf->writeHtml($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

			$mpdf->AddPage("P");
			$plantilla2 = page02Psico($this->beginDate, $this->endDate);
			$mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
			$mpdf->writeHtml($plantilla2, \Mpdf\HTMLParserMode::HTML_BODY);

			$mpdf->AddPage("P");
			$plantilla3 = page03Psico($this->nomEmpleado, $this->ideEmpleado);
			$mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
			$mpdf->writeHtml($plantilla3, \Mpdf\HTMLParserMode::HTML_BODY);
		}
		if ($rolEmpleado == "FORMADOR") {
			$plantilla = pageFormador($this->idSubject, $this->beginDate, $this->endDate, $this->valContract, $rolEmpleado);
			$mpdf->writeHtml($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

			$mpdf->AddPage("P");
			$plantilla2 = page02Former($this->beginDate, $this->endDate, $this);
			$mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
			$mpdf->writeHtml($plantilla2, \Mpdf\HTMLParserMode::HTML_BODY);

			$mpdf->AddPage("P");
			$plantilla3 = page03Former($this->nomEmpleado, $this->ideEmpleado);
			$mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
			$mpdf->writeHtml($plantilla3, \Mpdf\HTMLParserMode::HTML_BODY);
		}

		$mpdf->writeHtml($plantClose, \Mpdf\HTMLParserMode::HTML_BODY);

		$mpdf->Output($directory, 'F');
		/* Fin Genero el PDF del Contrato*/

		/* Genero el PDF de la Autorización */
		$css = file_get_contents('../../../../views/pages/validations/contracts/informes.css');
		$directory02 = "../../../../views/img/subjects/" . trim($subjects->document_subject) . "/autorizacion_sin_firma.pdf";
		$plantilla = '';
		$mpdf = new \Mpdf\Mpdf([
			'format' => 'Letter',  // Tamaño de papel A4
			'margin_left' => 15,  // margen izquierdo en milímetros
			'margin_right' => 15, // margen derecho en milímetros
			'margin_top' => 5,   // margen superior en milímetros
			'margin_bottom' => 20 // margen inferior en milímetros
		]);

		$mpdf->AddPage("P");
		$plantilla = pageAuth($this->numContract, $this->nomEmpleado, $this->ideEmpleado, $rolEmpleado, $typerolEmpleado);
		$mpdf->writeHtml($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

		$mpdf->Output($directory02, 'F');
		/* Fin Genero el PDF de la Autorización*/

		/* Actualizo el estado de contrato enviado*/
		$url = "subjects?id=" . $_POST["idSubject"] . "&nameId=id_subject&token=" . $_POST["token"] . "&table=users&suffix=user";
		$method = "PUT";
		$fields = "scontract_subject=1";
		$response = CurlController::request($url, $method, $fields);
		//echo '<pre>'; print_r($response); echo '</pre>';exit;
		/* Fin Actualizo el estado de contrato enviado*/

		/* Genero el registro dependiento del cargo */
		$ideEmpleado = (int) $subjects->document_subject;
		$rolSubject = $subjects->id_place_subject;
		//var_dump($rolSubject);

		/* COORDINADORES*/
		if ($rolSubject == 1) {
			$data = array(
				"document_cord" => $ideEmpleado,
				"fullname_cord" => $this->nomEmpleado,
				"id_department_cord" => $subjects->id_department_subject,
				"address_cord" => $subjects->address_subject,
				"email_cord" => $subjects->email_subject,
				"phone_cord" =>  $subjects->phone_subject,
				"begin_cord" =>  $_POST["beginDate"],
				"end_cord" =>  $_POST["endDate"],
				"salary_cord" =>  $_POST["valContract"],
				"shirts_cord" =>  $subjects->shirt_size_subject,
				"pants_cord" =>  $subjects->pant_size_subject,
				"eps_cord" =>  $subjects->eps_subject,
				"afp_cord" =>  $subjects->afp_subject,
				"arl_cord" =>  $subjects->arl_subject,
				"date_created_cord" => date("Y-m-d")
			);

			$url = "cords?token=" . $this->token_user . "&table=users&suffix=user";
			$method = "POST";
			$fields = $data;
			$response = CurlController::request($url, $method, $fields);
		}

		/* PSICOSOCIALES*/
		if ($rolSubject == 2) {
			$data = array(
				"document_psico" => $ideEmpleado,
				"fullname_psico" => $this->nomEmpleado,
				"id_department_psico" => $subjects->id_department_subject,
				"address_psico" => $subjects->address_subject,
				"email_psico" => $subjects->email_subject,
				"phone_psico" =>  $subjects->phone_subject,
				"begin_psico" =>  $_POST["beginDate"],
				"end_psico" =>  $_POST["endDate"],
				"salary_psico" =>  $_POST["valContract"],
				"shirts_psico" =>  $subjects->shirt_size_subject,
				"pants_psico" =>  $subjects->pant_size_subject,
				"eps_psico" =>  $subjects->eps_subject,
				"afp_psico" =>  $subjects->afp_subject,
				"arl_psico" =>  $subjects->arl_subject,
				"date_created_psico" => date("Y-m-d")
			);

			$url = "psicos?token=" . $this->token_user . "&table=users&suffix=user";
			$method = "POST";
			$fields = $data;
			$response = CurlController::request($url, $method, $fields);
		}

		/* FORMADORES */
		if ($rolSubject == 3) {
			$data = array(
				"document_former" => $ideEmpleado,
				"fullname_former" => $this->nomEmpleado,
				"id_department_former" => $subjects->id_department_subject,
				"id_municipality_former" => $subjects->id_municipality_subject,
				"id_school_former" => $_POST["school"],
				"address_former" => $subjects->address_subject,
				"email_former" => $subjects->email_subject,
				"phone_former" =>  $subjects->phone_subject,
				"begin_former" =>  $_POST["beginDate"],
				"end_former" =>  $_POST["endDate"],
				"salary_former" =>  $_POST["valContract"],
				"shirts_former" =>  $subjects->shirt_size_subject,
				"pants_former" =>  $subjects->pant_size_subject,
				"eps_former" =>  $subjects->eps_subject,
				"afp_former" =>  $subjects->afp_subject,
				"arl_former" =>  $subjects->arl_subject,
				"date_created_former" => date("Y-m-d")
			);

			$url = "formers?token=" . $this->token_user . "&table=users&suffix=user";
			$method = "POST";
			$fields = $data;
			$response = CurlController::request($url, $method, $fields);

			/* Actualizo el estado de contrato enviado*/
			$url = "schools?id=" . intval($_POST["school"]) . "&nameId=id_school&token=" . $_POST["token"] . "&table=users&suffix=user";
			$method = "PUT";
			$fields = "assign_school=S";
			$response = CurlController::request($url, $method, $fields);
			var_dump($response);
			exit;
		}

		/* Envio del contrato al correo registrado */
		$name = $this->nomEmpleado;
		$subject = "Inscripción en el programa de formación";
		$email = $subjects->email_subject;
		$bodyMail = "email_contract";
		$message = "Inscripción en el programa de formación";
		$attach = $directory;
		$token = $subjects->token_subject;
		$url = "";
		//$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $token, $bodyMail, "", $attach, $directory02);
		//var_dump($sendEmail);exit;
		/* Fin del correo electrónico */
	}
}

function page01($idSubject, $nomEmpleado, $ideEmpleado, $rolEmpleado, $typerolEmpleado)
{
	$tiporol = ($typerolEmpleado == "FORMADOR") ? $typerolEmpleado : "";

	$plantilla = '<div class="row mt-3">
			<div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
				Entre OSVALDO JOSE VILLALOBOS CORTINA, identificado con cedula de ciudadanía No. 73.111.404 expedida
				en CARTAGENA, BOLIVAR quien obra en nombre y representación de la UT UNION TEMPORAL JORNADA DEPORTIVA ESCOLAR
				COMPLEMENTARIA CARIBE- JDECC con NIT 901915364-1, quien para efectos del presente contrato se denominará EL
				CONTRATANTE, de una parte, y de la otra ' . $nomEmpleado . ', identificado con cedula de
				ciudadanía No. ' . $ideEmpleado . ', y quien para efectos del presente contrato se denominará EL CONTRATISTA,
				han acordado celebrar un contrato de prestación de servicios contenido en las cláusulas de este 
		  		documento, previa a las siguientes,
			</div>
		</div><br>';

	$plantilla .= '<div style="text-align: center;"><span>CONSIDERACIONES:</span></div><br>
		<div class="row"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
				Alineado con del plan de desarrollo 2022-2026 “Colombia, Mundial de la Vida, el Ministerio del Deporte 
				estructura y fortalece el programa JORNADA DEPORTIVA ESCOLAR COMPLEMENTARIA, desde una perspectiva de 
				formación integral, entendiendo esta como un sistema de formación complejo-sistémico, en el que convergen 
				desde un territorio llamado escuela, los aspectos culturales, sociales, de principios, en pro de la 
				comprensión y práctica de los derechos humanos, los enfoques de inclusión, diferenciales, territoriales, 
				étnicos y de equidad en relación con el desarrollo físico, motor, cognitivo, social y afectivo, de niñas, 
				niños, adolescentes y jóvenes. (en adelante NNAJ).<br>
				Es así, como el Ministerio del Deporte, contribuye al fortalecimiento de las estrategias de ampliación y 
				uso significativo del tiempo escolar, la protección de las trayectorias de vida, para aumentar las 
				oportunidades de aprendizaje diversificado de NNAJ en el contexto escolar, a través de la acción motriz 
				que permita acrecentar el desarrollo de experiencias deportivas, de actividad física, recreación, 
				ciudadanía y educación para la paz.<br>
				Es por lo anterior que desde el año 2023, las Escuelas Deportivas se vinculan al contexto escolar en el 
				esquema de Jornadas Deportivas Escolares Complementarias, en la ampliación de la jornada escolar, 
				contribuyendo al reconocimiento de los intereses de NNAJ en las regiones, desde las prácticas de la 
				acción motriz, entre ellas, las prácticas deportivas convencionales, las prácticas corporales ancestrales 
				y las nuevas tendencias, que permitan desarrollar hábitos y estilos de vidas saludables, afianzando las 
				experiencias al ejercicio físico para la salud, la proyección del deporte como un estilo de vida; así 
				como también, coadyuvar a elevar el rendimiento y clima escolar, disminuir la deserción en el sistema 
				educativo y brindar oportunidades a las NNAJ para utilizar el tiempo libre en un proceso de formación que 
				se centre en él y su relación con el entorno en la construcción del proyecto de vida desde edades tempranas.
				la Jornada Deportiva Escolar Complementaria (JDEC) es el programa nacional que a través de procesos de 
				enseñanza - aprendizaje complementa la educación de niñas, niños, adolescentes, en la jornada contraria a 
				la escolar, buscando una educación integral para desarrollar contenidos pedagógicos, didácticos y 
				metodológicos en la enseñanza, del deporte formativo, la recreación, la actividad física, el juego, la 
				cultura, las tradiciones y el fortalecimiento de habilidades socioemocionales.
				Esta estrategia se fundamenta en el Decreto 1052 de 2022, que establece lineamientos para la organización 
				y ejecución de programas deportivos en Colombia. Su finalidad es garantizar un enfoque integral y 
				cohesionado para el desarrollo deportivo en el país, asegurando que las prácticas y selecciones estén 
				alineadas con las políticas nacionales para el fomento del deporte y la formación de futuros atletas de 
				alto rendimiento.
				En consonancia con lo anterior, el MINISTERIO DEL DEPORTE, suscribió el CONVENIO INTERADMINISTRATIVO 
				número COI-1083-2024 con SERVICIOS POSTALES NACIONALES S.A.S., para la implementación Jornada Deportiva 
				Escolar Complementaria en la región caribe, la cual abarca 8 departamentos relacionados a continuación: 
				GUAJIRA, ARCHIPIÉLAGO DE SAN ANDRÉS, PROVIDENCIA Y SANTA CATALINA, CORDOBA, SUCRE, MAGDALENA, ATLANTICO, 
				BOLIVAR y CESAR.<br>
				1.	Que, una vez surtido el proceso anteriormente mencionado, SERVICIOS POSTALES NACIONALES S.A.S., 
				oferto en a su bolsa de aliados y dentro del trámite y las cotizaciones recibidas la entidad FUNDACIÓN 
				EMPRESARIAL Y SOLIDARIA DE COLOMBIA (FUNDAESCOL) Y FUNDACION HERMANOS A LA OBRA conformaron la UT JORNADA 
				ESCOLAR COMPLEMENTARIA DEL CARIBE-JDECC.<br>
				2.	Que, la UT UNION TEMPORAL JORNADA DEPORTIVA ESCOLAR COMPLEMETARIA CARIBE -JDECC fue conformada por 
				miembros de la bolsa de aliados y conjunto presentaron oferta de ejecución que se ajustó a los 
				requerimientos, dando como resultado la suscripción del contrato No. 018 del 2025.<br>
				3.	Que, en virtud de los antecedentes contractuales relacionados anteriormente, la UT UNION TEMPORAL 
				JORNADA DEPORTIVA ESCOLAR COMPLEMETARIA CARIBE -JDECC.<br>
				Como corolario de lo anterior, este contrato se regirá por las siguientes,</div></div></div><br>';

	$plantilla .= '<div style="text-align: center;"><span>CLÁUSULAS</span></div><br>
		<div class="row">
			<div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
				PRIMERA. OBJETO. EL CONTRATISTA se compromete con EL CONTRATANTE a prestar de manera diligente y con 
				plena autonomía, los servicios como ' . $rolEmpleado . ' - ' . $tiporol .
		' en desarrollo del contrato suscrito por la 
				UNION TEMPORAL JORNADA DEPORTIVA ESCOLAR COMPLEMENTARIA CARIBE - UT JDECC y SERVICIOS POSTALES 
				NACIONALES S.A.S, cuyo objeto es “UNIR ESFUERZOS ADMINISTRATIVOS, OPERATIVOS QUE GARANTICEN PRESTAR 
				SERVICIOS DE REALIZAR EL PROGRAMA JORNADA ESCOLAR COMPLEMENTARIA EN LE REGIÓN CARIBE” EN VIRTUD DEL 
				CONTRATO INTERADMINISTRATIVO NO. COI-1083-2024, SUSCRITO CON EL CLIENTE MINDEPORTES.
				<br>SEGUNDA. EJECUCIÓN DEL CONTRATO. Para una adecuada ejecución del presente contrato y conforme al plan 
				y los requerimientos señalados por EL CONTRATANTE, EL CONTRATISTA deberá realizar:
			</div>
		</div>
	</div>';

	return $plantilla;
}

function pagePsicosocial($idSubject, $beginDate, $endDate, $valContract, $rolEmpleado)
{
	/* Calculo los parametros del contrato */
	$select = "id_subject,typedoc_subject,document_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,id_department_subject,name_department,id_municipality_subject,name_municipality,address_subject,email_subject,phone_subject,id_place_subject,name_place";
	$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . "&linkTo=id_subject&equalTo=" . $idSubject;
	$method = "GET";
	$fields = array();
	$subjects = CurlController::request($url, $method, $fields)->results[0];

	/* Variables de Impresión*/
	$ideEmpleado = (int) $subjects->document_subject;
	$ideEmpleado = number_format($ideEmpleado, 0, ",", ".");
	$fecini = new DateTime($beginDate);
	$fecfin = new DateTime($endDate);

	$intervalo = $fecini->diff($fecfin);
	$numMeses = $intervalo->days / 30;
	$numMeses = (int) $numMeses;
	$diaDifer = $intervalo->days - ($numMeses * 30);
	$valDay = round(intval($valContract) / $intervalo->days, 2);
	$valMes = round($valDay * 30, 0);
	$valAdi = round($valDay * $diaDifer, 0);
	$letContract = TemplateController::MontoMonetarioEnLetras($valContract);
	$valLetras = TemplateController::MontoMonetarioEnLetras($valMes);
	$diaLetras = TemplateController::MontoMonetarioEnLetras($valAdi);
	$mesLetras = explode(" ", TemplateController::MontoMonetarioEnLetras($numMeses));
	$mesLetras = $mesLetras[0];

	$plantilla = '<br><div style="text-align: center;"><span>ACTIVIDADES GENERALES.</span></div><br>
		<div class="row"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
		1.	Acta de reuniones con directivos docentes, Tutor para el aprendizaje y la formación integral, padres de familia en 
		coherencia con las obligaciones específicas y las actividades generales a desarrollar, planteadas en el presente 
		documento.
		2.	Planeación pedagógica y por sesiones, de acuerdo con los formatos y las orientaciones establecidas por el 
		Ministerio del Deporte.
		3.	Informes mensuales y parciales cuando le sean requeridos del desarrollo técnico pedagógico.
		4.	Informes finales consolidados por escuelas de los procesos, según las orientaciones del programa de Jornada 
		Deportiva Escolar Complementaria. 
		5.	Deberá entregar Informe de resultados de la aplicación de las pruebas de entrada y salida, propuestas por el 
		programa de Jornada Deportiva Escolar Complementaria, que contengan, análisis de resultados, ajustes de la 
		planificación sobre los resultados obtenidos y todas aquellas demás que EL CONTRATISTA considere necesarias, de 
		acuerdo con los datos obtenidos.
		6.	Participar y apoyar, activamente de los talleres y procesos psicosociales, programados para padres de familia, 
		NNA beneficiarios y comunidad, en el marco del programa de Jornada Deportiva Escolar Complementaria.
		7.	Los demás entregables que sean requeridos con relación a sus obligaciones específicas, actividades según la 
		naturaleza de su objeto contractual.
		</div></div>
		<br><div style="text-align: center;"><span class="centrar-titulo">ACTIVIDADES ESPECIFICAS.</span></div><br>
		<div class="row"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
		1.	Presentar la planeación pedagógica y por sesiones de clase, con sus respectivos objetivos, sesiones, temas, 
		contenidos, materiales, evaluación y retroalimentación, según las orientaciones del programa Jornada Deportiva 
		Escolar Complementaria, elaborando una planeación mensual y por sesión con sus respectivas fechas y horarios, los 
		cuales deben ser entregados a los coordinadores según el cronograma establecido.
		2.	Apoyar técnica, administrativa y operativamente la socialización del programa Jornada Deportiva Escolar 
		Complementaria en los municipios y las Instituciones Educativas focalizadas de manera presencial.
		3.	Diligenciar y sistematizar los formatos establecidos por el programa de Jornada Deportiva Escolar 
		Complementaria: i) formatos de inscripción de los beneficiarios, ii) consentimiento y asentimiento informado de 
		padres de familia, iii) planeación unidades didácticas, iv) formatos de planeación de sesiones de las unidades 
		didácticas, v) asistencia a las sesiones entre otros, que se requieran durante la ejecución del programa, en medio 
		físico y magnético, con el total de la información diligenciada. La entrega se realizará según el cronograma que 
		se establezca.
		4.	Coordinar y realizar la inscripción de los cinco (5) grupos de acuerdo a la caracterización, en la Institución 
		Educativa de veinte (20) beneficiarios entre los 3 a 17 años divididos en grupos etarios de (3 a 5), (6 - 9), 
		(10 - 12), (13 - 15) y (16 - 17) para un total de (100) cien niños, niñas y adolescentes (NNA), quienes recibirán 
		dos (2) sesiones semanales de dos (2) horas (120 minutos), por grupo en cada sesión.
		5.	Asistir, participar en el proceso de capacitación en los aspectos metodológicos, pedagógicos, administrativos 
		psicosociales y de enfoque de derechos, territoriales e inclusivos, del programa de Jornada Deportiva Escolar 
		Complementaria, organizado por EL CONTRATANTE, SERVICIOS POSTALES NACIONALES S.A.S. y el Ministerio del Deporte.
		6.	Aplicar las evaluaciones de entrada y salida a los participantes de la Jornada Deportiva Escolar Complementaria, 
		a través de los test y pruebas orientadas, donde se evidencien las debilidades y fortalezas en los componentes de 
		desarrollo físico, motriz, social, cultural, de medio ambiente, hábitos y estilos de vida saludable, la promoción y 
		práctica de los principios, derechos y deberes de niños, niñas y adolescentes, de acuerdo con los lineamientos 
		orientados por EL CONTRATANTE, SERVICIOS POSTALES NACIONALES S.A.S. y el Ministerio del Deporte.
		7.	Planear y ejecutar el Festival Deportivo y Taller Psicosocial, en conjunto con el Coordinador, Profesional 
		Psicosocial y en alianza con el Instituto Municipal y/o Departamental de Deportes teniendo en cuenta actividades 
		lúdicas, recreativas, motrices, culturales y específicas del deporte, según el cronograma definido por EL CONTRATANTE 
		y SERVICIOS POSTALES NACIONALES S.A.S. y de acuerdo con las orientaciones del Ministerio del Deporte.
		8.	Entregar mensualmente informe de actividades e informe técnico del centro de interés deportivo que se genere 
		como producto del programa Jornada Deportiva Escolar Complementaria y enviarlos al Coordinador para revisión y 
		aprobación. Así mismo, el informe final de actividades de acuerdo con los lineamientos dados por el Ministerio del 
		Deporte.
		9.	Las demás actividades que le sean asignadas y que guarden relación con el objeto contractual.</div></div>';

	$plantilla .= '<div class="row"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
		PARÁGRAFO. LUGAR DE EJECUCIÓN: Se establece como lugar de ejecución de las actividades del presente contrato en el 
		departamento ' . $subjects->name_department . '.<br>
		TERCERA. PRECIO Y FORMA DE PAGO. Por la prestación de los servicios, EL CONTRATANTE pagará a EL CONTRATISTA la suma 
		total de ' . $letContract . ' M/CTE ($ ' . number_format($valContract, 2) . '). La forma de pago será la siguiente: ' .
		$mesLetras . ' pagos mensuales iguales equivalentes a ' . $valLetras . ' M/CTE ($ ' . number_format($valMes, 2) . ') al cumplirse el día 
		treinta (30) de cada mes previo cumplimento de requisitos y entrega de soportes de la ejecución de las actividades 
		contratadas, y un último pago equivalente a ' . $diaLetras . ' M/CTE ($ ' . number_format($valAdi, 2) . '), una vez EL CONTRATISTA, 
		cumpla con la totalidad de actividades generales y</div>';

	return $plantilla;
}

function pageFormador($idSubject, $beginDate, $endDate, $valContract, $rolEmpleado)
{
	/* Calculo los parametros del contrato */
	$select = "id_subject,typedoc_subject,document_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,id_department_subject,name_department,id_municipality_subject,name_municipality,address_subject,email_subject,phone_subject,id_place_subject,name_place";
	$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . "&linkTo=id_subject&equalTo=" . $idSubject;
	$method = "GET";
	$fields = array();
	$subjects = CurlController::request($url, $method, $fields)->results[0];

	/* Variables de Impresión*/
	$ideEmpleado = (int) $subjects->document_subject;
	$ideEmpleado = number_format($ideEmpleado, 0, ",", ".");
	$fecini = new DateTime($beginDate);
	$fecfin = new DateTime($endDate);

	$intervalo = $fecini->diff($fecfin);
	$numMeses = $intervalo->days / 30;
	$numMeses = (int) $numMeses;
	$diaDifer = $intervalo->days - ($numMeses * 30);
	$valDay = round(intval($valContract) / $intervalo->days, 2);
	$valMes = round($valDay * 30, 0);
	$valAdi = round($valDay * $diaDifer, 0);
	$letContract = TemplateController::MontoMonetarioEnLetras($valContract);
	$valLetras = TemplateController::MontoMonetarioEnLetras($valMes);
	$diaLetras = TemplateController::MontoMonetarioEnLetras($valAdi);
	$mesLetras = explode(" ", TemplateController::MontoMonetarioEnLetras($numMeses));
	$mesLetras = $mesLetras[0];

	$plantilla = '<br><div style="text-align: center;"><span>ACTIVIDADES GENERALES.</span></div><br>
		<div class="row"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
		1.	Acta de reuniones con directivos docentes, Tutor para el aprendizaje y la formación integral, padres de familia en 
		coherencia con las obligaciones específicas y las actividades generales a desarrollar, planteadas en el presente 
		documento.
		2.	Planeación pedagógica y por sesiones, de acuerdo con los formatos y las orientaciones establecidas por el 
		Ministerio del Deporte.
		3.	Informes mensuales y parciales cuando le sean requeridos del desarrollo técnico pedagógico.
		4.	Informes finales consolidados por escuelas de los procesos, según las orientaciones del programa de Jornada 
		Deportiva Escolar Complementaria. 
		5.	Deberá entregar Informe de resultados de la aplicación de las pruebas de entrada y salida, propuestas por el 
		programa de Jornada Deportiva Escolar Complementaria, que contengan, análisis de</div></div>';

	$plantilla .= '';

	return $plantilla;
}

function pageCords($idSubject, $beginDate, $endDate, $valContract)
{

	/* Calculo los parametros del contrato */
	$select = "id_subject,typedoc_subject,document_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,id_department_subject,name_department,id_municipality_subject,name_municipality,address_subject,email_subject,phone_subject,id_place_subject,name_place";
	$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . "&linkTo=id_subject&equalTo=" . $idSubject;
	$method = "GET";
	$fields = array();
	$subjects = CurlController::request($url, $method, $fields)->results[0];


	/* Variables de Impresión*/
	$ideEmpleado = (int) $subjects->document_subject;
	$ideEmpleado = number_format($ideEmpleado, 0, ",", ".");
	$fecini = new DateTime($beginDate);
	$fecfin = new DateTime($endDate);

	$intervalo = $fecini->diff($fecfin);
	$numMeses = $intervalo->days / 30;
	$numMeses = (int) $numMeses;
	$diaDifer = $intervalo->days - ($numMeses * 30);
	$valDay = round(intval($valContract) / $intervalo->days, 2);
	$valMes = round($valDay * 30, 0);
	$valAdi = round($valDay * $diaDifer, 0);
	$letContract = TemplateController::MontoMonetarioEnLetras($valContract);
	$valLetras = TemplateController::MontoMonetarioEnLetras($valMes);
	$diaLetras = TemplateController::MontoMonetarioEnLetras($valAdi);
	$mesLetras = explode(" ", TemplateController::MontoMonetarioEnLetras($numMeses));
	$mesLetras = $mesLetras[0];

	$plantilla = '<br><div style="text-align: center;"><span>ACTIVIDADES GENERALES.</span></div><br>
	<div class="row"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
	1.	Realizar y entregar al CONTRATANTE, según la programación establecida, el plan de acción incluido el cronograma 
	de visitas e intervenciones pedagógicas y psicosociales, la gestión de articulación sectorial con entidades del estado 
	que brindan programas de infancia y adolescencia, además de evidenciar los siguientes aspectos: presentación en los 
	entes deportivos departamentales y municipales, plan de gestión de riesgos con las entidades de salud, autoridades y 
	entidades encargadas de prevención de desastres, con el fin de prever situaciones que afecten la integridad física y 
	mental de los beneficiarios del programa en cada departamento y municipio.
	2.	Socializar el programa ante el gobierno local: Alcalde, Secretarías de Educación y directivos docentes, Secretaria 
	de Salud, Secretaría de Desarrollo Social, Secretaría de Medio Ambiente, Bienestar familiar, casa de la juventud, casas 
	de justicia, la defensoría del pueblo, entes y organismo del deporte, la recreación y la actividad física, las JAC o 
	presidentes de JAC, facultades de educación física, recreación y deportes, gestionando el trabajo articulado y la 
	participación integral.
	3.	Contactarse con el Tutor para el aprendizaje y la formación integral: gestionar ante el Tutor de los 
	establecimientos educativos, el desarrollo de la Jornada Deportiva Escolar Complementaria en los colegios; así como 
	también verificar información de escenarios, la vinculación de estudiantes, la divulgación a las familias sobre la 
	oferta y aquellas acciones que puedan considerarse como de apoyo y beneficien el desarrollo del programa en la región.
	4.	Conocer y gestionar la inclusión de los beneficiarios de la Jornada Deportiva Escolar Complementaria: en los 
	programas orientados a la prevención de la vulneración de los derechos de las niñas, niños, adolescentes y jóvenes, 
	como también gestionar el apoyo de los profesionales en trabajo social, psicología, entre otras, de estas instituciones 
	para realizar seguimiento a los casos detectados por el Formador con respecto a las problemáticas sociales y 
	vulnerabilidad que se presentan en los grupos intervenidos.
	5.	Evaluar durante las visitas la asistencia, desarrollo de la sesión de clase de los Formadores y el profesional 
	psicosocial, uso de material deportivo, que la verbalización de las actividades sea adecuada para la población atendida. actividades que deben encontrarse soportadas en actas de reunión y registro fotográfico. Entregará un informe después de cada visita que contenga aspectos, positivos y a mejorar.
	6.	Orientar, supervisar y viabilizar el correcto diligenciamiento de los formatos de inscripción, consentimientos 
	informados, planeación mensual de sesiones, formatos de sesiones de clase, asistencia a las sesiones, encuestas y las 
	que se requieran durante la ejecución del programa de Jornada Deportiva Escolar Complementaria, en medio físico y 
	magnético, completando el total de la información.
	7.	Acompañar y apoyar a las actividades desarrolladas por el programa Jornada Deportiva Escolar Complementaria, en 
	el departamento y municipios en donde sea asignado.
	8.	Los demás entregables que sean requeridos con relación a sus obligaciones específicas, actividades según la 
	naturaleza de su objeto contractual.
	</div></div>
	<br><div style="text-align: center;"><span class="centrar-titulo">ACTIVIDADES ESPECIFICAS.</span></div><br>
	<div class="row"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
	1.	Realizar la presentación del programa con las Secretarías de Educación y directivos docentes, Tutores para el 
	aprendizaje y la formación integral, Alcaldías, Institutos Departamentales y/o Municipales de Deportes, Salud y 
	Desarrollo Social, ICBF, Casas de Justicia, Secretarías de medio ambiente o quien haga sus veces, entre otras afines 
	al programa JDEC, con el fin de construir redes de apoyo de trabajo intersectorial para la ejecución del programa 
	Jornada Deportiva Escolar Complementaria para los procesos de alistamiento, ejecución y cierre del proyecto.
	2.	Coordinar y realizar seguimiento a la inscripción de los cinco (5) grupos de acuerdo a la caracterización que 
	realice el conveniente y/o asociado en la Institución Educativa de veinte (20) beneficiarios entre los 3 a 17 años 
	divididos en grupos etarios de (3 a 5) (6 - 9) (10 - 12) (13 - 15) y (16 - 17) para un total de (100) cien niños, 
	niñas y adolescentes (NNA), quienes recibirán dos (2) sesiones semanales de dos (2) horas (120 minutos), por grupo 
	en cada sesión y las intervenciones Psicosociales correspondientes de acuerdo con el cronograma de atención.
	3.	Coordinar y desarrollar la planeación, revisión y retroalimentación de los planes de clase, informes de 
	actividades, y la organización de eventos como festivales deportivos y talleres psicosociales. Estos documentos, 
	elaborados por los formadores y profesionales psicosociales, deben estar de acuerdo con los lineamientos pedagógicos, 
	técnicos y administrativos del programa, siguiendo los criterios establecidos por el Ministerio del Deporte a EL 
	CONTRATANTE. Además, de realizar acciones de mejora cuando sea necesario para corregir posibles desviaciones de la 
	planeación del programa.
	4.	Realizar acompañamiento y visitas de seguimiento a los formadores y profesionales psicosociales del programa 
	Jornada Deportiva Escolar Complementaria para el cumplimiento de las metas y objetivos establecidos en los lineamientos 
	del programa. Dichas visitas pueden realizarse de manera presencial y/o virtual de acuerdo con los lineamientos que 
	el Ministerio del Deporte solicite a EL CONTRATANTE.
	5.	Consolidar, verificar y reportar la aplicación de las herramientas establecidas para la ejecución y seguimiento 
	del programa Jornada Deportiva Escolar Complementaria, tales como: Inscripciones con sus soportes, formatos, encuestas, 
	pruebas físicas, bases de datos y/o evaluaciones de los procesos formativos desarrollados, definido por EL CONTRATISTA 
	de conformidad con las instrucciones impartidas por el Ministerio del Deporte,</div></div>';

	return $plantilla;
}

function page02Psico($beginDate, $endDate)
{

	$plantilla2 = '<div class="row mt-3"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
	 especificas relacionadas en el presente contrato y suministre la información en los formatos establecidos.

	<br>PARÁGRAFO PRIMERO. Serán requisitos indispensables para el pago que EL CONTRATISTA presente:

		a)	Cuenta de cobro;
		b)	copia del RUT;
		c)	Planilla integrada de liquidación de aportes (PILA) que acredite el pago al Sistema General de Seguridad Social 
		Integral.

	<br>CUARTA. PLAZO. Este contrato tendrá una duración comprendida entre el ' . $beginDate . ' hasta el ' . $endDate . '. No 
	obstante, el contrato podrá ser terminado de forma anticipada por parte de EL CONTRATANTE, en cualquier momento previa 
	comunicación escrita, caso en el cual se reconocerán solamente los honorarios equivalentes a las actividades y productos 
	efectivamente entregados a la fecha.
	<br>PARÁGRAFO PRIMERO: En caso de terminación anticipada del contrato, EL CONTRATISTA deberá entregar todos los documentos y 
	demás resultados producto de la ejecución contractual realizados hasta la fecha y elementos entregados al contratista 
	para el desarrollo del objeto contractual. 
	<br>PARÁGRAFO SEGUNDO: EL CONTRATANTE podrá suspender el presente contrato en cualquier momento previa comunicación escrita, 
	lo cual se formalizará en un acta suscrita por las partes en la cual se indicará la duración de la suspensión. Si cumplido 
	el plazo de la suspensión no es posible reanudar las actividades, EL CONTRATANTE, a su arbitrio, podrá dar por terminado 
	el contrato de forma unilateral sin que haya lugar a indemnizaciones a cualquier título.
	<br>QUINTA. OBLIGACIONES DEL CONTRATISTA. a) Cumplir en forma oportuna el objeto y las actividades acordadas. b) Aportar su 
	experiencia y los conocimientos necesarios para la adecuada ejecución del contrato. c) Absolver las consultas que EL 
	CONTRATANTE le solicite, relacionadas con el objeto del contrato. d) Asistir a las reuniones en los días y horas 
	previamente acordados por EL CONTRATANTE. e) Tener vigente al momento de prestar sus servicios profesionales la afiliación 
	al Sistema General de Seguridad Social. f) Garantizar la mejor calidad con respecto a la ejecución de las actividades y 
	entregables señalados en la cláusula segunda. En tal sentido, deberá subsanar inmediatamente cualquier incumplimiento 
	total o parcial identificado por EL CONTRATANTE y en caso de no recibirlos a completa satisfacción, EL CONTRATANTE se 
	reserva el derecho de contratar con un tercero idóneo la terminación de las actividades y entregables, caso en el cual 
	EL CONTRATISTA asumirá, a título de perjuicios, el valor de los honorarios facturados por el tercero; para lo anterior, 
	autoriza desde ya a EL CONTRATANTE para deducir tales sumas de dinero de los honorarios que pueda adeudarle. g) Cumplir 
	las políticas y reglamentos vigentes de EL CONTRATANTE en lo que resulte pertinente. h) Suministrar por su cuenta y riesgo, 
	el transporte necesario para desplazarse a los lugares en donde deban realizarse las actividades objeto de este contrato. 
	i) Todas las demás obligaciones que se derivan de la naturaleza de este contrato.
	<br>SEXTA. OBLIGACIONES DE EL CONTRATANTE. a) Pagar el valor establecido en la cláusula tercera. b) Facilitar a EL CONTRATISTA 
	el acceso a la información que sea necesaria, de manera oportuna y prestar el apoyo requerido para la debida ejecución del 
	objeto del contrato. c) Cumplir con lo estipulado en las demás cláusulas y condiciones previstas en este contrato y demás 
	anexos.
	<br>PARÁGRAFO PRIMERO. Durante la ejecución del contrato EL CONTRATANTE no asume ninguna obligación de custodia o seguridad en 
	relación con la integridad física del personal o los bienes materiales de EL CONTRATISTA.
	<br>SÉPTIMA. EXCLUSIÓN DE LA RELACIÓN LABORAL. Dada la naturaleza de este contrato, no existirá relación laboral alguna entre 
	EL CONTRATANTE y EL CONTRATISTA, o el personal que éste contrate para apoyar la ejecución del objeto contractual. EL 
	CONTRATISTA se compromete con EL CONTRATANTE a ejecutar en forma independiente y con plena autonomía técnica y 
	administrativa, el objeto mencionado en la cláusula primera de este documento.
	<br>OCTAVA. CLÁUSULA DE CONFIDENCIALIDAD. EL CONTRATISTA deberá mantener la confidencialidad sobre toda la información de EL 
	CONTRATANTE que conozca o a la que tenga acceso con ocasión del presente del Contrato con independencia del medio en cual 
	se encuentre soportada. Se tendrá como información confidencial cualquier información no divulgada que posea legítimamente 
	EL CONTRATANTE que pueda usarse en alguna actividad académica, productiva, industrial o comercial y que sea susceptible 
	de comunicarse a un tercero. Sin fines restrictivos la información confidencial podrá versar sobre invenciones, modelos 
	de utilidad, programas de software, fórmulas, métodos, know-how, procesos, diseños, nuevos productos, trabajos en 
	desarrollo, requisitos de comercialización, planes de mercadeo, nombres de clientes y proveedores existentes y potenciales, 
	así como toda otra información que se identifique como confidencial a EL CONTRATISTA. La información confidencial incluye 
	también toda información recibida de terceros que EL CONTRATISTA está obligado a tratar como confidencial, así como las 
	informaciones orales que EL CONTRATANTE identifique como confidencial.
	La información confidencial de EL CONTRATANTE no incluye aquella información que: a) Sea o llegue a ser del dominio público 
	sin que medie acto u omisión de la otra parte o de un tercero. b) estuviese en posesión legítima de EL CONTRATISTA con 
	anterioridad a la divulgación y no hubiese sido obtenida de forma directa o indirecta de la parte propietaria de esta 
	información. c) Sea legalmente divulgada por un tercero que no esté sujeto a restricciones en cuanto a su divulgación y 
	la haya obtenido de buena fe. d) Cuando la divulgación se produzca en cumplimiento de una orden de autoridad competente. 
	En este caso, si EL CONTRATISTA es requerido por alguna autoridad judicial o administrativa, deberá notificar previamente 
	tal circunstancia a EL CONTRATANTE.
	EL CONTRATISTA no podrá sin la previa autorización de EL CONTRATANTE realizar cualquiera de las siguientes conductas: a) 
	Hacer pública o divulgar a cualquier persona no autorizada la información confidencial de EL CONTRATANTE. Se exceptuará 
	esta prohibición en aquellos casos en los que EL CONTRATANTE autorice la subcontratación con respecto a la información que 
	deba ser entregada a cualquier persona que necesariamente deba conocerla, caso en el cual estas personas deberán suscribir 
	acuerdos de confidencialidad. b) Realizará reproducción o modificación de la información confidencial. c) Utilizar la 
	información confidencial para cualquier otro fin distinto a la ejecución del presente Contrato. EL CONTRATISTA se 
	compromete a adoptar todas las medidas razonablemente necesarias para garantizar que la información confidencial no sea 
	revelada o divulgada por él o por sus empleados o subcontratistas. Esta obligación se entiende que aplica con respecto 
	a toda información entregada o dada a conocer a EL CONTRATISTA con anterioridad a la suscripción del contrato y permanecerá 
	vigente a la terminación del presente contrato siempre que la información siga teniendo el carácter de confidencial. 
	Asimismo, EL CONTRATISTA, se obliga a guardar absoluta reserva de los resultados parciales o totales obtenidos en toda la 
	ejecución del presente contrato y aún después de su terminación. Sin perjuicio de lo anterior, EL CONTRATISTA deberá a la 
	terminación del presente contrato (o antes por solicitud de EL CONTRATANTE), devolver cualquier información entregada o 
	dada a conocer o en su defecto, por solicitud de EL CONTRATANTE, procederá a su destrucción o eliminación por un medio 
	seguro que impida su acceso por terceros no autorizados.
	<br>NOVENA. PROTECCIÓN Y TRATAMIENTO DE DATOS PERSONALES. EL CONTRATISTA asume la obligación constitucional, legal y 
	reglamentaria de proteger los datos personales a los que acceda con ocasión al contrato. Por tanto, deberá adoptar las 
	medidas que le permita dar cumplimiento a lo dispuesto por la normatividad vigente en la materia y las políticas sobre el 
	tratamiento de datos personales emitidas por EL CONTRATANTE. 
	Adicionalmente, EL CONTRATISTA se obliga a limitar el tratamiento de los datos personales de terceros que le sean 
	entregados por EL CONTRATANTE a la finalidad propia de sus obligaciones, garantizando los derechos de la privacidad, la 
	intimidad y el buen nombre, en el tratamiento de los datos personales y a informar a EL CONTRATANTE de cualquier sospecha 
	de pérdida, fuga o ataque contra la información personal a la que ha accedido. </div>';

	return $plantilla2;
}

function page02Former($beginDate, $endDate, $valContract)
{
	/* Calculo los parametros del contrato */
	$select = "id_subject,typedoc_subject,document_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,id_department_subject,name_department,id_municipality_subject,name_municipality,address_subject,email_subject,phone_subject,id_place_subject,name_place";
	$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . "&linkTo=id_subject&equalTo=" . $idSubject;
	$method = "GET";
	$fields = array();
	$subjects = CurlController::request($url, $method, $fields)->results[0];


	/* Variables de Impresión*/
	$ideEmpleado = (int) $subjects->document_subject;
	$ideEmpleado = number_format($ideEmpleado, 0, ",", ".");
	$fecini = new DateTime($beginDate);
	$fecfin = new DateTime($endDate);

	$intervalo = $fecini->diff($fecfin);
	$numMeses = $intervalo->days / 30;
	$numMeses = (int) $numMeses;
	$diaDifer = $intervalo->days - ($numMeses * 30);
	$valDay = round(intval($valContract) / $intervalo->days, 2);
	$valMes = round($valDay * 30, 0);
	$valAdi = round($valDay * $diaDifer, 0);
	$letContract = TemplateController::MontoMonetarioEnLetras($valContract);
	$valLetras = TemplateController::MontoMonetarioEnLetras($valMes);
	$diaLetras = TemplateController::MontoMonetarioEnLetras($valAdi);
	$mesLetras = explode(" ", TemplateController::MontoMonetarioEnLetras($numMeses));
	$mesLetras = $mesLetras[0];

	$plantilla2 = '<div class="row mt-3"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
	 resultados, ajustes de la 
		planificación sobre los resultados obtenidos y todas aquellas demás que EL CONTRATISTA considere necesarias, de 
		acuerdo con los datos obtenidos.
		6.	Participar y apoyar, activamente de los talleres y procesos psicosociales, programados para padres de familia, 
		NNA beneficiarios y comunidad, en el marco del programa de Jornada Deportiva Escolar Complementaria.
		7.	Los demás entregables que sean requeridos con relación a sus obligaciones específicas, actividades según la 
		naturaleza de su objeto contractual.
		</div></div>
		<br><div style="text-align: center;"><span class="centrar-titulo">ACTIVIDADES ESPECIFICAS.</span></div><br>
		<div class="row"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
		1.	Presentar la planeación pedagógica y por sesiones de clase, con sus respectivos objetivos, sesiones, temas, 
		contenidos, materiales, evaluación y retroalimentación, según las orientaciones del programa Jornada Deportiva 
		Escolar Complementaria, elaborando una planeación mensual y por sesión con sus respectivas fechas y horarios, los 
		cuales deben ser entregados a los coordinadores según el cronograma establecido.
		2.	Apoyar técnica, administrativa y operativamente la socialización del programa Jornada Deportiva Escolar 
		Complementaria en los municipios y las Instituciones Educativas focalizadas de manera presencial.
		3.	Diligenciar y sistematizar los formatos establecidos por el programa de Jornada Deportiva Escolar 
		Complementaria: i) formatos de inscripción de los beneficiarios, ii) consentimiento y asentimiento informado de 
		padres de familia, iii) planeación unidades didácticas, iv) formatos de planeación de sesiones de las unidades 
		didácticas, v) asistencia a las sesiones entre otros, que se requieran durante la ejecución del programa, en medio 
		físico y magnético, con el total de la información diligenciada. La entrega se realizará según el cronograma que 
		se establezca.
		4.	Coordinar y realizar la inscripción de los cinco (5) grupos de acuerdo a la caracterización, en la Institución 
		Educativa de veinte (20) beneficiarios entre los 3 a 17 años divididos en grupos etarios de (3 a 5), (6 - 9), 
		(10 - 12), (13 - 15) y (16 - 17) para un total de (100) cien niños, niñas y adolescentes (NNA), quienes recibirán 
		dos (2) sesiones semanales de dos (2) horas (120 minutos), por grupo en cada sesión.
		5.	Asistir, participar en el proceso de capacitación en los aspectos metodológicos, pedagógicos, administrativos 
		psicosociales y de enfoque de derechos, territoriales e inclusivos, del programa de Jornada Deportiva Escolar 
		Complementaria, organizado por EL CONTRATANTE, SERVICIOS POSTALES NACIONALES S.A.S. y el Ministerio del Deporte.
		6.	Aplicar las evaluaciones de entrada y salida a los participantes de la Jornada Deportiva Escolar Complementaria, 
		a través de los test y pruebas orientadas, donde se evidencien las debilidades y fortalezas en los componentes de 
		desarrollo físico, motriz, social, cultural, de medio ambiente, hábitos y estilos de vida saludable, la promoción y 
		práctica de los principios, derechos y deberes de niños, niñas y adolescentes, de acuerdo con los lineamientos 
		orientados por EL CONTRATANTE, SERVICIOS POSTALES NACIONALES S.A.S. y el Ministerio del Deporte.
		7.	Planear y ejecutar el Festival Deportivo y Taller Psicosocial, en conjunto con el Coordinador, Profesional 
		Psicosocial y en alianza con el Instituto Municipal y/o Departamental de Deportes teniendo en cuenta actividades 
		lúdicas, recreativas, motrices, culturales y específicas del deporte, según el cronograma definido por EL CONTRATANTE 
		y SERVICIOS POSTALES NACIONALES S.A.S. y de acuerdo con las orientaciones del Ministerio del Deporte.
		8.	Entregar mensualmente informe de actividades e informe técnico del centro de interés deportivo que se genere 
		como producto del programa Jornada Deportiva Escolar Complementaria y enviarlos al Coordinador para revisión y 
		aprobación. Así mismo, el informe final de actividades de acuerdo con los lineamientos dados por el Ministerio del 
		Deporte.
		9.	Las demás actividades que le sean asignadas y que guarden relación con el objeto contractual.

		<div class="row"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
		<br>PARÁGRAFO. LUGAR DE EJECUCIÓN: Se establece como lugar de ejecución de las actividades del presente contrato en el 
		departamento ' . $subjects->name_department . '.
		<br>TERCERA. PRECIO Y FORMA DE PAGO. Por la prestación de los servicios, EL CONTRATANTE pagará a EL CONTRATISTA la suma 
		total de ' . $letContract . ' M/CTE ($ ' . number_format($valContract, 2) . '). La forma de pago será la siguiente: ' .
		$mesLetras . ' pagos mensuales iguales equivalentes a ' . $valLetras . ' M/CTE ($ ' . number_format($valMes, 2) . ') al cumplirse el día 
		treinta (30) de cada mes previo cumplimento de requisitos y entrega de soportes de la ejecución de las actividades 
		contratadas, y un último pago equivalente a ' . $diaLetras . ' M/CTE ($ ' . number_format($valAdi, 2) . '), una vez EL CONTRATISTA, 
		cumpla con la totalidad de actividades generales y 	especificas relacionadas en el presente contrato y suministre
		 la información en los formatos establecidos.

	PARÁGRAFO PRIMERO. Serán requisitos indispensables para el pago que EL CONTRATISTA presente:

		a)	Cuenta de cobro;
		b)	copia del RUT;
		c)	Planilla integrada de liquidación de aportes (PILA) que acredite el pago al Sistema General de Seguridad Social 
		Integral.

	CUARTA. PLAZO. Este contrato tendrá una duración comprendida entre el ' . $beginDate . ' hasta el ' . $endDate . '. No 
	obstante, el contrato podrá ser terminado de forma anticipada por parte de EL CONTRATANTE, en cualquier momento previa 
	comunicación escrita, caso en el cual se reconocerán solamente los honorarios equivalentes a las actividades y productos 
	efectivamente entregados a la fecha.
	PARÁGRAFO PRIMERO: En caso de terminación anticipada del contrato, EL CONTRATISTA deberá entregar todos los documentos y 
	demás resultados producto de la ejecución contractual realizados hasta la fecha y elementos entregados al contratista 
	para el desarrollo del objeto contractual. 
	PARÁGRAFO SEGUNDO: EL CONTRATANTE podrá suspender el presente contrato en cualquier momento previa comunicación escrita, 
	lo cual se formalizará en un acta suscrita por las partes en la cual se indicará la duración de la suspensión. Si cumplido 
	el plazo de la suspensión no es posible reanudar las actividades, EL CONTRATANTE, a su arbitrio, podrá dar por terminado 
	el contrato de forma unilateral sin que haya lugar a indemnizaciones a cualquier título.
	QUINTA. OBLIGACIONES DEL CONTRATISTA. a) Cumplir en forma oportuna el objeto y las actividades acordadas. b) Aportar su 
	experiencia y los conocimientos necesarios para la adecuada ejecución del contrato. c) Absolver las consultas que EL 
	CONTRATANTE le solicite, relacionadas con el objeto del contrato. d) Asistir a las reuniones en los días y horas 
	previamente acordados por EL CONTRATANTE. e) Tener vigente al momento de prestar sus servicios profesionales la afiliación 
	al Sistema General de Seguridad Social. f) Garantizar la mejor calidad con respecto a la ejecución de las actividades y 
	entregables señalados en la cláusula segunda. En tal sentido, deberá subsanar inmediatamente cualquier incumplimiento 
	total o parcial identificado por EL CONTRATANTE y en caso de no recibirlos a completa satisfacción, EL CONTRATANTE se 
	reserva el derecho de contratar con un tercero idóneo la terminación de las actividades y entregables, caso en el cual 
	EL CONTRATISTA asumirá, a título de perjuicios, el valor de los honorarios facturados por el tercero; para lo anterior, 
	autoriza desde ya a EL CONTRATANTE para deducir tales sumas de dinero de los honorarios que pueda adeudarle. g) Cumplir 
	las políticas y reglamentos vigentes de EL CONTRATANTE en lo que resulte pertinente. h) Suministrar por su cuenta y riesgo, 
	el transporte necesario para desplazarse a los lugares en donde deban realizarse las actividades objeto de este contrato. 
	i) Todas las demás obligaciones que se derivan de la naturaleza de este contrato.
	SEXTA. OBLIGACIONES DE EL CONTRATANTE. a) Pagar el valor establecido en la cláusula tercera. b) Facilitar a EL CONTRATISTA 
	el acceso a la información que sea necesaria, de manera oportuna y prestar el apoyo requerido para la debida ejecución del 
	objeto del contrato. c) Cumplir con lo estipulado en las demás cláusulas y condiciones previstas en este contrato y demás 
	anexos.
	PARÁGRAFO PRIMERO. Durante la ejecución del contrato EL CONTRATANTE no asume ninguna obligación de custodia o seguridad en 
	relación con la integridad física del personal o los bienes materiales de EL CONTRATISTA.
	SÉPTIMA. EXCLUSIÓN DE LA RELACIÓN LABORAL. Dada la naturaleza de este contrato, no existirá relación laboral alguna entre 
	EL CONTRATANTE y EL CONTRATISTA, o el personal que éste contrate para apoyar la ejecución del objeto contractual. EL 
	CONTRATISTA se compromete con EL CONTRATANTE a ejecutar en forma independiente y con plena autonomía técnica y 
	administrativa, el objeto mencionado en la cláusula primera de este documento.
	OCTAVA. CLÁUSULA DE CONFIDENCIALIDAD. EL CONTRATISTA deberá mantener la confidencialidad sobre toda la información de EL 
	CONTRATANTE que conozca o a la que tenga acceso con ocasión del presente del Contrato con independencia del medio en cual 
	se encuentre soportada. Se tendrá como información confidencial cualquier información no divulgada que posea legítimamente 
	EL CONTRATANTE que pueda usarse en alguna actividad académica, productiva, industrial o comercial y que sea susceptible 
	de comunicarse a un tercero. Sin fines restrictivos la información confidencial podrá versar sobre invenciones, modelos 
	de utilidad, programas de software, fórmulas, métodos, know-how, procesos, diseños, nuevos productos, trabajos en 
	desarrollo, requisitos de comercialización, planes de mercadeo, nombres de clientes y proveedores existentes y potenciales, 
	así como toda otra información que se identifique como confidencial a EL CONTRATISTA. La información confidencial incluye 
	también toda información recibida de terceros que EL CONTRATISTA está obligado a tratar como confidencial, así como las 
	informaciones orales que EL CONTRATANTE identifique como confidencial.
	La información confidencial de EL CONTRATANTE no incluye aquella información que: a) Sea o llegue a ser del dominio público 
	sin que medie acto u omisión de la otra parte o de un tercero. b) estuviese en posesión legítima de EL CONTRATISTA con 
	anterioridad a la divulgación y no hubiese sido obtenida de forma directa o indirecta de la parte propietaria de esta 
	información. c) Sea legalmente divulgada por un tercero que no esté sujeto a restricciones en cuanto a su divulgación y 
	la haya obtenido de buena fe. d) Cuando la divulgación se produzca en cumplimiento de una orden de autoridad competente. 
	En este caso, si EL CONTRATISTA es requerido por alguna autoridad judicial o administrativa, deberá notificar previamente 
	tal circunstancia a EL CONTRATANTE.
	EL CONTRATISTA no podrá sin la previa autorización de EL CONTRATANTE realizar cualquiera de las siguientes conductas: a) 
	Hacer pública o divulgar a cualquier persona no autorizada la información confidencial de EL CONTRATANTE. Se exceptuará 
	esta prohibición en aquellos casos en los que EL CONTRATANTE autorice la subcontratación con respecto a la información que 
	deba ser entregada a cualquier persona que necesariamente deba conocerla, caso en el cual estas personas deberán suscribir 
	acuerdos de confidencialidad. b) Realizará reproducción o modificación de la información confidencial. c) Utilizar la 
	información confidencial para cualquier otro fin distinto a la ejecución del presente Contrato. EL CONTRATISTA se 
	compromete a adoptar todas las medidas razonablemente necesarias para garantizar que la información confidencial no sea 
	revelada o divulgada por él o por sus empleados o subcontratistas. Esta obligación se entiende que aplica con respecto 
	a toda información entregada o dada a conocer a EL CONTRATISTA con anterioridad a la suscripción del contrato y permanecerá 
	vigente a la terminación del presente contrato siempre que la información siga teniendo el carácter de confidencial. 
	Asimismo, EL CONTRATISTA, se obliga a guardar absoluta reserva de los resultados parciales o totales obtenidos en toda la 
	ejecución del presente contrato y aún después de su terminación. Sin perjuicio de lo anterior, EL CONTRATISTA deberá a la 
	terminación del presente contrato (o antes por solicitud de EL CONTRATANTE), devolver cualquier información entregada o 
	dada a conocer o en su defecto, por solicitud de EL CONTRATANTE, procederá a su destrucción o eliminación por un medio 
	seguro que impida su acceso por terceros no autorizados.
	NOVENA. PROTECCIÓN Y TRATAMIENTO DE DATOS PERSONALES. EL CONTRATISTA asume la obligación constitucional, legal y 
	reglamentaria de proteger los datos personales a los que acceda con ocasión al contrato. Por tanto, deberá adoptar las 
	medidas que le permita dar cumplimiento a lo dispuesto por la normatividad vigente en la materia y las políticas sobre el 
	tratamiento de datos personales emitidas por EL CONTRATANTE. 
	Adicionalmente, EL CONTRATISTA se obliga a limitar el tratamiento de los datos personales de terceros que le sean 
	entregados por EL CONTRATANTE a la finalidad propia de sus obligaciones, garantizando los derechos de la privacidad, la 
	intimidad y el buen nombre, en el tratamiento de los datos personales y a informar a EL CONTRATANTE de cualquier sospecha 
	de pérdida, fuga o ataque contra la información personal a la que ha accedido. </div>';

	return $plantilla2;
}

function page02Cord($idSubject, $beginDate, $endDate, $valContract)
{
	/* Calculo los parametros del contrato */
	$select = "id_subject,typedoc_subject,document_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,id_department_subject,name_department,id_municipality_subject,name_municipality,address_subject,email_subject,phone_subject,id_place_subject,name_place";
	$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . "&linkTo=id_subject&equalTo=" . $idSubject;
	$method = "GET";
	$fields = array();
	$subjects = CurlController::request($url, $method, $fields)->results[0];


	/* Variables de Impresión*/
	$ideEmpleado = (int) $subjects->document_subject;
	$ideEmpleado = number_format($ideEmpleado, 0, ",", ".");
	$fecini = new DateTime($beginDate);
	$fecfin = new DateTime($endDate);

	$intervalo = $fecini->diff($fecfin);
	$numMeses = $intervalo->days / 30;
	$numMeses = (int) $numMeses;
	$diaDifer = $intervalo->days - ($numMeses * 30);
	$valDay = round(intval($valContract) / $intervalo->days, 2);
	$valMes = round($valDay * 30, 0);
	$valAdi = round($valDay * $diaDifer, 0);
	$letContract = TemplateController::MontoMonetarioEnLetras($valContract);
	$valLetras = TemplateController::MontoMonetarioEnLetras($valMes);
	$diaLetras = TemplateController::MontoMonetarioEnLetras($valAdi);
	$mesLetras = explode(" ", TemplateController::MontoMonetarioEnLetras($numMeses));
	$mesLetras = $mesLetras[0];

	$plantilla = '<div class="row mt-3"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
	según el cronograma del programa.
	6.	Entregar mensualmente informe de actividades e informe técnico consolidado del departamento asignado como producto 
	de la implementación del programa de Jornada Deportiva Escolar Complementaria según los criterios definidos por EL 
	CONTRATANTE, acorde a las solicitudes realizadas por el Ministerio del Deporte. Asimismo, presentar el informe técnico 
	final del programa consolidado de acuerdo con los lineamientos dados por EL CONTRATANTE, acorde a los requerimientos 
	del Ministerio Del Deporte.
	7.	Asistir puntualmente a las reuniones y capacitaciones convocadas por EL CONTRATANTE, en acorde a las orientaciones 
	realizadas por el Ministerio del Deporte, así como a todas aquellas en las que se le requiera su presencia y 
	participación.
	8.	Las demás actividades que le sean asignadas y que guarden relación con el objeto contractual.
	</div></div>';

	$plantilla .= '<div class="row"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
	PARÁGRAFO. LUGAR DE EJECUCIÓN: Se establece como lugar de ejecución de las actividades del presente contrato en el 
	departamento ' . $subjects->name_department . '.
	TERCERA. PRECIO Y FORMA DE PAGO. Por la prestación de los servicios, EL CONTRATANTE pagará a EL CONTRATISTA la suma 
	total de ' . $letContract . ' M/CTE ($ ' . number_format($valContract, 2) . '). La forma de pago será la siguiente: ' .
		$mesLetras . ' pagos mensuales iguales equivalentes a ' . $valLetras . ' M/CTE ($ ' . number_format($valMes, 2) . ') al cumplirse el día 
	treinta (30) de cada mes previo cumplimento de requisitos y entrega de soportes de la ejecución de las actividades 
	contratadas, y un último pago equivalente a ' . $diaLetras . ' M/CTE ($ ' . number_format($valAdi, 2) . '), una vez EL CONTRATISTA, 
	cumpla con la totalidad de actividades generales y especificas relacionadas en el presente contrato y suministre la información 
	en los formatos establecidos.

	PARÁGRAFO PRIMERO. Serán requisitos indispensables para el pago que EL CONTRATISTA presente:

		a)	Cuenta de cobro;
		b)	copia del RUT;
		c)	Planilla integrada de liquidación de aportes (PILA) que acredite el pago al Sistema General de Seguridad Social 
		Integral.

	CUARTA. PLAZO. Este contrato tendrá una duración comprendida entre el ' . $beginDate . ' hasta el ' . $endDate . '. No 
	obstante, el contrato podrá ser terminado de forma anticipada por parte de EL CONTRATANTE, en cualquier momento previa 
	comunicación escrita, caso en el cual se reconocerán solamente los honorarios equivalentes a las actividades y productos 
	efectivamente entregados a la fecha.
	PARÁGRAFO PRIMERO: En caso de terminación anticipada del contrato, EL CONTRATISTA deberá entregar todos los documentos y 
	demás resultados producto de la ejecución contractual realizados hasta la fecha y elementos entregados al contratista 
	para el desarrollo del objeto contractual. 
	PARÁGRAFO SEGUNDO: EL CONTRATANTE podrá suspender el presente contrato en cualquier momento previa comunicación escrita, 
	lo cual se formalizará en un acta suscrita por las partes en la cual se indicará la duración de la suspensión. Si cumplido 
	el plazo de la suspensión no es posible reanudar las actividades, EL CONTRATANTE, a su arbitrio, podrá dar por terminado 
	el contrato de forma unilateral sin que haya lugar a indemnizaciones a cualquier título.
	QUINTA. OBLIGACIONES DEL CONTRATISTA. a) Cumplir en forma oportuna el objeto y las actividades acordadas. b) Aportar su 
	experiencia y los conocimientos necesarios para la adecuada ejecución del contrato. c) Absolver las consultas que EL 
	CONTRATANTE le solicite, relacionadas con el objeto del contrato. d) Asistir a las reuniones en los días y horas 
	previamente acordados por EL CONTRATANTE. e) Tener vigente al momento de prestar sus servicios profesionales la afiliación 
	al Sistema General de Seguridad Social. f) Garantizar la mejor calidad con respecto a la ejecución de las actividades y 
	entregables señalados en la cláusula segunda. En tal sentido, deberá subsanar inmediatamente cualquier incumplimiento 
	total o parcial identificado por EL CONTRATANTE y en caso de no recibirlos a completa satisfacción, EL CONTRATANTE se 
	reserva el derecho de contratar con un tercero idóneo la terminación de las actividades y entregables, caso en el cual 
	EL CONTRATISTA asumirá, a título de perjuicios, el valor de los honorarios facturados por el tercero; para lo anterior, 
	autoriza desde ya a EL CONTRATANTE para deducir tales sumas de dinero de los honorarios que pueda adeudarle. g) Cumplir 
	las políticas y reglamentos vigentes de EL CONTRATANTE en lo que resulte pertinente. h) Suministrar por su cuenta y riesgo, 
	el transporte necesario para desplazarse a los lugares en donde deban realizarse las actividades objeto de este contrato. 
	i) Todas las demás obligaciones que se derivan de la naturaleza de este contrato.
	SEXTA. OBLIGACIONES DE EL CONTRATANTE. a) Pagar el valor establecido en la cláusula tercera. b) Facilitar a EL CONTRATISTA 
	el acceso a la información que sea necesaria, de manera oportuna y prestar el apoyo requerido para la debida ejecución del 
	objeto del contrato. c) Cumplir con lo estipulado en las demás cláusulas y condiciones previstas en este contrato y demás 
	anexos.
	PARÁGRAFO PRIMERO. Durante la ejecución del contrato EL CONTRATANTE no asume ninguna obligación de custodia o seguridad en 
	relación con la integridad física del personal o los bienes materiales de EL CONTRATISTA.
	SÉPTIMA. EXCLUSIÓN DE LA RELACIÓN LABORAL. Dada la naturaleza de este contrato, no existirá relación laboral alguna entre 
	EL CONTRATANTE y EL CONTRATISTA, o el personal que éste contrate para apoyar la ejecución del objeto contractual. EL 
	CONTRATISTA se compromete con EL CONTRATANTE a ejecutar en forma independiente y con plena autonomía técnica y 
	administrativa, el objeto mencionado en la cláusula primera de este documento.
	OCTAVA. CLÁUSULA DE CONFIDENCIALIDAD. EL CONTRATISTA deberá mantener la confidencialidad sobre toda la información de EL 
	CONTRATANTE que conozca o a la que tenga acceso con ocasión del presente del Contrato con independencia del medio en cual 
	se encuentre soportada. Se tendrá como información confidencial cualquier información no divulgada que posea legítimamente 
	EL CONTRATANTE que pueda usarse en alguna actividad académica, productiva, industrial o comercial y que sea susceptible 
	de comunicarse a un tercero. Sin fines restrictivos la información confidencial podrá versar sobre invenciones, modelos 
	de utilidad, programas de software, fórmulas, métodos, know-how, procesos, diseños, nuevos productos, trabajos en 
	desarrollo, requisitos de comercialización, planes de mercadeo, nombres de clientes y proveedores existentes y potenciales, 
	así como toda otra información que se identifique como confidencial a EL CONTRATISTA. La información confidencial incluye 
	también toda información recibida de terceros que EL CONTRATISTA está obligado a tratar como confidencial, así como las 
	informaciones orales que EL CONTRATANTE identifique como confidencial.
	La información confidencial de EL CONTRATANTE no incluye aquella información que: a) Sea o llegue a ser del dominio público 
	sin que medie acto u omisión de la otra parte o de un tercero. b) estuviese en posesión legítima de EL CONTRATISTA con 
	anterioridad a la divulgación y no hubiese sido obtenida de forma directa o indirecta de la parte propietaria de esta 
	información. c) Sea legalmente divulgada por un tercero que no esté sujeto a restricciones en cuanto a su divulgación y 
	la haya obtenido de buena fe. d) Cuando la divulgación se produzca en cumplimiento de una orden de autoridad competente. 
	En este caso, si EL CONTRATISTA es requerido por alguna autoridad judicial o administrativa, deberá notificar previamente 
	tal circunstancia a EL CONTRATANTE.
	EL CONTRATISTA no podrá sin la previa autorización de EL CONTRATANTE realizar cualquiera de las siguientes conductas: a) 
	Hacer pública o divulgar a cualquier persona no autorizada la información confidencial de EL CONTRATANTE. Se exceptuará 
	esta prohibición en aquellos casos en los que EL CONTRATANTE autorice la subcontratación con respecto a la información que 
	deba ser entregada a cualquier persona que necesariamente deba conocerla, caso en el cual estas personas deberán suscribir 
	acuerdos de confidencialidad. b) Realizará reproducción o modificación de la información confidencial. c) Utilizar la 
	información confidencial para cualquier otro fin distinto a la ejecución  del  presente  Contrato. EL  CONTRATISTA  se 
	compromete  a  adoptar  todas  las  medidas  razonablemente necesarias</div>';

	return $plantilla;
}

function page03Psico($nomEmpleado, $ideEmpleado)
{
	$numero_mes = date('m'); // Obtener el número del mes actual
	$fecha = DateTime::createFromFormat('!m', $numero_mes);
	// Obtener el nombre del mes en letras
	$mes_nombre = $fecha->format('F'); // 'F' devuelve el nombre completo del mes

	$plantilla3 = '<div class="row mt-3"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
	<br>PARÁGRAFO PRIMERO. EL CONTRATISTA autoriza a EL CONTRATANTE, en su condición de responsable del tratamiento de información 
	y a las personas aturales y jurídicas que detenten la calidad de encargados del tratamiento de información, para efectuar 
	el tratamiento de sus datos personales, lo cual incluye la captura, recolección, recaudo, almacenamiento, actualización, 
	uso, circulación, procesamiento, transmisión, transferencia, disposición y supresión de los mismos, para los siguientes 
	fines: a) Para dar cumplimiento a las obligaciones de su actividad como contratante y verificar el cumplimiento de las 
	actividades de EL CONTRATISTA; b) Para la expedición de certificados solicitados por EL CONTRATISTA; c) Para dar 
	cumplimiento a las obligaciones contraídas por EL CONTRATANTE con autoridades públicas, contratantes, clientes, 
	proveedores y empleados; d) Para el envío de información institucional a EL CONTRATISTA a través de los diferentes medios 
	de comunicación de la EL CONTRATANTE: correo electrónico, intranet, correspondencia física a la oficina y/o domicilio, 
	entre otros medios.  
	<br>PARÁGRAFO SEGUNDO. EL CONTRATISTA certifica que los datos personales suministrados a EL CONTRATANTE son veraces, completos, 
	exactos, actualizados, reales y comprobables. Por tanto, cualquier error en la información suministrada será de su 
	exclusiva responsabilidad, lo que exonera a EL CONTRATANTE, en calidad de responsable y a sus aliados que actúen como 
	encargados, de cualquier responsabilidad ante las autoridades judiciales y/o administrativas, en especial ante la autoridad 
	de protección de datos personales.
	<br>DÉCIMA. CLAUSULA PENAL. En caso en que EL CONTRATISTA incumpla cualquiera de las obligaciones aquí contraídas, pagará a EL 
	CONTRATANTE a título de cláusula penal el veinte por ciento (20%) del valor total del presente contrato, sin perjuicio de 
	las acciones que EL CONTRATANTE pueda intentar judicial o extrajudicialmente el cobro de los perjuicios causados, para lo 
	cual el presente contrato junto con la afirmación de EL CONTRATANTE sobre el incumplimiento de EL CONTRATISTA, prestará 
	mérito ejecutivo, renunciando a ser constituido en mora. 
	<br>DÉCIMA PRIMERA. PREVENCIÓN DE LAVADO DE ACTIVOS: EL CONTRATANTE podrá terminar de manera unilateral e inmediata el presente 
	contrato, en caso de que EL CONTRATISTA llegare a ser: a) incluido en las listas para el control de lavado de activos y 
	financiación del terrorismo administradas por cualquier autoridad nacional o extranjera, tales como la lista de la Oficina 
	de Control de Activos en el Exterior - OFAC emitida por la Oficina del Tesoro de los Estados Unidos de Norte América, la 
	lista de la Organización de las Naciones Unidas, así como cualquier otra lista pública relacionada con el tema de lavado 
	de activos y financiación del terrorismo, o a) Condenado por parte de las autoridades competentes en cualquier tipo de 
	proceso judicial relacionado con la comisión de los anteriores delitos. En este sentido, EL CONTRATISTA autoriza 
	irrevocablemente a EL CONTRATANTE para que consulte tal información en dichas listas y/o listas similares. EL CONTRATANTE 
	declara bajo la gravedad de juramento que los recursos, fondos, dineros, activos o bienes relacionados con este contrato, 
	son de procedencia lícita y no están vinculados con el lavado de activos ni con ninguno de sus delitos fuente, así como que 
	el destino de los recursos, fondos, dineros, activos o bienes producto de los mismos no van a ser destinados para la 
	financiación del terrorismo o cualquier otra conducta delictiva, de acuerdo con las normas penales y las que sean 
	aplicables en Colombia, sin perjuicio de las acciones legales pertinentes derivadas del incumplimiento de esta declaración. 
	<br>DÉCIMA SEGUNDA. CESIÓN. EL CONTRATISTA no podrá ceder total ni parcialmente, así como subcontratar, la ejecución del 
	presente contrato, salvo previa autorización expresa y escrita de EL CONTRATANTE.
	<br>DÉCIMA TERCERA. TERMINACIÓN. El presente contrato podrá terminar por alguno de los siguientes eventos: a) Vencimiento del 
	plazo sin que las partes, por mutuo acuerdo y por escrito, manifiesten su intención de prorrogarlo; b) Decisión unilateral 
	de EL CONTRATANTE; c) Por mutuo acuerdo entre las partes, lo cual deberá constar en acta de terminación; d) Por 
	incumplimiento de EL CONTRATISTA de alguna de las obligaciones. e) En cumplimiento de lo prescrito en el parágrafo segundo 
	de la cláusula cuarta del presente contrato.
	<br>DÉCIMA CUARTA. DOMICILIO CONTRACTUAL. Para todos los efectos legales, el domicilio contractual será la ciudad de Santa Marta.
	<br>DÉCIMA QUINTA. IMPUESTOS. Si fuere el caso, EL CONTRATANTE deducirá de los honorarios a pagar los valores de los impuestos 
	a que haya lugar de conformidad con lo decretado por la autoridad competente. 
	<br>DÉCIMA SEXTA. MODIFICACIONES. Cualquier modificación a los términos y condiciones del presente contrato deberá ser 
	acordada entre las partes y requerirá de un “otrosí” firmado por ellas. 
	<br>DECIMO SEPTIMA. ACUERDO. El presente contrato reemplazará en su integridad y deja sin efecto alguno, cualquier otro acuerdo 
	verbal o escrito celebrado con anterioridad entre las partes sobre el mismo objeto.
	<br><br>Para constancia se firma en dos ejemplares, a los ' . date("d") . ' dias del mes ' . changeLetterMonth(date('m')) . ' del año ' .
		date("Y") . '.<br><br><br><br></div></div>';

	return $plantilla3;
}

function page03Former($nomEmpleado, $ideEmpleado)
{
	$numero_mes = date('m'); // Obtener el número del mes actual
	$fecha = DateTime::createFromFormat('!m', $numero_mes);
	// Obtener el nombre del mes en letras
	$mes_nombre = $fecha->format('F'); // 'F' devuelve el nombre completo del mes

	$plantilla3 = '<div class="row mt-3"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
	PARÁGRAFO PRIMERO. EL CONTRATISTA autoriza a EL CONTRATANTE, en su condición de responsable del tratamiento de información 
	y a las personas aturales y jurídicas que detenten la calidad de encargados del tratamiento de información, para efectuar 
	el tratamiento de sus datos personales, lo cual incluye la captura, recolección, recaudo, almacenamiento, actualización, 
	uso, circulación, procesamiento, transmisión, transferencia, disposición y supresión de los mismos, para los siguientes 
	fines: a) Para dar cumplimiento a las obligaciones de su actividad como contratante y verificar el cumplimiento de las 
	actividades de EL CONTRATISTA; b) Para la expedición de certificados solicitados por EL CONTRATISTA; c) Para dar 
	cumplimiento a las obligaciones contraídas por EL CONTRATANTE con autoridades públicas, contratantes, clientes, 
	proveedores y empleados; d) Para el envío de información institucional a EL CONTRATISTA a través de los diferentes medios 
	de comunicación de la EL CONTRATANTE: correo electrónico, intranet, correspondencia física a la oficina y/o domicilio, 
	entre otros medios.  
	PARÁGRAFO SEGUNDO. EL CONTRATISTA certifica que los datos personales suministrados a EL CONTRATANTE son veraces, completos, 
	exactos, actualizados, reales y comprobables. Por tanto, cualquier error en la información suministrada será de su 
	exclusiva responsabilidad, lo que exonera a EL CONTRATANTE, en calidad de responsable y a sus aliados que actúen como 
	encargados, de cualquier responsabilidad ante las autoridades judiciales y/o administrativas, en especial ante la autoridad 
	de protección de datos personales.
	DÉCIMA. CLAUSULA PENAL. En caso en que EL CONTRATISTA incumpla cualquiera de las obligaciones aquí contraídas, pagará a EL 
	CONTRATANTE a título de cláusula penal el veinte por ciento (20%) del valor total del presente contrato, sin perjuicio de 
	las acciones que EL CONTRATANTE pueda intentar judicial o extrajudicialmente el cobro de los perjuicios causados, para lo 
	cual el presente contrato junto con la afirmación de EL CONTRATANTE sobre el incumplimiento de EL CONTRATISTA, prestará 
	mérito ejecutivo, renunciando a ser constituido en mora. 
	DÉCIMA PRIMERA. PREVENCIÓN DE LAVADO DE ACTIVOS: EL CONTRATANTE podrá terminar de manera unilateral e inmediata el presente 
	contrato, en caso de que EL CONTRATISTA llegare a ser: a) incluido en las listas para el control de lavado de activos y 
	financiación del terrorismo administradas por cualquier autoridad nacional o extranjera, tales como la lista de la Oficina 
	de Control de Activos en el Exterior - OFAC emitida por la Oficina del Tesoro de los Estados Unidos de Norte América, la 
	lista de la Organización de las Naciones Unidas, así como cualquier otra lista pública relacionada con el tema de lavado 
	de activos y financiación del terrorismo, o a) Condenado por parte de las autoridades competentes en cualquier tipo de 
	proceso judicial relacionado con la comisión de los anteriores delitos. En este sentido, EL CONTRATISTA autoriza 
	irrevocablemente a EL CONTRATANTE para que consulte tal información en dichas listas y/o listas similares. EL CONTRATANTE 
	declara bajo la gravedad de juramento que los recursos, fondos, dineros, activos o bienes relacionados con este contrato, 
	son de procedencia lícita y no están vinculados con el lavado de activos ni con ninguno de sus delitos fuente, así como que 
	el destino de los recursos, fondos, dineros, activos o bienes producto de los mismos no van a ser destinados para la 
	financiación del terrorismo o cualquier otra conducta delictiva, de acuerdo con las normas penales y las que sean 
	aplicables en Colombia, sin perjuicio de las acciones legales pertinentes derivadas del incumplimiento de esta declaración. 
	DÉCIMA SEGUNDA. CESIÓN. EL CONTRATISTA no podrá ceder total ni parcialmente, así como subcontratar, la ejecución del 
	presente contrato, salvo previa autorización expresa y escrita de EL CONTRATANTE.
	DÉCIMA TERCERA. TERMINACIÓN. El presente contrato podrá terminar por alguno de los siguientes eventos: a) Vencimiento del 
	plazo sin que las partes, por mutuo acuerdo y por escrito, manifiesten su intención de prorrogarlo; b) Decisión unilateral 
	de EL CONTRATANTE; c) Por mutuo acuerdo entre las partes, lo cual deberá constar en acta de terminación; d) Por 
	incumplimiento de EL CONTRATISTA de alguna de las obligaciones. e) En cumplimiento de lo prescrito en el parágrafo segundo 
	de la cláusula cuarta del presente contrato.
	DÉCIMA CUARTA. DOMICILIO CONTRACTUAL. Para todos los efectos legales, el domicilio contractual será la ciudad de Santa Marta.
	DÉCIMA QUINTA. IMPUESTOS. Si fuere el caso, EL CONTRATANTE deducirá de los honorarios a pagar los valores de los impuestos 
	a que haya lugar de conformidad con lo decretado por la autoridad competente. 
	DÉCIMA SEPTIMA. MODIFICACIONES. Cualquier modificación a los términos y condiciones del presente contrato deberá ser 
	acordada entre las partes y requerirá de un “otrosí” firmado por ellas. 
	DECIMO OCTAVA. ACUERDO. El presente contrato reemplazará en su integridad y deja sin efecto alguno, cualquier otro acuerdo 
	verbal o escrito celebrado con anterioridad entre las partes sobre el mismo objeto.
	<br><br>Para constancia se firma en dos ejemplares, a los ' . date("d") . ' dias del mes ' . changeLetterMonth(date('m')) . ' del año ' .
		date("Y") . '.<br><br><br><br></div></div>';

	return $plantilla3;
}

function page03Cord($nomEmpleado, $ideEmpleado)
{

	$plantilla3 = '<div class="row mt-3"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
	para garantizar que la información confidencial no sea 
	revelada o divulgada por él o por sus empleados o subcontratistas. Esta obligación se entiende que aplica con respecto 
	a toda información entregada o dada a conocer a EL CONTRATISTA con anterioridad a la suscripción del contrato y permanecerá 
	vigente a la terminación del presente contrato siempre que la información siga teniendo el carácter de confidencial. 
	Asimismo, EL CONTRATISTA, se obliga a guardar absoluta reserva de los resultados parciales o totales obtenidos en toda la 
	ejecución del presente contrato y aún después de su terminación. Sin perjuicio de lo anterior, EL CONTRATISTA deberá a la 
	terminación del presente contrato (o antes por solicitud de EL CONTRATANTE), devolver cualquier información entregada o 
	dada a conocer o en su defecto, por solicitud de EL CONTRATANTE, procederá a su destrucción o eliminación por un medio 
	seguro que impida su acceso por terceros no autorizados.
	NOVENA. PROTECCIÓN Y TRATAMIENTO DE DATOS PERSONALES. EL CONTRATISTA asume la obligación constitucional, legal y 
	reglamentaria de proteger los datos personales a los que acceda con ocasión al contrato. Por tanto, deberá adoptar las 
	medidas que le permita dar cumplimiento a lo dispuesto por la normatividad vigente en la materia y las políticas sobre el 
	tratamiento de datos personales emitidas por EL CONTRATANTE. 
	Adicionalmente, EL CONTRATISTA se obliga a limitar el tratamiento de los datos personales de terceros que le sean 
	entregados por EL CONTRATANTE a la finalidad propia de sus obligaciones, garantizando los derechos de la privacidad, la 
	intimidad y el buen nombre, en el tratamiento de los datos personales y a informar a EL CONTRATANTE de cualquier sospecha 
	de pérdida, fuga o ataque contra la información personal a la que ha accedido. 
	PARÁGRAFO PRIMERO. EL CONTRATISTA autoriza a EL CONTRATANTE, en su condición de responsable del tratamiento de información 
	y a las personas aturales y jurídicas que detenten la calidad de encargados del tratamiento de información, para efectuar 
	el tratamiento de sus datos personales, lo cual incluye la captura, recolección, recaudo, almacenamiento, actualización, 
	uso, circulación, procesamiento, transmisión, transferencia, disposición y supresión de los mismos, para los siguientes 
	fines: a) Para dar cumplimiento a las obligaciones de su actividad como contratante y verificar el cumplimiento de las 
	actividades de EL CONTRATISTA; b) Para la expedición de certificados solicitados por EL CONTRATISTA; c) Para dar 
	cumplimiento a las obligaciones contraídas por EL CONTRATANTE con autoridades públicas, contratantes, clientes, 
	proveedores y empleados; d) Para el envío de información institucional a EL CONTRATISTA a través de los diferentes medios 
	de comunicación de la EL CONTRATANTE: correo electrónico, intranet, correspondencia física a la oficina y/o domicilio, 
	entre otros medios.  
	PARÁGRAFO SEGUNDO. EL CONTRATISTA certifica que los datos personales suministrados a EL CONTRATANTE son veraces, completos, 
	exactos, actualizados, reales y comprobables. Por tanto, cualquier error en la información suministrada será de su 
	exclusiva responsabilidad, lo que exonera a EL CONTRATANTE, en calidad de responsable y a sus aliados que actúen como 
	encargados, de cualquier responsabilidad ante las autoridades judiciales y/o administrativas, en especial ante la autoridad 
	de protección de datos personales.
	DÉCIMA. CLAUSULA PENAL. En caso en que EL CONTRATISTA incumpla cualquiera de las obligaciones aquí contraídas, pagará a EL 
	CONTRATANTE a título de cláusula penal el veinte por ciento (20%) del valor total del presente contrato, sin perjuicio de 
	las acciones que EL CONTRATANTE pueda intentar judicial o extrajudicialmente el cobro de los perjuicios causados, para lo 
	cual el presente contrato junto con la afirmación de EL CONTRATANTE sobre el incumplimiento de EL CONTRATISTA, prestará 
	mérito ejecutivo, renunciando a ser constituido en mora. 
	DÉCIMA PRIMERA. PREVENCIÓN DE LAVADO DE ACTIVOS: EL CONTRATANTE podrá terminar de manera unilateral e inmediata el presente 
	contrato, en caso de que EL CONTRATISTA llegare a ser: a) incluido en las listas para el control de lavado de activos y 
	financiación del terrorismo administradas por cualquier autoridad nacional o extranjera, tales como la lista de la Oficina 
	de Control de Activos en el Exterior - OFAC emitida por la Oficina del Tesoro de los Estados Unidos de Norte América, la 
	lista de la Organización de las Naciones Unidas, así como cualquier otra lista pública relacionada con el tema de lavado 
	de activos y financiación del terrorismo, o a) Condenado por parte de las autoridades competentes en cualquier tipo de 
	proceso judicial relacionado con la comisión de los anteriores delitos. En este sentido, EL CONTRATISTA autoriza 
	irrevocablemente a EL CONTRATANTE para que consulte tal información en dichas listas y/o listas similares. EL CONTRATANTE 
	declara bajo la gravedad de juramento que los recursos, fondos, dineros, activos o bienes relacionados con este contrato, 
	son de procedencia lícita y no están vinculados con el lavado de activos ni con ninguno de sus delitos fuente, así como que 
	el destino de los recursos, fondos, dineros, activos o bienes producto de los mismos no van a ser destinados para la 
	financiación del terrorismo o cualquier otra conducta delictiva, de acuerdo con las normas penales y las que sean 
	aplicables en Colombia, sin perjuicio de las acciones legales pertinentes derivadas del incumplimiento de esta declaración. 
	DÉCIMA SEGUNDA. CESIÓN. EL CONTRATISTA no podrá ceder total ni parcialmente, así como subcontratar, la ejecución del 
	presente contrato, salvo previa autorización expresa y escrita de EL CONTRATANTE.
	DÉCIMA TERCERA. TERMINACIÓN. El presente contrato podrá terminar por alguno de los siguientes eventos: a) Vencimiento del 
	plazo sin que las partes, por mutuo acuerdo y por escrito, manifiesten su intención de prorrogarlo; b) Decisión unilateral 
	de EL CONTRATANTE; c) Por mutuo acuerdo entre las partes, lo cual deberá constar en acta de terminación; d) Por 
	incumplimiento de EL CONTRATISTA de alguna de las obligaciones. e) En cumplimiento de lo prescrito en el parágrafo segundo 
	de la cláusula cuarta del presente contrato.
	DÉCIMA CUARTA. DOMICILIO CONTRACTUAL. Para todos los efectos legales, el domicilio contractual será la ciudad de Santa Marta.
	DÉCIMA QUINTA. IMPUESTOS. Si fuere el caso, EL CONTRATANTE deducirá de los honorarios a pagar los valores de los impuestos 
	a que haya lugar de conformidad con lo decretado por la autoridad competente. 
	DÉCIMA SEPTIMA. MODIFICACIONES. Cualquier modificación a los términos y condiciones del presente contrato deberá ser 
	acordada entre las partes y requerirá de un “otrosí” firmado por ellas. 
	DECIMO OCTAVA. ACUERDO. El presente contrato reemplazará en su integridad y deja sin efecto alguno, cualquier otro acuerdo 
	verbal o escrito celebrado con anterioridad entre las partes sobre el mismo objeto.
	<br><br>Para constancia se firma en dos ejemplares, a los ' . date("d") . ' dias del mes ' . changeLetterMonth(date('m')) . ' del año ' .
		date("Y") . '.<br><br><br><br></div></div>';

	return $plantilla3;
}

function initPage()
{
	$plantInit =  '<!DOCTYPE html>
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<title></title>
		</head>
		<body>';

	return $plantInit;
}


function pageAuth($numContract, $nomEmpleado, $ideEmpleado, $rolEmpleado, $typerolEmpleado)
{
	$plantilla =  '<!DOCTYPE html>
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<title></title>
		</head>
		<body>';

	$plantilla .= '<br><br><br><div class="row mt-3"><div class="col-12" style="text-align: justify; font-size: 11px; line-height: 1.5;">
			' . changeLetterMonth(date('m')) . ' ' . date('d') . ' de ' . date('Y') . '<br><br>	


			Señor(es)<br>
			UNION TEMPORAL JORNADA DEPORTIVA ESCOLAR COMPLEMENTARIA CARIBE - UTJDECC<br>
			Atn. Dr. Osvaldo José Villalobos Cortina<br>
			Representante Legal<br>
			Ciudad<br><br>


			ASUNTO: AUTORIZACIÓN DE DESCUENTO DE APORTES AL SISTEMA GENERAL DE SEGURIDAD SOCIAL.<br><br>


			Cordial Saludo,<br><br> 


			Por medio del presente autorizo a la UNION TEMPORAL JORNADA DEPORTIVA ESCOLAR COMPLEMENTARIA CARIBE - UTJDECC, a 
			realizar descuento correspondiente por aportes al Sistema General de Seguridad Social (Salud, Pensión y Arl), 
			del contrato de Prestación de Servicios Profesionales N° ' . str_pad($numContract, 5, '0', STR_PAD_LEFT) .
		' que tiene como objeto ' .
		'EL CONTRATISTA se compromete con EL CONTRATANTE a prestar de manera diligente y con plena autonomía, los servicios 
			como ' . $rolEmpleado . ' - ' . $typerolEmpleado . ' en desarrollo del contrato suscrito por la UNION TEMPORAL JORNADA DEPORTIVA ESCOLAR 
			COMPLEMENTARIA CARIBE - UT JDECC y SERVICIOS POSTALES NACIONALES S.A.S en el desarrollo del contrato suscrito por la 
			UNION TEMPORAL JORNADA DEPORTIVA ESCOLAR COMPLEMENTARIA CARIBE - UT JDECC y SERVICIOS POSTALES NACIONALES S.A.S., 
			cuyo objeto es AUNAR ESFUERZOS ADMINISTRATIVOS, OPERATIVOS QUE GARANTICEN PRESTAR SERVICIOS DE REALIZAR EL PROGRAMA 
			JORNADA ESCOLAR  COMPLEMENTARIA EN LA REGION CARIBE EN VIRTUD DEL CONTRATO INTERADMINISTRATIVO  No. COI-1083-2024, 
			SUSCRITO CON EL CLIENTE MINDEPORTES.”.<br><br><br><br>


			Atentamente,<br><br>

	</div></div>';

	$plantilla .= '<br><br><br><br>
	<table style="font-size: 10px; background-color: #ffffff;">
			<tr>
				<th style="width: 300px; text-align: left;">
					EL CONTRATISTA
				</th>
			</tr>
			<tr style="height: 50px;">
				<td style="text-align: left;">
				   
				</td>
				<td style="height: 50px;">
				   
				</td>
				<td style="height: 50px;">
				   
				</td>
			</tr>
			<tr>
				<th style="text-align: left;">
					' . $nomEmpleado . '
				</th>
			</tr>
			<tr>
				<th style="text-align: left;">
					C.c. No. ' . number_format($ideEmpleado, 0, ',', '.') . '
				</th>
			</tr>
			<tr>
				<th style="text-align: left;">
				</th>
			</tr>
			<tr>
				<th style="text-align: left;">
				</th>
			</tr>
		</table>

	</div>';

	$plantilla .= '</body></html>'; // cerrar la pagina

	return $plantilla;
}

function pageHead($numContract)
{
	/* LOGOS */
	$logo_ut = "/views/img/logos/logo_ut.png";
	$logo_472 = "/views/img/logos/logo_472.png";
	$logo_program = "/views/img/logos/logo_jdec.png";

	$plantHead = '<table>
			<tr>
				<td width = "100">
					<img style="width: 8%;" src="' . $logo_ut . '">
				</td>
				<td width = "100">
					<img style="width: 15%;" src="' . $logo_472 . '">
				</td>
				<td width = "400" style="text-align: center;">
					CONTRATO DE PRESTACIÓN DE SERVICIOS<br> No. ' . str_pad($numContract, 5, '0', STR_PAD_LEFT) . ' DE 2025
				</td>
				<td width = "100">
					<img src="' . $logo_program . '">
				</td>
			</tr>
		</table><br><br>';

	return $plantHead;
}

function pageFoot()
{
	$plantFood = '<table style="text-justify: right; width: 100%; background-color: #ffffff;">
			<tr>
				<td style="font-size: 8px; text-align: right; background-color: #ffffff;">
					CARRERA 11 No. 17-06 Barrio Territorial<br>
					Santa Marta, Magdalena - Colombia<br>
					e-mail: utjdeccaribe@gmail.com<br>
					Cel.: 302 389 8254
				</td>
			</tr>
		</table><br><br>
		';

	return $plantFood;
}

function pageClose($nomEmpleado, $ideEmpleado)
{
	$plantClose =  '<table style="font-size: 10px; background-color: #ffffff;">
			<tr>
				<th style="width: 300px; text-align: left;">
					EL CONTRATANTE
				</th>
				<th style="width: 300px; text-align: left;">
					EL CONTRATISTA
				</th>
			</tr>
			<tr style="height: 50px;">
				<td style="text-align: left;">
				   
				</td>
				<td style="height: 50px;">
				   
				</td>
			</tr>
			<tr>
				<th style="text-align: left;">
					 OSVALDO JOSE VILLALOBOS CORTINA 
				</th>
				<th style="text-align: left;">
					' . $nomEmpleado . '
				</th>
			</tr>
			<tr>
				<th style="text-align: left;">
					 C.c. No. 73.111.404 
				</th>
				<th style="text-align: left;">
					C.c. No. ' . number_format($ideEmpleado, 0, ',', '.') . '
				</th>
			</tr>
			<tr>
				<th style="text-align: left;">
					 REP. LEGAL UT JDECC 
				</th>
				<th style="text-align: left;">
				</th>
			</tr>
			<tr>
				<th style="text-align: left;">
					 NIT. 901.915.364-1
				</th>
				<th style="text-align: left;">
				</th>
			</tr>
		</table>';

	$plantClose .= '</body></html>'; // cerrar la pagina

	return $plantClose;
}

/* Función para validar crear cargos disponibles */
if (isset($_POST["idSubject"])) {
	//var_dump($_POST);exit;
	$ajax = new ContractController();
	$ajax->token_user = $_POST["token"];
	$ajax->idSubject = $_POST["idSubject"];
	$ajax->beginDate = $_POST["beginDate"];
	$ajax->endDate = $_POST["endDate"];
	$ajax->valContract = $_POST["valContract"];
	$ajax->generate();
}
