  <meta charset="UTF-8">
  <title>Formato de Visita JDEC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
      body {
          font-family: Arial, sans-serif;
          background: #fff;
          margin: 0;
          padding: 30px;
      }

      table {
          width: 100%;
          border-collapse: collapse;
          font-size: 14px;
      }

      th,
      td {
          border: 1px solid #000;
          padding: 6px;
          vertical-align: middle;
      }

      th {
          background-color: #005aa7;
          color: white;
          text-align: center;
      }

      .header {
          background-color: #005aa7;
          color: white;
          text-align: center;
          font-weight: bold;
      }

      .subheader {
          background-color: #d9e8f5;
          font-weight: bold;
      }

      .blue-cell {
          background-color: #cce5ff;
      }

      input[type="text"],
      input[type="number"],
      input[type="date"],
      input[type="time"],
      select,
      textarea {
          width: 100%;
          box-sizing: border-box;
          border: none;
          font-size: 13px;
      }

      textarea {
          resize: vertical;
          height: 100px;
      }

      .signature {
          height: 60px;
      }
  </style>

  <table>
      <tr>
          <th colspan="6">PROCESO:<br>Fomento al Desarrollo Humano y Social<br>FORMATO<br>Visita a Formadores y profesional psicosocial JDEC</th>
      </tr>
      <tr>
          <td class="blue-cell">Fecha de Supervisión</td>
          <td><input type="date"></td>
          <td class="blue-cell">Hora de Inicio:</td>
          <td><input type="time"></td>
          <td class="blue-cell">Hora de Finalización:</td>
          <td><input type="time"></td>
      </tr>
      <tr>
          <td class="blue-cell">Seguimiento a:</td>
          <td colspan="2"><select>
                  <option>Formador</option>
                  <option>Profesional Psicosocial</option>
              </select></td>
          <td class="blue-cell">Nombre del profesional al que se le hace la visita</td>
          <td colspan="2"><input type="text"></td>
      </tr>
      <tr>
          <td class="blue-cell">Edades:</td>
          <td>3-5 Años <input type="checkbox"></td>
          <td>6-9 Años <input type="checkbox"></td>
          <td>10-12 años <input type="checkbox"></td>
          <td>13-15 años <input type="checkbox"></td>
          <td>16-17 años <input type="checkbox"></td>
      </tr>
      <tr>
          <td class="blue-cell">Departamento:</td>
          <td><input type="text"></td>
          <td class="blue-cell">Municipio:</td>
          <td><input type="text"></td>
          <td class="blue-cell">I.E.:</td>
          <td><input type="text"></td>
      </tr>
      <tr>
          <td class="blue-cell">Formato Listado de Asistencia</td>
          <td>SI <input type="radio" name="asistencia"> NO <input type="radio" name="asistencia"></td>
          <td colspan="2" class="blue-cell">Número de Usuarios en Clase:</td>
          <td colspan="2"><input type="number"></td>
      </tr>
      <tr>
          <td class="blue-cell">Presenta Plan de Clase</td>
          <td>SI <input type="radio" name="plan"> NO <input type="radio" name="plan"></td>
          <td colspan="2" class="blue-cell">La sesión de clase es acorde con el plan clase</td>
          <td>SI <input type="radio" name="plan_acorde"> NO <input type="radio" name="plan_acorde"></td>
      </tr>
      <tr>
          <td class="blue-cell">Tiene el directorio de padres</td>
          <td>SI <input type="radio" name="directorio"> NO <input type="radio" name="directorio"></td>
          <td colspan="2" class="blue-cell">El formador o profesional psicosocial se presenta en la indumentaria adecuada a las clases</td>
          <td>SI <input type="radio" name="indumentaria"> NO <input type="radio" name="indumentaria"></td>
      </tr>
      <tr>
          <td class="blue-cell">Dominio del Grupo</td>
          <td>SI <input type="radio" name="grupo"> NO <input type="radio" name="grupo"></td>
          <td colspan="2" class="blue-cell">Dominio del Tema</td>
          <td>SI <input type="radio" name="tema"> NO <input type="radio" name="tema"></td>
      </tr>
      <tr>
          <td class="blue-cell">Uso Adecuado de Escenario y Materiales</td>
          <td>SI <input type="radio" name="escenario"> NO <input type="radio" name="escenario"></td>
          <td colspan="2" class="blue-cell">¿Por qué?</td>
          <td colspan="2"><input type="text"></td>
      </tr>
      <tr>
          <td class="blue-cell">Formato de Póliza</td>
          <td>SI <input type="radio" name="poliza"> NO <input type="radio" name="poliza"></td>
          <td colspan="2" class="blue-cell">Conoce la ruta de Emergencia</td>
          <td>SI <input type="radio" name="ruta"> NO <input type="radio" name="ruta"></td>
      </tr>
      <tr>
          <th colspan="6">Visita</th>
      </tr>
      <tr>
          <td colspan="6">
              <label>Observaciones del Coordinador:</label>
              <textarea></textarea>
          </td>
      </tr>
  </table>

  <br>

  <table>
      <tr>
          <th>Firma del Coordinador</th>
          <th>Firma del Encuestado</th>
          <th>Firma Directivo Docente y/o Líder Comunitario</th>
      </tr>
      <tr>
          <td class="signature"></td>
          <td class="signature"></td>
          <td class="signature"></td>
      </tr>
      <tr>
          <td>C.C. No.<br><input type="text"><br>No. de Teléfono<br><input type="text"></td>
          <td>Nombre Completo del encuestado<br><input type="text"><br>C.C. No.<br><input type="text"><br>No. de Teléfono<br><input type="text"></td>
          <td>Nombre Completo del DD y/o LC:<br><input type="text"><br>C.C. No.<br><input type="text"><br>No. de Teléfono<br><input type="text"></td>
      </tr>
  </table>