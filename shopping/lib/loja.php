<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-loja.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoloja;
	
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
	
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	
	$numPagina = $banco->RetornaPagina($this->PaginaAux);
	
	$Produtos = $banco->ListaProdutosLoja($Auxilio,$this->PaginaAux[0], $numPagina, $order);
	
	$Paginacao = $banco->MontaPaginacao($numPagina, $this->PaginaAux, $this->Pagina);
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('loja');
	$Conteudo = str_replace('<%PRODUTOS%>',$Produtos,$Conteudo);
	$Conteudo = str_replace('<%PAGINACAO%>',$Paginacao,$Conteudo);
	$Conteudo = str_replace('<%NOMELOJA%>',$this->PaginaAux[0],$Conteudo);
?>