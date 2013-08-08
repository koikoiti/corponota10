<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-pesquisar.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancopesquisar;
	
	#Trabalha com ordenação
	if($this->PaginaAux[1] == "order"){
		switch($this->PaginaAux[2]){
			case "nome": $order = "P.nome ASC";
			break;
			case "menorMaior": $order = "P.preco ASC";
			break;
			case "maiorMenor": $order = "P.preco DESC";
			break;
			case "clicados": $order = "P.contaclick DESC";
			break;
		}
	}else{
		$order = "P.nome ASC";
	}
	
	#Coloca na variável a tag que quer buscar
	$busca = $this->PaginaAux[0];
	
	#monta o auxilio para listar os produtos
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	
	#Pega a página
	$numPagina = $banco->RetornaPagina($this->PaginaAux);
	
	$Pesquisar = $banco->ListaPesquisa($Auxilio, $busca, $numPagina, $order);
	
	$Paginacao = $banco->MontaPaginacao($numPagina, $this->PaginaAux, $this->Pagina);
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('pesquisar');
	$Conteudo = str_replace('<%PESQUISAR%>',$Pesquisar,$Conteudo);
	$Conteudo = str_replace('<%PAGINACAO%>',$Paginacao,$Conteudo);
	$Conteudo = str_replace('<%BUSCA%>',$busca,$Conteudo);
?>