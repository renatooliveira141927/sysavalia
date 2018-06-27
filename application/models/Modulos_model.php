<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto
 * Date: 11/11/2017
 * Time: 11:30
 */

class Modulos_model extends MY_Model
{
    private $limitPagina = 10;

    public function get_cursos_by_filter($objTerm, $page = 1){
        $objTerm = (Object) $objTerm;
        $db = $this->db;

        $db->select('*');
        $db->from('pronatec.tb_curso tc');
        $db->join("pronatec.tb_eixo te", "te.ci_eixo = tc.cd_eixo");
        $db->where("tc.fl_ativo", true);
        if(isset($objTerm->search1)) $db->where('cd_eixo', $objTerm->search1);
        if(@$objTerm->search2) $db->like('nm_curso', $objTerm->search2);

        $query = $db->get();
        $data['num_rows'] = $query->num_rows();


        $db->select("tc.ci_curso, tc.nm_curso, te.ci_eixo, te.ds_eixo,
            tc.nr_carga_horaria_distancia, tc.nr_carga_horaria_presencial,
            count(tm.ci_modulo) AS qdt_modulos");
        $db->from("pronatec.tb_curso tc");
        $db->join("pronatec.tb_eixo te", "te.ci_eixo = tc.cd_eixo");
        $db->join("pronatec.tb_modulo tm", "tm.cd_curso = tc.ci_curso", "left");
        $db->join("pronatec.tb_modulo_disciplina tmd","tmd.cd_modulo = tm.ci_modulo", "left");
        $db->join("pronatec.tb_disciplina td", "td.ci_disciplina = tmd.cd_disciplina AND td.fl_ativo = 'true'" ,"left", true);
        $db->where("tc.fl_ativo", true);

        if(isset($objTerm->search1) && $objTerm->search1 != 0) $db->where('cd_eixo', $objTerm->search1);
        if(@$objTerm->search2) $db->like('nm_curso', $objTerm->search2);

        $db->group_by("1,2,3,4,5,6");
        $db->order_by("nm_curso");
        $db->limit($this->limitPagina,($page - 1) * $this->limitPagina);

        $query = $db->get();
        //trace($db->last_query());

        $data['result']   = $query->result();
        return (Object) $data;
    }

    public function get_modulos($id = null)
    {
        $db = $this->db;
        $db->select("tm.*, sum(tmd.nr_chpresencial) AS nr_chpresencial, sum(tmd.nr_chadistancia) AS nr_chadistancia, count(tmd.cd_disciplina) AS qdt_disciplina");
        $db->from("pronatec.tb_modulo tm");
        $db->join("pronatec.tb_modulo_disciplina tmd","tmd.cd_modulo = tm.ci_modulo");
        $db->where("tm.cd_curso",$id);
        $db->group_by("1,2,3,4,5,6,7");

        $query = $db->get();
       //trace($db->last_query());

        $data['result'] = $query->result();
        return (Object) $data;
    }


    public function nextval(){
        $db = $this->db;
        $query = $db->query("select nextval('pronatec.tb_modulo_ci_modulo_seq'::regclass) as num");
        $rowID = $query->row();
        return $rowID->num;
    }

    public function save_modulo($obj){
        $usuario_aut = (Object) $this->session->get_userdata('user');
        $db = $this->db;
        $ci_modulo = $this->nextval();

        $data = array(
            'ci_modulo' => $ci_modulo,
            'cd_curso' => $obj->id_curso,
            'ds_modulo' => $obj->modulo,
            'ds_observacao' => $obj->descricao,
            'cd_usuario' => $usuario_aut->user->ci_usuario,
            'dt_criacao' => 'now()'
        );

        if($db->insert('pronatec.tb_modulo', $data)){
            $return = Array("valid"=>TRUE, "messeger"=>"Salvo com sucesso!", "id"=>$ci_modulo);
            return $return;
        }else{
            $return = Array("valid"=>FALSE, "messeger"=>"Ocorreu um erro ao salvar!");
            return $return;
        }
    }

    public function remove_modulo($obj){
        $db = $this->db;
        if($db->delete('pronatec.tb_modulo', array('ci_modulo' => $obj->id))){
            $return = Array("valid"=>TRUE, "messeger"=>"Excluído com sucesso!", "id"=>$obj->id);
            return $return;
        }else{
            $return = Array("valid"=>FALSE, "messeger"=>"Ocorreu um erro ao salvar!");
            return $return;
        }
    }

    public function get_disciplinas_by_modulo($obj)
    {
        $db = $this->db;
        $db->select("tm.*, tmd.*, td.*");
        $db->from("pronatec.tb_modulo tm");
        $db->join("pronatec.tb_modulo_disciplina tmd","tmd.cd_modulo = tm.ci_modulo", "left");
        $db->join("pronatec.tb_disciplina td", "td.ci_disciplina = tmd.cd_disciplina AND td.fl_ativo = 't'");
        $db->where("tm.ci_modulo",$obj->id);

        $query = $db->get();
        //trace($db->last_query());

        $count = $query->num_rows();
        if($count != 0){
            return Array("result"=> (Object) $query->result(), "num_registro"=>$count);
        }

        return Array("result"=> "", "num_registro"=>$count);

    }

    public function get_disciplinas($obj){

        $db = $this->db;
        $db->select("td.ci_disciplina, td.ds_disciplina");
        $db->from("pronatec.tb_disciplina td");
        $db->where("td.fl_ativo", 't');
        $db->like("td.ds_disciplina",strtoupper($obj->term));

        $query = $db->get();
        //trace($db->last_query());

        $count = $query->num_rows();
        if($count != 0){
            return Array("result"=> (Object) $query->result(), "num_registro"=>$count);
        }

        return Array("result"=> "", "num_registro"=>$count);
    }

    public function salvar_modulo_disciplina($obj){
        $usuario_aut = (Object) $this->session->get_userdata('user');
        $db = $this->db;

        $data = array(
            'cd_modulo' => $obj->id_modulo,
            'cd_disciplina' => $obj->id,
            'dt_criacao' => 'now()',
            'nr_chpresencial' => $obj->chpresencial,
            'nr_chadistancia' => $obj->chadistancia,
            'cd_usuario' => $usuario_aut->user->ci_usuario
        );

        if($db->insert('pronatec.tb_modulo_disciplina', $data)){
            $return = Array("valid"=>TRUE, "messeger"=>"Salvo com sucesso!");
            return $return;
        }else{
            $return = Array("valid"=>FALSE, "messeger"=>"Ocorreu um erro ao salvar!");
            return $return;
        }
    }
}