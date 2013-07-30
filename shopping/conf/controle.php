<?php
	session_start('login');
	include('functions/banco.php');
	include('tags.php');
	
	class controle{
		public function __construct(){
			
			$banco = new banco;
			$banco->Conecta();
			$banco->CarregaPaginas();

			if ($banco->Pagina){	
				#Se tiver ele vai buscar no banco a pagina requisitada
				$num_rows = $banco->BuscaPagina($banco->Pagina);
				
				#Se tiver no banco a pagina ele chama!
				if($num_rows){	
					#Verifica se ele tem acesso a pagina requisitada
					$Conteudo = $banco->ChamaPhp($banco->Pagina);	
					
				}else{
					$Conteudo = $banco->ChamaPhp('404');
				}
			}else{
					$Conteudo = $banco->ChamaPhp('inicio');
			}
			
			$Lateral01 = $banco->Lateral01();
			$Lateral02 = $banco->Lateral02();
			$slider = $banco->Slider();
			$navegacao = $banco->Navegacao();
			$subcategorias = $banco->Subcategorias();
			
			#Carrega Pagina Requisitada
			if($banco->Pagina == "redireciona"){
				$SaidaHtml = $banco->CarregaHtml('redireciona');
				$SaidaHtml = str_replace('<%CONTEUDO%>',$Conteudo,$SaidaHtml);
			}else{
				$SaidaHtml = $banco->CarregaHtml('modelo');
				$SaidaHtml = str_replace('<%CONTEUDO%>',$Conteudo,$SaidaHtml);
				$SaidaHtml = str_replace('<%URLPADRAO%>',UrlPadrao,$SaidaHtml);
				$SaidaHtml = str_replace('<%URLMODELO%>',UrlModelo,$SaidaHtml);
				$SaidaHtml = str_replace('<%NAVEGACAO%>',$navegacao,$SaidaHtml);
				$SaidaHtml = str_replace('<%SUBCATEGORIAS%>',$subcategorias,$SaidaHtml);
				$SaidaHtml = str_replace('<%LATERAL01%>',$Lateral01,$SaidaHtml);
				$SaidaHtml = str_replace('<%LATERAL02%>',$Lateral02,$SaidaHtml);
				$SaidaHtml = str_replace('<%SLIDER%>',$slider,$SaidaHtml);
				$SaidaHtml = str_replace('<%TITULOLATERAL01%>','Lojas',$SaidaHtml);
			}

			#Imprime tela
			echo utf8_encode($SaidaHtml);
			
		}
	}
?>