<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Empresa extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function registrarEmpresa($data) {
		$data = array(
			'EMP_IMAGEN'				=> $data['imagen'],
			'EMP_NOMBRE'				=> $data['nombre_empresa'],
			'EMP_PAIS'					=> $data['pais'],
			'EMP_CIUDAD'				=> $data['ciudad'],
			'EMP_EMAIL'					=> $data['email'],
			'EMP_TIPO'					=> $data['tipo_usuario'],
			'EMP_ANIO_FUNDACION'		=> $data['anio_fundacion'],
			'EMP_DESCRIPCION'			=> $data['descripcion'],
			''
		);
		
		return false;
	}
	
	public function validarCorreoExistente($data) {
		$resultado = false;
		$consulta = $this->db->where('EMP_ID', $data);
		if($consulta->num_rows() < 0) {
			$resultado = true;
		}
		return $resultado;
	}
	
	public function registrarSectorEmpresarial($data) {
		
	}
}

?>