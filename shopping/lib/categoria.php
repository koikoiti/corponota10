<?php
	
	#Include nas funcoes do documento
	include('functions/banco-categoria.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancocategoria;
	
	#monta o auxilio para listar os produtos
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	
	#Pega a página
	$numPagina = $banco->RetornaPagina($this->PaginaAux);
	
	#Lista a categoria
	if($this->PaginaAux[1] == "pg" || !isset($this->PaginaAux[1])){
		$cat = $this->PaginaAux[0];
		$ordcat = $cat;
		$Categoria = $banco->ListaCategoria($Auxilio, $numPagina, $cat);
	}else{
		$sub = $this->PaginaAux[1];
		$ordcat = $this->PaginaAux[0] . "/" . $this->PaginaAux[1];;
		$Categoria = $banco->ListaSubcategoria($Auxilio, $numPagina, $sub);
	}
	
	$Paginacao = $banco->MontaPaginacao($numPagina, $this->PaginaAux, $this->Pagina);
		
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('categoria');
	$Conteudo = str_replace('<%CATEGORIA%>',$Categoria,$Conteudo);
	$Conteudo = str_replace('<%PAGINACAO%>',$Paginacao,$Conteudo);
	$Conteudo = str_replace('<%ORDCAT%>',$ordcat,$Conteudo);
?>