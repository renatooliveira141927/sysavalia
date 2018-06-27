<?php

/**
 * Verifica se um privilégio, número de uma somatória, tem permissão de acesso para as operações:
 * I - Incluir     - 1
 * P - Pesquisar - 2
 * A - Alterar     - 4
 * D - Deletar     - 8
 * Exemplos:
 *  temPriv(15, "D") retornará true, tem acesso para exclusão
 *  temPriv(7, "D") retornará false, pois só tem acesso para criação pesquisa e alteração
 */
function temPriv($priv, $operacao)
{
    if ($priv >= 8) { //Deletar?
        $priv -= 8;
        if ($operacao == 'D')
            return TRUE;
    }
    if ($priv >= 4) { //Alterar?
        $priv -= 4;
        if ($operacao == 'A')
            return TRUE;
    }
    if ($priv >= 2) { //Pesquisar?
        $priv -= 2;
        if ($operacao == 'P')
            return TRUE;
    }
    if ($priv >= 1) { //Incluir?
        $priv -= 1;
        if ($operacao == 'I')
            return TRUE;
    }
    return FALSE;
}

/**
 * Atualiza o privilégio da transação antiga para a nova caso seja "S" para permitir.
 * Esta função foi criada porque uma transação pode pertencer a vários grupos,
 * sendo necessário atualizar a transação já incluida em session("Transacoes")
 * Obs: utilizado em login.asp
 */
function atualizaPriv($privOld, $I, $P, $A, $D)
{

    //se o privilégio for igual a 15 quer dizer que o usuário poderá
    //criar, ler, alterar e excluir, não sendo necessário atualizar
    if ($privOld == 15)
        return 15;

    //se os parâmetros c-r-u-d forem todos "S" também não será necessário
    //atualizar o privilégio, pois o usuário já poderá realizar todos
    if ($I == "S" && $P == "S" && $A == "S" && $D == "S")
        return 15;

    //restaurando os privilégios já exsistentes na transação
    $incluir_ = "N";    //1
    $pesquisar_ = "N";    //2
    $alterar_ = "N";    //4
    $deletar_ = "N";    //8

    //verifica a permissão de excluir
    if ($privOld >= 8) {
        $privOld = $privOld - 8;
        $deletar_ = "S";
    }

    //verifica a permissão de alterar
    if ($privOld >= 4) {
        $privOld = $privOld - 4;
        $alterar_ = "S";
    }

    //verifica a permissão de ler
    if ($privOld >= 2) {
        $privOld = $privOld - 2;
        $pesquisar_ = "S";
    }

    //verifica a permissão de excluir
    if ($privOld >= 1) {
        $privOld = $privOld - 1;
        $incluir_ = "S";
    }

    //atualizando os privilégios da transação antiga para a nova
    if ($I == "S") $incluir_ = "S";
    if ($P == "S") $pesquisar_ = "S";
    if ($A == "S") $alterar_ = "S";
    if ($D == "S") $deletar_ = "S";

    return $this->priv($incluir_, $pesquisar_, $alterar_, $deletar_);
}

/**
 * Retorna o número de uma somatória através da string "S" ou "N" vinda do banco,
 * caso haja dúvida, pesquisar sobre questões somatórias
 * I - Incluir	- 1
 * P - Pesquisar	- 2
 * A - Alterar	- 4
 * D - Deletar	- 8
 * Obs: utilizado em login.asp
 */
function priv($I, $P, $A, $D)
{
    //iniciando com nenhum privilégio para a transação
    $priv = 0;
    if ($I == "S") $priv += 1;    //incluir 	= 1
    if ($P == "S") $priv += 2;    //pesquisar = 2
    if ($A == "S") $priv += 4;    //alterar 	= 4
    if ($D == "S") $priv += 8;    //deletar 	= 8
    return $priv;
}

/**
 * Testa se um usuário é master
 * @return boolean
 */
function isMaster()
{
    $CI =& get_instance();
    $user = $CI->session->userdata('user');
    if ($user->ismaster)
        return TRUE;
    else
        return FALSE;
}

/**
 * Informa se o usuário corrente tem a permissão de criar
 * @return boolean
 */
function isCreate($transaction)
{
    $CI =& get_instance();
    $transacoes = $CI->session->userdata('transacoes');
    $result = FALSE;
    if (isMaster()) {
        $result = TRUE;
    } else {
        if (array_key_exists($transaction, $transacoes)) {
            $result = temPriv($transacoes[$transaction], 'I');
        }
    }
    return $result;
}

/**
 * Informa se o usuário corrente tem a permissão de ler
 * @return boolean
 */
function isRead($transaction)
{
    $CI =& get_instance();
    $transacoes = $CI->session->userdata('transacoes');
    $result = FALSE;
    if (isMaster()) {
        $result = TRUE;
    } else {
        if (array_key_exists($transaction, $transacoes)) {
            $result = temPriv($transacoes[$transaction], 'P');
        }
    }
    return $result;
}

/**
 * Informa se o usuário corrente tem a permissão de alterar
 * @return boolean
 */
function isUpdate($transaction)
{
    $CI =& get_instance();
    $transacoes = $CI->session->userdata('transacoes');
    $result = FALSE;
    if (isMaster()) {
        $result = TRUE;
    } else {
        if (array_key_exists($transaction, $transacoes)) {
            $result = temPriv($transacoes[$transaction], 'A');
        }
    }
    return $result;
}

/**
 * Informa se o usuário corrente tem a permissão de excluir
 * @return boolean
 */
function isDelete($transaction)
{
    $CI =& get_instance();
    $transacoes = $CI->session->userdata('transacoes');
    $result = FALSE;
    if (isMaster()) {
        $result = TRUE;
    } else {
        if (array_key_exists($transaction, $transacoes)) {
            $result = temPriv($transacoes[$transaction], 'D');
        }
    }
    return $result;
}

/**
 * Informa se o usuario tem permissão aos itens de um menu "pai".(dropdown)
 * @return boolean
 **/
function IsMenuRead($str)
{
    $str = explode(',', $str);
    foreach ($str as $value) {
        if (isRead($value)) return TRUE;
    }
    return FALSE;
}