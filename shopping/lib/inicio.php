<?php
	
	#Include nas funcoes da tela principal
	include('functions/banco-inicio.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoinicio();
	
	#monta o auxilio para listar os produtos
	$Auxilio = $banco->CarregaHtml('itens/lista-produto-itens');
	$AuxilioLoja = $banco->CarregaHtml('itens/lista-loja-itens');
	
	$Produtos = $banco->ListaProdutosInicio($Auxilio);
	$Lojas = $banco->ListaLojasInicio($AuxilioLoja);
	
	#Imprime tela
	$Conteudo = $banco->CarregaHtml('inicio');
	$Conteudo = str_replace('<%URLPADRAO%>',UrlPadrao,$Conteudo);
	$Conteudo = str_replace('<%PRODUTOS%>',$Produtos,$Conteudo);
	$Conteudo = str_replace('<%LOJAS%>',$Lojas,$Conteudo);
?>