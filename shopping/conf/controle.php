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
		
			#Carrega Pagina Requisitada
			$SaidaHtml = $banco->CarregaHtml('modelo');
			$SaidaHtml = str_replace('<%CONTEUDO%>',$Conteudo,$SaidaHtml);
			$SaidaHtml = str_replace('<%URLPADRAO%>',UrlPadrao,$SaidaHtml);

			#Imprime tela
			echo $SaidaHtml;
			
		}
	}
?>