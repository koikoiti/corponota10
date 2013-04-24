<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-categoria.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancocategoria;
	
	#monta o auxilio para listar os produtos
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	
	if($this->PaginaAux[0]){
		#Coloca na variável a categoria que quer buscar
		$categoria = $this->PaginaAux[0];
		$where = "WHERE S.idcategoria = " . $categoria;
	}
	if($this->PaginaAux[1]){
		$categoria = $this->PaginaAux[0];
		$subcategoria = $this->PaginaAux[1];
		$where = "WHERE P.idsubcategoria = " . $subcategoria . 
				 " AND S.idcategoria = " . $categoria;
	}
	
	$Categoria = $banco->ListaCategoria($Auxilio, $where);
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('categoria');
	$Conteudo = str_replace('<%CATEGORIA%>',$Categoria,$Conteudo);
?>