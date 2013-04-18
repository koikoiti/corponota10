<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-comparar.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancocomparar;
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('comparar');
?>