<?php
	$Docs = '';	
	$botaocancelar = '';
	
	#Include nas funcoes do documento
	include('functions/banco-doc.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancodoc();
	
	#Carrega o html de Auxilio
	$Auxilio = $banco->CarregaHtml('itens/lista-doc-itens');
	
	#Busca Documentos
	$Docs = $banco->ListaDoc($Auxilio);
	
	#verifica se foi cadastrado pelo GET , se teve um novo cadastro coloca mensagem.
	if($this->PaginaAux[0] == "insert"){
		$msg = MsgSucesso_Cadastro;
	}elseif($this->PaginaAux[0] == "update"){
		$msg = MsgSucesso_Atualiza;
	}elseif($this->PaginaAux[0] == "delete"){
		$msg = MsgSucesso_Deleta;
	}else{
		$msg = "";
	}
	
	#Verifica ordenação
	if($this->PaginaAux[0] == "order"){
		$ordena = $this->PaginaAux[1];
		switch($ordena){
			case "doc":
				$Docs = $banco->BuscaOrdenada("nome_d", $Auxilio);
				break;
			case "emp":
				$Docs = $banco->BuscaOrdenada("nome_e", $Auxilio);
				break;
			case "dte":
				$Docs = $banco->BuscaOrdenada("data_emissao", $Auxilio);
				break;
			case "dtv":
				$Docs = $banco->BuscaOrdenada("data_vencimento", $Auxilio);
				break;
		}
	}
	
	#Trabalha com o post do buscar
	if( isset($_POST["acao"]) && $_POST["acao"] != '' && $_POST["busca"] != '' ){
		$busca = strip_tags(trim(addslashes($_POST["busca"])));
		$op = strip_tags(trim(addslashes($_POST["campo"])));
		$botaocancelar = Botao_CancelarLista;
		switch ($op){
			case 1:
				$Docs = $banco->BuscaNaTabelaDoc($busca, "documento", $Auxilio);
				break;
			case 2:
				$Docs = $banco->BuscaNaTabelaDoc($busca, "empresa", $Auxilio);
				break;
			case 3:
				$Docs = $banco->BuscaNaTabelaDoc($busca, "data_emissao", $Auxilio);
				break;
			case 4:
				$Docs = $banco->BuscaNaTabelaDoc($busca, "data_vencimento", $Auxilio);
				break;
		}
	}
	
	#Monta botão novo
	$botao = $banco->MontaNovo("doc");
		
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('lista-doc');
	$Conteudo = str_replace('<%DOCS%>',$Docs,$Conteudo);
	$Conteudo = str_replace('<%BOTAO%>',$botao,$Conteudo);
	$Conteudo = str_replace('<%BOTAOBUSCAR%>', Botao_Buscar,$Conteudo);
	$Conteudo = str_replace('<%MSG%>',$msg,$Conteudo);
	$Conteudo = str_replace('<%BOTAOCANCELAR%>',$botaocancelar,$Conteudo);
?>