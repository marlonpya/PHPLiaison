<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_Api_Rest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('security');
        $this->load->model('M_Empresa');
    }

    /*
    | --------------------------------------------------------
    | URL -> ENVIAR DATA DINAMICA A LAS APLICACIONES MOVILES
    | --------------------------------------------------------
    */
    public function getAllData() {
        $json 				= new stdClass();
        $json->type 		= "All Data";
        $json->presentation = "getAllData";
        $json->data 		= array();
        $json->status 		= FALSE;

        $dataCategorias         = $this->M_Categoria->getTotalCategorias();
        $dataSubcategorias      = $this->M_Subcategoria->getTotalSubcategorias();
        $dataPreguntas          = $this->M_Pregunta->getTotalPreguntas();
        $dataArea               = $this->M_Area->getTotalArea();
        $dataTerminos            = $this->M_Termino->fetch();

        $data                           = new stdClass();
        $data->dataCategorias           = $dataCategorias;
        $data->dataSubcategorias        = $dataSubcategorias;
        $data->dataPreguntas            = $dataPreguntas;
        $data->dataArea                 = $dataArea;
        $data->dataTerminos             = $dataTerminos;

        $json->data         = $data;
        $json->message      = "Todos los registros fueron recuperados correctamente.";
        $json->status       = TRUE;

        echo json_encode($json);
    }

    /*
    | --------------------------------------------------------
    | URL -> SOLICITAR DATOS DE PREGUNTAS ACTUALIZADAS
    | --------------------------------------------------------
    */
    public function getPreguntas() {
        $json 				= new stdClass();
        $json->type 		= "All Data";
        $json->presentation = "getPreguntas";
        $json->data 		= array();
        $json->status 		= FALSE;

        $dataCategorias         = $this->M_Categoria->getTotalCategorias();
        $dataSubcategorias      = $this->M_Subcategoria->getTotalSubcategorias();
        $dataPreguntas          = $this->M_Pregunta->getTotalPreguntas();

        $data                           = new stdClass();
        $data->dataCategorias           = $dataCategorias;
        $data->dataSubcategorias        = $dataSubcategorias;
        $data->dataPreguntas            = $dataPreguntas;

        $json->data         = $data;
        $json->message      = "Todos los registros fueron recuperados correctamente.";
        $json->status       = TRUE;

        echo json_encode($json);
    }



    /*
    | --------------------------------------------------------
    | URL -> SOLICITAR DATOS DE NUEVAS AREAS DE INTERES
    | --------------------------------------------------------
    */
    public function getAreas() {
        $json 				= new stdClass();
        $json->type 		= "All Data";
        $json->presentation = "getAreas";
        $json->data 		= array();
        $json->status 		= FALSE;


        $dataArea               = $this->M_Area->getTotalArea();
        $data                           = new stdClass();
        $data->dataArea                 = $dataArea;

        $json->data         = $data;
        $json->message      = "Todos los registros fueron recuperados correctamente.";
        $json->status       = TRUE;

        echo json_encode($json);
    }



    /*
    | --------------------------------------------------------
    | URL -> SOLICITAR DATOS DE NUEVOS TERMINOS Y CONDICIONES
    | --------------------------------------------------------
    */
    public function getTerminos() {
        $json 				= new stdClass();
        $json->type 		= "All Data";
        $json->presentation = "getTerminos";
        $json->data 		= array();
        $json->status 		= FALSE;


        $dataTerminos            = $this->M_Termino->fetch();
        $data                           = new stdClass();
        $data->dataTerminos             = $dataTerminos;

        $json->data         = $data;
        $json->message      = "Todos los registros fueron recuperados correctamente.";
        $json->status       = TRUE;

        echo json_encode($json);
    }



    /*
    | ----------------------------------------------------
    | URL -> SOLICITAR CAMBIO DE UNA PREGUNTA DESDE ANDROID
    | ----------------------------------------------------
    */
    public function updatePregunta(){
        $json                   = new stdClass();
        $json->type             = "getPregunta";
        $json->presentation     = "";
        $json->action           = "get";
        $json->data             = array();
        $json->status           = FALSE;

        if ($this->input->post("idPregunta")) {
            /* Registrar Sugerencia */
            $dataPregunta = $this->M_Api_Rest->getPreguntaByID($this->input->post("idPregunta"));
            if(count($dataPregunta)>0){
                $json->data = $dataPregunta;
                $json->message = "La pregunta se obtuvo correctamente.";
                $json->status = TRUE;
            }else{
                $json->message = "No se ha podido encontrar la pregunta solicitada.";
            }

        } else {
            $json->message  = "No se recibio los parametros necesarios para procesar su solicitud.";
        }
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($json);
    }



    /*
    | --------------------------------------------------------------------------
    | URL -> RECIBE 5 PARAMETROS INICIALES PARA INICIAR UN TEST DESDE ANDROID
    | --------------------------------------------------------------------------
    */
    public function startTest() {
        $json 				= new stdClass();
        $json->type 		= "Start Test";
        $json->presentation = "saveTest";
        $json->data 		= array();
        $json->status 		= FALSE;

        if ( $this->input->post("fecha") && $this->input->post("idCategoria") && $this->input->post("token") && $this->input->post("from") && $this->input->post("idPeriodo")   ) {

            $id = $this->M_Api_Rest->createTest(
                array(
                    "fecha"                     => $this->input->post("fecha"),
                    "idCategoria"               => $this->input->post("idCategoria"),
                    "token"                     => $this->input->post("token"),
                    "idPeriodo"                 => $this->input->post("idPeriodo"),
                    "from"                      => $this->input->post("from")
                )
            );

            if($id){
                $json->id       = $id;
                $json->message  = "Test creado correctamente";
                $json->status   = TRUE;
            }else{
                $json->message = "No se ha podido crear el Test";
            }

        } else {
            $json->message 	= "No se recibio los parametros necesarios para procesar su solicitud.";
        }


        echo json_encode($json);
    }



    /*
    | ----------------------------------------------------
    | URL -> ACTUALIZAR TOKEN DE CELULAR ANDROID
    | ----------------------------------------------------
    */
    public function updateToken() {
        $json 				= new stdClass();
        $json->type 		= "Update Test";
        $json->presentation = "updateToken";
        $json->data 		= array();
        $json->status 		= FALSE;

        if ( $this->input->post("idTest") && $this->input->post("token")   ) {

            $id = $this->M_Api_Rest->updateToken(
                array(
                    "idTest"                     => $this->input->post("idTest"),
                    "token"                      => $this->input->post("token")
                )
            );

            if($id){
                $json->message = "Token actualizado correctamente";
                $json->status = TRUE;
            }else{
                $json->message = "No se ha podido actualizado el Token";
            }

        } else {
            $json->message 	= "No se recibio los parametros necesarios para procesar su solicitud.";
        }


        echo json_encode($json);
    }


    /*
    | ----------------------------------------------------
    | URL -> ACTUALIZAR INFORMACION DEL TEST RESUELTO
    | ----------------------------------------------------
    */
    public function saveFormulario() {

        $json                   = new stdClass();
        $json->type             = "saveFormulario";
        $json->presentation     = "";
        $json->action           = "insert";
        $json->data             = array();
        $json->status           = FALSE;


        if ($this->input->post("idTest") &&  $this->input->post("txtNombres")  && $this->input->post("txtCorreo")  && $this->input->post("txtCargo")  && $this->input->post("txtTelefono") && $this->input->post("txtRazonSocial") && $this->input->post("cbAreas")&& $this->input->post("pregunta")) {

            /* Registrar Datos */
            $this->M_Api_Rest->updateTest(
                array(
                    "txtNombres"      			=> trim($this->input->post("txtNombres", TRUE)),
                    "txtCorreo"      			=> trim($this->input->post("txtCorreo", TRUE)),
                    "txtCargo"      			=> trim($this->input->post("txtCargo", TRUE)),
                    "txtTelefono"      			=> trim($this->input->post("txtTelefono", TRUE)),
                    "txtRazonSocial"      		=> trim($this->input->post("txtRazonSocial", TRUE)),
                    "cbAreas"      				=> trim($this->input->post("cbAreas", TRUE)),
                    "pregunta"      			=> trim($this->input->post("pregunta", TRUE))
                ),$this->input->post("idTest")
            );

            $data = $this->M_Api_Rest->getDataFromTest($this->input->post("idTest"));
            if($data[0]->idCategoria == '1'){
                $this->getResultados($this->input->post("idTest"), $data[0]->idCategoria);
                $this->generatePdf($this->input->post("idTest"));
                $json->message = "Gracias por completar el Test de GestiÃ³n Empresarial.. En breves momentos recibirÃ¡ un correo con sus resultados. Recuerde revisar su bandeja de correo no deseado.";

            }else{
                $this->getResultados($this->input->post("idTest"), $data[0]->idCategoria);
                $this->generateInnovacionPdf($this->input->post("idTest"));
                $json->message = "Gracias por completar el Test de InnovaciÃ³n Empresarial.. En breves momentos recibirÃ¡ un correo con sus resultados. Recuerde revisar su bandeja de correo no deseado.";

            }

            $json->status = TRUE;


        } else {
            $json->message  = "No se recibio los parametros necesarios para procesar su solicitud.";
        }

        echo json_encode($json);
    }



    /*
    | ----------------------------------------------------------------------------
    | URL -> GENERAR ESTRUCTURA Y GUARDAR PDF DE AREA DE INNOVACION - CATEGORIA 2
    | ----------------------------------------------------------------------------
    */
    public function generateInnovacionPdf($idTest){
        $this->load->library('utils/Pdf');
        $this->load->library('utils/PHPMailer/Mailer');
        $this->load->model('api-rest/M_Api_Rest');
        $this->load->model('M_Texto');

        $resultados = $this->M_Api_Rest->getResultadosTest($idTest);

        $pdf = new TCPDF('P', 'px', 'letter', true, 'UTF-8', false);
        $pdf->AddPage();
        // create some HTML content
        $data = $this->M_Api_Rest->getDataFromTest($idTest);
        $texto     = $this->M_Texto->fetch();

        if($resultados[0]->resultado_final < 45){
            $recomendacion = $texto[1]->recomendacion;
            $cabecera1 = $texto[1]->cabecera1;
            $cabecera2 = $texto[1]->cabecera2;
        }
        if($resultados[0]->resultado_final > 44 && $resultados[0]->resultado_final < 75){
            $recomendacion = $texto[2]->recomendacion;
            $cabecera1 = $texto[2]->cabecera1;
            $cabecera2 = $texto[2]->cabecera2;
        }
        if($resultados[0]->resultado_final > 74){
            $recomendacion = $texto[3]->recomendacion;
            $cabecera1 = $texto[3]->cabecera1;
            $cabecera2 = $texto[3]->cabecera2;
        }

        $html = '
            <table cellpadding="1" cellspacing="1" border="0" style="text-align:center; border-color: #fff" >
                <tr>
                <td style="text-align:center;"><img src="'.base_url().PATH_RESOURCE_MAIN.'images/background/fondo-innova.png'.'" border="0" height="70" width="550" align="middle" /></td>
                </tr>
                <tr style="width: 60px">
                <td></td>
                </tr>
                <tr style="text-align:left;font-size: 10px;"><td>Estimado '.$data[0]->fullname.':</td>
                </tr>
                <tr style="text-align:left;font-size: 10px;">
                <td>'.$cabecera1."". $data[0]->razonsocial." ".$cabecera2. '</td>
                </tr>
            </table>
        ';
        // output the HTML content
        $pdf->writeHTML($html, 1, 0, 1, 0, '');


        $w = array(100, 110, 100, 115, 100);
        $i=0;
        foreach($resultados as $res){
            $pdf->Cell($w[$i], 7, $res->nombre_subc, 0, 0, 'C', 0);
            $i++;
        }

        // print a block of text using Write()
        $pdf->Write(0, "", '', 0, 'C', true, 0, false, false, 0);
        $total = count($resultados);
        $i = 1;
        foreach($resultados as $res){
            if($i==$total){
                $pdf->Image(base_url().PATH_RESOURCE_MAIN.'resultados/innova/'.$this->getImages($res->resultado), '', '', 105, 105, '', '', 'N', false, 300, '', false, false, false, false, false, false);
            }else{
                $pdf->Image(base_url().PATH_RESOURCE_MAIN.'resultados/innova/'.$this->getImages($res->resultado), '', '', 105, 105, '', '', 'T', false, 300, '', false, false, false, false, false, false);
            }

            $i++;
        }

        $i=0;
        foreach($resultados as $res){
            $pdf->Cell($w[$i], 7, round($res->resultado,0,PHP_ROUND_HALF_UP).' %', 0, 0, 'C', 0);
            $i++;
        }

        // print a block of text using Write()
        $pdf->Write(0, "", '', 0, 'C', true, 0, false, false, 0);

        $pdf->Image(base_url().PATH_RESOURCE_MAIN.'resultados/rinnova/'.$this->getImages($resultados[0]->resultado_final), '', '', 260, 160, '', '', 'N', false, 300, 'C', false, false, false, false, false, false);

        $html = '

            <table cellpadding="1" cellspacing="1" border="0" style="text-align:center; border-color: #fff" >
                <tr style="font-size: 12px;">
                    <td><p>RESULTADO FINAL: '.round($resultados[0]->resultado_final,0,PHP_ROUND_HALF_UP).' %</p></td>
                </tr>
                <tr style="text-align:left;font-size: 10px;">
                <td>'.$recomendacion. '</td>
                </tr>

            </table>


        ';

        // output the HTML content
        $pdf->writeHTML($html, 1, 0, 1, 0, '');
        $pdf->lastPage();// reset pointer to the last page
        //$pdf->Output('INNOVACION_EMPRESARIAL_RESULTADOS.pdf', 'I');//Close and output PDF document
        $pdf->Output('uploads/resultados/innovacion/'.$idTest.'.pdf', 'F');//save the document

        $path = base_url()."uploads/resultados/innovacion/".$idTest.".pdf";
        $filename = "INNOVACION_EMPRESARIAL_RESULTADOS.pdf";

        $emails = $this->M_Api_Rest->getEmails('2');
        $rp = $this->sendEmail($emails, $path, $filename, 'IPAE - INNOVACION EMPRESARIAL', 'RESULTADOS DEL TEST DE INNOVACION EMPRESARIAL', $data[0]->email, CORREO_INNOVA);
        if($rp){
            return TRUE;
        }else{
            return FALSE;
        }

    }



    /*
    | -------------------------------------------------------------------------
    | URL -> GENERAR ESTRUCTURA Y GUARDAR PDF DE AREA DE ACELERA - CATEGORIA 1
    | -------------------------------------------------------------------------
    */
    public function generatePdf($idTest){
        $this->load->library('utils/Pdf');
        $this->load->library('utils/PHPMailer/Mailer');
        $this->load->model('api-rest/M_Api_Rest');
        $this->load->model('M_Texto');

        $this->generateImagen($idTest);
        $pdf = new TCPDF('P', 'px', 'letter', true, 'UTF-8', false);
        $pdf->AddPage();
        // create some HTML content
        $data = $this->M_Api_Rest->getDataFromTest($idTest);
        $texto     = $this->M_Texto->fetch();

        $html = '
            <table cellpadding="1" cellspacing="1" border="0" style="text-align:center; border-color: #fff" >
                <tr>
                <td style="text-align:center;"><img src="'.base_url().PATH_RESOURCE_MAIN.'images/background/fondo-acelera.png'.'" border="0" height="70" width="550" align="middle" /></td>
                </tr>
                <tr style="width: 60px">
                <td></td>
                </tr>
                <tr style="text-align:left;font-size: 10px;"><td>Estimado '.$data[0]->fullname.':</td>
                </tr>
                <tr style="text-align:left;font-size: 10px;">
                <td>'.$texto[0]->cabecera1."". $data[0]->razonsocial." ".$texto[0]->cabecera2. '</td>
                </tr>
            </table>
        ';
        // output the HTML content
        $pdf->writeHTML($html, 1, 0, 1, 0, '');


        $pdf->Image(base_url()."uploads/resultados/imagen/".$idTest.".jpg", '', '', 500, 250, '', '', 'N', false, 300, 'C', false, false, false, false, false, false);

        $html = '
            <br>
            <br>
            <table cellpadding="1" cellspacing="1" border="0" style="text-align:center; border-color: #fff" >
                <br>
                <tr style="text-align:left;font-size: 10px;">
                <td>'.$recomendacion = $texto[0]->recomendacion. '</td>
                </tr>
            </table>
        ';

        // output the HTML content
        $pdf->writeHTML($html, 1, 0, 1, 0, '');
        $pdf->lastPage();// reset pointer to the last page
        //$pdf->Output('uploads/resultados/gestion/GESTION_EMPRESARIAL_RESULTADOS.pdf', 'I');//Close and output PDF document
        $pdf->Output('uploads/resultados/gestion/'.$idTest.'.pdf', 'F');//save the document

        $path = base_url()."uploads/resultados/gestion/".$idTest.".pdf";
        $filename = "GESTION_EMPRESARIAL_RESULTADOS.pdf";

        $emails = $this->M_Api_Rest->getEmails('1');
        $data = $this->M_Api_Rest->getDataFromTest($idTest);
        $rp = $this->sendEmail($emails, $path, $filename, 'IPAE - GESTION EMPRESARIAL', 'RESULTADOS DEL TEST DE GESTION EMPRESARIAL', $data[0]->email, CORREO_ACELERA);
        if($rp){
            return TRUE;
        }else{
            return FALSE;
        }
    }



    /*
    | ----------------------------------------------------------------------
    | URL -> CREAR GRAFICO ESTADISTICO DEL AREA DE ACELERA - CATEGORIA 1
    | ----------------------------------------------------------------------
    */
    public function generateImagen($idTest){
        $this->load->library('utils/JpGraph/Graph');
        $resultado = $this->M_Api_Rest->getResultadosTest($idTest);

        $arrayNivelOptimoEmpresarial = array();
        $arrayResultadoAcelera = array();
        $leyenda = array();
        foreach($resultado as $res){
            $arrayResultadoAcelera[] = $res->resultado;
            $arrayNivelOptimoEmpresarial[] = 100;
            $leyenda[] = $res->nombre_subc;
        }

        function salida_azul() {
            return array(floor(100/5), "", "#0d7ec5");
        }

        function salida_verde() {
            return array(floor(100/5), "", "#0e914c");
        }

        //CREAMOS UN OBJETO GRAPH QUE ES EL GRÃ�FICO(PANEL) PARA MOSTRAR SIEMPRE
        $graph = new Graph(800, 400, "auto");	//ancho, alto, modo autoajustado
        $graph->SetMargin(0, 160, 0, 0);		//margen de mi Graph
        $graph->SetScale("textlin", -20, 100);			//estilo que centra mi Graph
        $graph->SetBox(false);					//inhabilitar mostrar contorno de mi Graph
        $graph->SetMarginColor('white');
        $graph->SetFrame(true,'white',1);

        // $graph->legend REPRESENTA A LA LEYENDA DE MI GRAPH
        $graph->legend->SetColumns(1);			//seteamos una sola columna para la leyenda
        $graph->legend->SetLineSpacing(90);		//espacio creado entre cada row de leyenda ejm(tenemos 2, Nivel Ã“ptimo Empresarial y Nivel de Empresa Evaluada
        $graph->legend->Pos(0.03, 0.30, "right", "center");//posicion de la leyenda en el Graph
        $graph->legend->SetColor("#000000","#FFFFFF");
        $graph->legend->SetFillColor("#FFFFFF");

        // $graph->yaxis EL ATRIBUTO "Y" DE NUESTRO GRAPH, OBS: TIENE "Y" y "X"
        $graph->yaxis->scale->setGrace(20); 	//seteamos que nuestra medida de Y estÃ¡ hasta 100
        $graph->yaxis->SetColor("#FFFFFF");	//seteamos un color blanco para que no se pueda mostrar
        $graph->yaxis->HideZeroLabel();
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);
        $graph->yaxis->SetTickPositions(array(-20, 0, 20, 40, 60, 80, 100));//seteamos el array de cada punto "Y" que tengamos
        $graph->ygrid->SetFill(false);			//inhabilitamos mostrar colores en nuestros puntos "Y"
        $graph->xaxis->SetPos('min');
        //$graph->xaxis EL ATRIBUTO "X" DE NUESTRO GRAPH
        $graph->xaxis->SetTickLabels($leyenda);//seteamos el array de cada punto "X" que tengamos
        $graph->xaxis->SetFont(FF_FONT0, FS_BOLDIT);

        //CREAMOS UN OBJETO LINEPLOT PARA MARCAR LAS L?EAS
        $p1 = new LinePlot($arrayNivelOptimoEmpresarial);//seteamos un array en este caso el de $arrayNivelOptimoEmpresarial
        $p1->SetColor("#0d7ec5");				//seteamos un color a las l?eas
        //$p1->SetLegend('Nivel Ã“ptimo\nEmpresarial');//creamos la leyenda para nuestro LinePlot
        $p1->SetCenter();

        $pp1 = new ScatterPlot($arrayNivelOptimoEmpresarial);
        $pp1->mark->SetType(MARK_FILLEDCIRCLE);
        $pp1->mark->SetCallback("salida_azul");
        $pp1->value->SetColor("#FFFFFF");
        $pp1->value->SetFont(FF_FONT1, FS_BOLD);
        $pp1->value->show();
        $p1->SetLegend("Nivel Ã“ptimo\nEmpresarial");

        $graph->Add($p1);
        $graph->Add($pp1);

        $p2 = new LinePlot($arrayResultadoAcelera);

        $pp2 = new ScatterPlot($arrayResultadoAcelera);
        $pp2->mark->SetType(MARK_FILLEDCIRCLE);
        $pp2->mark->SetCallback("salida_verde");
        $pp2->value->SetColor("#FFFFFF");
        $pp2->value->SetFont(FF_FONT1, FS_BOLD);
        $pp2->value->show();

        $graph->Add($p2);

        $p2->SetColor("#58FA58");
        $p2->mark->SetColor("#58FA58");
        $graph->Add($pp2);


        $p2->SetLegend("Nivel de Empresa\nEvaluada");
        $graph->Stroke("uploads/resultados/imagen/".$idTest.".jpg");
    }




    /*
    | --------------------------------------------------------------------------------------------------
    | URL -> ENVIAR EMAIL CON LOS RESULTADOS ( ADJUNTO PDF DE RESULTADOS Y COPIA A EMAILS ASIGNADOS )
    | --------------------------------------------------------------------------------------------------
    */
    function sendEmail($emails, $path, $filename,$name, $subject, $to, $from){

        $file = $path;

        $email = new PHPMailer();
        $email->From      = $from;
        $email->FromName  = $name;
        $email->Subject   = $subject;
        $message = "
            <html><body><table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border: 0px solid #cccccc; border-collapse: collapse;'><tr><td align='center' bgcolor='#fff' style='padding: 20px 0 20px 0; color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;'>
            <img src='".base_url().PATH_RESOURCE_ADMIN."img/icon/bg-page.jpg' width='600' height='5'>
            <img src='".base_url().PATH_RESOURCE_ADMIN."img/icon/logo_cabecera.png' alt='IPAE ACCION EMPRESARIAL' width='250' height='70' style='display: block;' />
            <img src='".base_url().PATH_RESOURCE_ADMIN."img/icon/line.png' width='600' height='5'></td></tr><tr><td style='color: #153643; font-family: Arial, sans-serif; font-size: 24px;'>
            <b>Los resultados obtenidos en el test se encuentran en el documento adjunto en este email</b><br/><br/>
            <p style='font-size: 14px;'>IPAE - ACCION EMPRESARIAL</p></td></tr></table></body></html>
        ";
        $email->Body      = $message;
        $email->IsHTML(true);
        $email->AddAddress( $to );
        /*foreach($emails as $e){
            $email->AddBCC($e->email);
        }*/

        $email->AddStringAttachment( file_get_contents($file), $filename, "base64", "application/pdf");

        if (!$email->Send()) {
            /* Error */
           return FALSE;
        } else {
            /* Success */
            return TRUE;
        }
    }



    /*
    | -------------------------------------------------------------------------------------------------------------
    | URL -> OBTENER LAS IMAGENES (DE TACOMETROS) CORRESPONDIENTES SEGUN EL RANGO DEL AREA DE INNOVA - CATEGORIA 2
    | -------------------------------------------------------------------------------------------------------------
    */
    function getImages($resultado){
        if($resultado == 0 )  {  return 'i0.png';  }
        if($resultado > 0 && $resultado <= 5)  {  return 'i1.png';  }
        if($resultado > 5 && $resultado <= 10) {  return 'i2.png';  }
        if($resultado > 10 && $resultado <= 15){  return 'i3.png';  }
        if($resultado > 15 && $resultado <= 20){  return 'i4.png';  }
        if($resultado > 20 && $resultado <= 25){  return 'i5.png';  }
        if($resultado > 25 && $resultado <= 30){  return 'i6.png';  }
        if($resultado > 30 && $resultado <= 35){  return 'i7.png';  }
        if($resultado > 35 && $resultado <= 40){  return 'i8.png';  }
        if($resultado > 40 && $resultado <= 45){  return 'i9.png';  }
        if($resultado > 45 && $resultado <= 50){  return 'i10.png';  }
        if($resultado > 50 && $resultado <= 55){  return 'i11.png';  }
        if($resultado > 55 && $resultado <= 60){  return 'i12.png';  }
        if($resultado > 60 && $resultado <= 65){  return 'i13.png';  }
        if($resultado > 65 && $resultado <= 70){  return 'i14.png';  }
        if($resultado > 70 && $resultado <= 75){  return 'i15.png';  }
        if($resultado > 75 && $resultado <= 80){  return 'i16.png';  }
        if($resultado > 80 && $resultado <= 85){  return 'i17.png';  }
        if($resultado > 85 && $resultado <= 90){  return 'i18.png';  }
        if($resultado > 90 && $resultado <= 95){  return 'i19.png';  }
        if($resultado > 95 && $resultado <= 100){  return 'i20.png';  }
    }



    /*
    | ------------------------------------------------------
    | URL -> OBTENER LOS RESULTADOS DE UN TEST RESUELTO
    | ------------------------------------------------------
    */
    public function getResultados($idTest, $idCategoria){

        $puntajes 		= $this->M_Categoria->getPuntajeByCat($idCategoria);
        $npreguntastotal  =  $this->M_Pregunta->getCountPreguntasByCategorias($idCategoria);
        $sucategorias       =  $this->M_Acelera->getPreguntasBySubc($idCategoria);
        $respuestas = $this->M_Acelera->getRespuestaByTest($idTest);


        $PSI 				= $puntajes[0]->puntaje;
        $SI 				= $puntajes[0]->texto;
        $PENPROCESO 		= $puntajes[1]->puntaje;
        $ENPROCESO 			= $puntajes[1]->texto;
        $PNO 				= $puntajes[2]->puntaje;
        $NO 				= $puntajes[2]->texto;
        $PNOAPLICA 			= $puntajes[3]->puntaje;
        $NOAPLICA 			= $puntajes[3]->texto;

        $PUNTAJEGENERAL = 0;


        foreach($sucategorias as $subc){

            $PUNTAJE = 0;
            $bandera = 0;
            foreach($respuestas as $respuesta){
                if( $respuesta->idSubcategoria == $subc->idSubCategoria){
                    if($respuesta->rp == $SI ){
                        $PUNTAJE = $PUNTAJE + $PSI ;
                    }
                    if($respuesta->rp == $ENPROCESO){
                        $PUNTAJE = $PUNTAJE + $PENPROCESO ;
                    }
                    if($respuesta->rp == $NO){
                        $PUNTAJE = $PUNTAJE + $PNO ;
                    }
                    if($respuesta->rp == $NOAPLICA){
                        $PUNTAJE = $PUNTAJE + $PNOAPLICA ;
                        $bandera ++;
                    }
                }
            }

            /*  El MÃ¡ximo Puntaje por Grupo */
            $npreguntas = $subc->cantidad;
            if($bandera > 0){
                $npreguntas = $npreguntas - $bandera;
            }
            $MPGA =  $npreguntas * $PSI;
            $SUBCPUNTAJE = ($PUNTAJE / $MPGA) * 100;
            $PUNTAJEGENERAL = $PUNTAJEGENERAL + $PUNTAJE;

            $r = $this->M_Acelera->checkResultadosByTest($idTest , $subc->idSubCategoria);
            if( $r == 0){
                $this->M_Acelera->saveResultados(
                    array(
                        "idTest"            		=> $idTest,
                        "resultado"                	=>  round($SUBCPUNTAJE,0,PHP_ROUND_HALF_UP),
                        "idSubCategoria"            => $subc->idSubCategoria
                    )
                );
            }
        }

        /*  El MÃ¡ximo Puntaje Total Acumulado */
        $MPTA = $npreguntastotal * $PSI;
        $NIVELGENERAL =  ( $PUNTAJEGENERAL / $MPTA ) * 100 ;

        $this->M_Acelera->updateResultadoTest(round($NIVELGENERAL,0,PHP_ROUND_HALF_UP), $idTest);

    }



    /*
    | ------------------------------------------------------
    | URL -> GUARDAR RESPUESTAS REALIZADAS DESDE ANDROID
    | ------------------------------------------------------
    */
    public function saveRespuestas() {
        $json = new stdClass ();
        $json->type = "saveRespuestas";
        $json->presentation = "";
        $json->action = "insert";
        $json->data = array ();

        $dataRespuestas = json_encode ($this->input->post() );
        $dataRespuestas = json_decode ( $this->input->post ( "dataRespuestas" ), true );
        $this->M_Api_Rest->clearTable($dataRespuestas[0]['idTest']);

        foreach ( $dataRespuestas as $d ) {

            $this->M_Api_Rest->saveRespuesta(
                array(
                    "idTest" => $d["idTest"],
                    "idPregunta" => $d["idPregunta"],
                    "idSubcategoria" => $d["subCategoria"],
                    "rp" => $d["rp"]
                )

            );
        }



        $json->message = "Respuestas guardadas correctamente";
        $json->status = TRUE;

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($json);
    }




    /*
    | ------------------------------------------------------
    | URL -> GUARDAR SUGERENCIA ENVIADA DESDE ANDROID
    | ------------------------------------------------------
    */
    public function saveSugerencia() {

        $json                   = new stdClass();
        $json->type             = "saveSugerencia";
        $json->presentation     = "";
        $json->action           = "insert";
        $json->data             = array();
        $json->status           = FALSE;

        if ($this->input->post("nombrecompleto") &&  $this->input->post("correo") &&  $this->input->post("consulta")  ) {

            /* Registrar Sugerencia */
            $this->M_Api_Rest->saveSugerencia(
                array(
                    "nombrecompleto"      			=> trim($this->input->post("nombrecompleto", TRUE)),
                    "correo"      			=> trim($this->input->post("correo", TRUE)),
                    "consulta"      			=> trim($this->input->post("consulta", TRUE))
                )
            );

            $json->message = "La Sugerencia se enviÃ³ correctamente.";
            $json->status = TRUE;

        } else {
            $json->message  = "No se recibio los parametros necesarios para procesar su solicitud.";
        }
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($json);
    }






}