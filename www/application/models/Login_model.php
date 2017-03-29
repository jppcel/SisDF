<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model padrão para checagem de usuários
 *
 * @author André Luiz Girol - Departamento de Física - FFCLRP
 */
class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /*
     * Checa usuário e senha do usuário e alimenta Sessão
     * 
     * Libera login apenas se usuário estiver ativo.
     */

    public function check_user($num_usp, $password) {

        $this->db->select('id_usuario, num_usp, nome, email, ramal, nivel_acesso');
        $this->db->from('usuario');
//        $this->db->join('revenda', 'id_revenda = id_revenda_fk');

        $this->db->where('usuario.num_usp', $num_usp);
        $this->db->where('usuario.senha', md5($password));
        $this->db->where('usuario.ativo', 1);

        $result = $this->db->get();


        if ($result->num_rows() === 1):

            return $result->row_array();

        else:

            return FALSE;

        endif;
    }

}