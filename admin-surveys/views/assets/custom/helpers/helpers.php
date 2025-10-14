<?php //Mostrar los arreglos de datos formateados
function dep($data)
{
	$format = print_r('<pre>');
	$format .= print_r($data);
	$format = print_r('</pre>');
	return $format;
}

function changeLetterMonth($month)
{
	$months =   ['01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'];
	return $months[$month];
}
