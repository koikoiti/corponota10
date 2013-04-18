<?php
	
	#Include nas funcoes da tela principal
	include('functions/banco-principal.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoprincipal();
	
	
	#Imprime tela
	$Conteudo = $banco->CarregaHtml('principal');
?>