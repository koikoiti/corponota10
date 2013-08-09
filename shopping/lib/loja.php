<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-loja.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoloja;
	
	#Busca o order se tiver
	$orderUrl = $banco->RetornaOrder($this->PaginaAux);
	
	#Trabalha com ordenação
		switch($orderUrl){
			case "nome": $order = "P.nome ASC";
				break;
			case "menorMaior": $order = "P.preco ASC";
				break;
			case "maiorMenor": $order = "P.preco DESC";
				break;
			case "clicados": $order = "P.contaclick DESC";
				break;
		}
		
	$nomeloja = $this->PaginaAux[0];
	
	if ($banco->BuscaCategoria($this->PaginaAux)){
		$nomeloja.= '/'.$this->PaginaAux[1];
		$categoria = $this->PaginaAux[1];
	}

	 if ($banco->BuscaSubCategoria($this->PaginaAux)){
		$nomeloja.= '/'.$this->PaginaAux[2];
		$subcategoria = $this->PaginaAux[2];
	} 
	
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	
	$numPagina = $banco->RetornaPagina($this->PaginaAux);
	
	$Produtos = $banco->ListaProdutosLoja($Auxilio,$this->PaginaAux[0], $numPagina, $order, $categoria, $subcategoria);
	
	$Paginacao = $banco->MontaPaginacao($numPagina, $this->PaginaAux, $this->Pagina);
	
	
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('loja');
	$Conteudo = str_replace('<%PRODUTOS%>',$Produtos,$Conteudo);
	$Conteudo = str_replace('<%PAGINACAO%>',$Paginacao,$Conteudo);
	$Conteudo = str_replace('<%NOMELOJA%>',$nomeloja,$Conteudo);
?>