<?php

/*
 * 
 * 	$pagina = 1;
		#Coloca na variável a categoria que quer buscar
		$categoria = $this->PaginaAux[0];
		$where = "WHERE C.nome = '" . $categoria."'";
		if(strlen($this->PaginaAux[1]) < 6){
			$pagina = strstr($this->PaginaAux[1], "pg-");
			$pagina = str_replace("pg-", "", $pagina);
		}else{
			$subcategoria = $this->PaginaAux[1];
			$where = "WHERE S.nome = '" . $subcategoria."'".
					" AND C.nome = '" . $categoria."'";
		}
	}*/
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-categoria.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancocategoria;
	
	#monta o auxilio para listar os produtos
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	
	#Pega a página
	$pagina = $banco->PaginaCategoria($this->PaginaAux);
	
	#Lista a categoria
	if($this->PaginaAux[1] == "pg" || isset($this->PaginaAux[1])){
		$cat = $this->PaginaAux[0];
		$Categoria = $banco->ListaCategoria($Auxilio, $pagina, $cat);
		$Paginacao = $banco->MontaPaginacaoCat($pagina, $cat);
	}
	
	
		
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('categoria');
	$Conteudo = str_replace('<%CATEGORIA%>',$Categoria,$Conteudo);
	$Conteudo = str_replace('<%PAGINACAO%>',$Paginacao,$Conteudo);
?>