<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-loja.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoloja;
	
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	$Produtos = $banco->ListaProdutosLoja($Auxilio,$this->PaginaAux[0]);
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('loja');
	$Conteudo = str_replace('<%URLPADRAO%>',UrlPadrao,$Conteudo);
	$Conteudo = str_replace('<%PRODUTOS%>',$Produtos,$Conteudo);
	
?>