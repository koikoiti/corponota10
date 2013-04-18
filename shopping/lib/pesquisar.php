<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-pesquisar.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancopesquisar;
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('pesquisar');
?>