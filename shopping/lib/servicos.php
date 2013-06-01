<?php
	#Declara Variaveis
	
	#Include nas funcoes do documento
	include('functions/banco-servico.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancoservico;
	
	#monta o auxilio para listar os produtos
	#$Auxilio = $banco->CarregaHtml('itens/lista-servico-itens');
	
	#$Servicos = $banco->ListaServico($Auxilio);
	
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('servico');
	#$Conteudo = str_replace('<%SERVICOS%>',$Servicos,$Conteudo);
?>