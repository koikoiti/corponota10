<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-buscar.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancobuscar;
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('buscar');
?>