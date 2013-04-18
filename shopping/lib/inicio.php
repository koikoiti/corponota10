<?php
	
	#Include nas funcoes da tela principal
	include('functions/banco-inicio.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoinicio();
	
	
	#Imprime tela
	$Conteudo = $banco->CarregaHtml('inicio');
?>