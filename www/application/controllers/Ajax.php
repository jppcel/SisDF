<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	/*
	 Manipula requisições Ajax
	*/

class Ajax extends MY_Controller {

    public function __construct(){
        parent::__construct();

        // Remove debugger
        $this->output->enable_profiler(FALSE);

        
        if (ENVIRONMENT !== 'development'){
            // Se não é AJAX
            if(!$this->input->is_ajax_request()){
                show_404();
            }
        }

        $this->load->model('ajax_model');

    }

    public function index(){
        echo "to no index";
    }

    public function add_note(){
        if(empty($_POST['anotacao'])){
            echo 'error';
            exit();
        }
        $insert['id_anotacao'] = NULL;
        $insert['data_anotacao'] = date("Y-m-d H:i:s");
        $insert['texto'] = $this->input->post('anotacao');
        $insert['id_os_fk'] = $this->input->post('id_os');
        $insert['id_usuario_fk'] = $this->input->post('id_usuario');

        $db_result= $this->ajax_model->add_note($insert);

        $this->_check_query($db_result);
    }

    // Adiciona um técnico ao atendimento
    public function add_atendente_os(){
        // $this->output->enable_profiler(FALSE);
        if(empty($_POST['id_os'])){
            echo 'error';
            exit();
        }

        $id_os = $this->input->post('id_os');
        $db_result = $this->ajax_model->add_atendente_os($id_os);
        $this->ajax_model->change_os_status( 2 , $id_os );
        
        $this->_check_query($db_result);
    }

    /**
    * Muda status da ordem de serviço
    *
    * Usada na tela Ver OS
    */
    public function change_os_status($id_status = null, $id_os = NULL){
        
        if(!$this->_check_status($id_status) || !$id_os){
            echo "error";
            exit();
        }

        $db_result = $this->ajax_model->change_os_status( $id_status , $id_os );
        $this->_check_query($db_result);

    }

    /**
    * Busca responsável da sala baseado no ID
    *
    * Usada na tela Nova OS
    */

    public function get_resp_sala() {

        $id_sala = $this->input->post('sala');
        $res = $this->ajax_model->get_resp_sala($id_sala);

        echo $res['nome'];
    }

    public function get_os_by($id_secao = NULL, $id_status = NULL){
        $id_secao = $this->input->post('secao');
        $id_status = $this->input->post('os_status');


        // $this->output->enable_profiler(TRUE);
        $os_list = $this->ajax_model->get_os_by($id_secao, $id_status);
        // var_dump($os_list);
        echo json_encode($os_list);
        
    }
   
    /**
     * Pra não ter que reescrever over and over again
     * @param $db_response = BOOLEAN
     * Retornado do query builder
     */
    private function _check_query($db_response){
        if($db_response){
            echo 'success';
        } else {
            echo 'error';
        }
        exit();
    }
}
