<?php
/*
 * @author Jeferson Inacio Macedo
 */
	class Alterar_senha_model extends CI_Model {
		
		/*
		 *  Alterar senha do usuario
		 *  @param int $ci_usuario usuario que a senha vai ser alterarda
		 *  @param string $senhaAtual Senha atual
		 *  @param string $newSenha nova senha
		 *  
		 *  @return int se retorna 0 = success, 1 = senha atual incorreta, 2 = erro ao alterar senha 
		 */
		public function alterar_senha($ci_usuario,$senhaAtual, $newSenha){
			$db = $this->db;
			$db->select("ci_usuario ,nm_senha");
			$db->from(" util.tb_usuario");
			$db->where("ci_usuario = ".$ci_usuario);
			$result = $db->get()->row();
			
			if(md5($senhaAtual) == $result->nm_senha || md5(strtoupper($senhaAtual)) == $result->nm_senha){
				$set = array('nm_senha'=>md5($newSenha), 'fl_atualizousenha'=>'S');
				return $db->update('util.tb_usuario',$set, "ci_usuario = ".$ci_usuario);
			}else{
				return 3;
			}
		}
	}
?>