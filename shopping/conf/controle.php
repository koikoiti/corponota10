<?php
	session_start('login');
	include('functions/banco.php');
	include('tags.php');
	class controle{
		public function __construct(){
			$msg = '';
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
					$Conteudo = $banco->ChamaPhp('principal');
				}
			}	
		
			#Carrega Pagina Requisitada
			$SaidaHtml = $banco->CarregaHtml('modelo');
			$SaidaHtml = str_replace('<%CONTEUDO%>',$Conteudo,$SaidaHtml);
			$SaidaHtml = str_replace('<%MSG%>',$msg,$SaidaHtml);
			$SaidaHtml = str_replace('<%URLPADRAO%>',UrlPadrao,$SaidaHtml);

			#Imprimi tela
			echo $SaidaHtml;
			
		}
	}
?>