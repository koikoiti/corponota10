<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-loja.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoloja;
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('loja');
?>