<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-produto.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoproduto;
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('produto');
?>