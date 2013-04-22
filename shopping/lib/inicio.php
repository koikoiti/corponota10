<?php
	
	#Include nas funcoes da tela principal
	include('functions/banco-inicio.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoinicio();
	
	#monta o auxilio para listar os produtos
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	
	$Produtos = $banco->ListaProdutosInicio($Auxilio);
	
	#Imprime tela
	$Conteudo = $banco->CarregaHtml('inicio');
	$Conteudo = str_replace('<%PRODUTOS%>',$Produtos,$Conteudo);
?>