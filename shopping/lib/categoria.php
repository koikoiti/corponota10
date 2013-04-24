<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-categoria.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancocategoria;
	
	#monta o auxilio para listar os produtos
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	
	#Coloca na variável a categoria que quer buscar
	$categoria = $this->PaginaAux[0];
	
	$Categoria = $banco->ListaCategoria($Auxilio, $categoria);
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('categoria');
	$Conteudo = str_replace('<%CATEGORIA%>',$Categoria,$Conteudo);
?>