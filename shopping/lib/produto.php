<?php
	#Declara Variaveis

	#Include nas funcoes do documento
	include('functions/banco-produto.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoproduto;
	
	#monta o auxilio para listar os produtos
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	$AuxilioUnico = $banco->CarregaHtml('itens/lista-produto-unico');
	
	if($this->PaginaAux[0] == 'redi'){
		$link = $this->PaginaAux[2];
		echo $link;
	}
	
	if($this->PaginaAux[0]){
		$idproduto = $this->PaginaAux[0];
		$Produto = $banco->ListaProduto($AuxilioUnico,$idproduto);
		#Lista Produtos Similares
		$idsubcategoria = $banco->SubCategoriaProduto($idproduto);
		$ProdutoSimilar = $banco->ListaProdutoSimilar($Auxilio, $idproduto, $idsubcategoria);
	}
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('produto');
	$Conteudo = str_replace('<%PRODUTO%>',$Produto,$Conteudo);
	$Conteudo = str_replace('<%PRODUTOSIMILAR%>',$ProdutoSimilar,$Conteudo);
?>