<?php

	class bancodoc extends banco{
		
		#Funcao que lista os Docs
		function ListaDoc($Auxilio){
			$Banco_Vazio = "Banco esta Vazio";
			#Query Busca Docs
			$Sql = "SELECT D.*, E.nome AS nome_e, S.nome AS nome_d 
					FROM c_docs D
					INNER JOIN fixo_empresas E ON D.empresa = E.idempresa
					INNER JOIN fixo_documentos S ON D.documento = S.iddocumento
					ORDER BY data_vencimento
					";
			$result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
			
			#Monta no Html a Listagem
			if ($num_rows){
				while( $rs = mysql_fetch_array($result , MYSQL_ASSOC) )
				{
					$Linha = $Auxilio;
					$Linha = str_replace('<%ID%>',$rs['iddoc'],$Linha);
					$Linha = str_replace('<%DOCUMENTO%>', $rs['nome_d'], $Linha);
					$Linha = str_replace('<%EMPRESA%>',$rs['nome_e'],$Linha);
					$Linha = str_replace('<%CAMINHODOC%>',$rs['caminho_doc'],$Linha);
					$Linha = str_replace('<%DATAEMISSAO%>', date("d/m/Y", strtotime($rs['data_emissao'])),$Linha);
					$Linha = str_replace('<%DATAVENCIMENTO%>', date("d/m/Y", strtotime($rs['data_vencimento'])),$Linha);
					$Docs .= $Linha;
				}
			}else{
				$Docs = '<tr class="alternate-row">
							<td colspan=5>'.MsgAviso_BancoVazio.'</td>
						</tr>';
			}
			return $Docs;
		}
		
		#Funcao que lista documentos
		function ListaDocumentos(){
			$Sql = "Select * from fixo_documentos order by nome";
			$result = parent::Execute($Sql);
			return $result;
		}
		
		#Funcao que busca documento por id
		function BuscaDoc($id)
		{
			$Sql = "Select * from c_docs where iddoc='".$id."'";
			$result = parent::Execute($Sql);
			return $result;
		}
		
		#Funcao que atualiza o documento depois de um update
		function AtualizaDoc($id){
			$Sql = "SELECT *
					FROM c_docs
					WHERE iddoc = '". $id ."'
					";
			$result = parent::Execute($Sql);
			$rs = mysql_fetch_array($result , MYSQL_ASSOC);
			$caminho = $rs['caminho_doc'];
			unlink($caminho);
		}
		
		#Funcao que deleta o documento
		function DeletaDoc($id){
			$Sql = "DELETE FROM c_docs 
					WHERE iddoc = '".$id."'
					";
			$result = parent::Execute($Sql);
			
		}
		
		#Funcao que renomeia o documento depois de um update
		function RenomeiaDoc($id, $novo)
		{
			$Sql = "SELECT *
					FROM c_docs
					WHERE iddoc = '". $id ."'
					";
			$result = parent::Execute($Sql);
			$rs = mysql_fetch_array($result , MYSQL_ASSOC);
			$antigo = $rs['caminho_doc'];
			rename($antigo, $novo);
		}
		
		#Funcao que busca auto_increment da tabela c_docs
		function BuscaMaxId()
		{
			$Sql = "SHOW TABLE STATUS LIKE 'c_docs'";
			$result = parent::Execute($Sql);
			$rs = mysql_fetch_array($result , MYSQL_ASSOC);
			return $rs['Auto_increment'];
		}
		
		#Funcao que retorna o nome do documento
		function BuscaNomeDoc($id){
			$Sql = "SELECT *
					FROM fixo_documentos
					WHERE iddocumento = '".$id."'
					";
			$result = parent::Execute($Sql);
			$rs = mysql_fetch_array($result , MYSQL_ASSOC);
			return $rs['nome'];
		}
		
		#Função que faz o download do documento
		function DownloadDoc($id){
			$Sql = "SELECT D.*, E.nome AS nome_e, S.nome AS nome_d 
					FROM c_docs D
					INNER JOIN fixo_empresas E ON D.empresa = E.idempresa
					INNER JOIN fixo_documentos S ON D.documento = S.iddocumento
					WHERE iddoc = '".$id."'					
					";
			$result = parent::Execute($Sql);
			$rs = mysql_fetch_array($result , MYSQL_ASSOC);
			$caminho_doc = $rs['caminho_doc'];
			$nome = $rs['nome_e'] . " - " . $rs['nome_d'] . " - ". $rs['data_vencimento'] .".pdf";
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=$nome");
			header("Content-Length: ".filesize($caminho_doc));
			header("Accept-Ranges: bytes");
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-transfer-encoding: binary");
			 
			@readfile($caminho_doc);
			 
			exit();
		}
		
		#Busca na tabela Doc
		function BuscaNaTabelaDoc($q,$coluna,$Auxilio){
			if($coluna == "documento"){
				$Sql = "SELECT D.*, E.nome AS nome_e, S.nome AS nome_d 
						FROM c_docs D
						INNER JOIN fixo_empresas E ON D.empresa = E.idempresa
						INNER JOIN fixo_documentos S ON D.documento = S.iddocumento
						WHERE S.nome LIKE '%".$q."%'
						";
			}elseif($coluna == "empresa"){
				$Sql = "SELECT D.*, E.nome AS nome_e, S.nome AS nome_d
						FROM c_docs D
						INNER JOIN fixo_empresas E ON D.empresa = E.idempresa
						INNER JOIN fixo_documentos S ON D.documento = S.iddocumento
						WHERE E.nome LIKE '%".$q."%'
						";
			}else{
				$Sql = "SELECT D.*, E.nome AS nome_e, S.nome AS nome_d
						FROM c_docs D
						INNER JOIN fixo_empresas E ON D.empresa = E.idempresa
						INNER JOIN fixo_documentos S ON D.documento = S.iddocumento
						WHERE D.".$coluna." LIKE '%".$q."%'
						";
			}
			
			$result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
				
			#Monta no Html a Listagem
			if ($num_rows){
				while( $rs = mysql_fetch_array($result , MYSQL_ASSOC) )
				{
					$Linha = $Auxilio;
					$Linha = str_replace('<%ID%>',$rs['iddoc'],$Linha);
					$Linha = str_replace('<%DOCUMENTO%>', $rs['nome_d'], $Linha);
					$Linha = str_replace('<%EMPRESA%>',$rs['nome_e'],$Linha);
					$Linha = str_replace('<%CAMINHODOC%>',$rs['caminho_doc'],$Linha);
					$Linha = str_replace('<%DATAEMISSAO%>', date("d/m/Y", strtotime($rs['data_emissao'])),$Linha);
					$Linha = str_replace('<%DATAVENCIMENTO%>', date("d/m/Y", strtotime($rs['data_vencimento'])),$Linha);
					$Docs .= $Linha;
				}
			}else{
				$Docs = '<tr class="alternate-row">
							<td colspan=5>'.MsgAviso_BuscaVazio.'</td>
						</tr>';
			}
			return $Docs;
			}
			
			#Busca Ordenada
			function BuscaOrdenada($coluna, $Auxilio){
				$Sql = "SELECT D.*, E.nome AS nome_e, S.nome AS nome_d 
					FROM c_docs D
					INNER JOIN fixo_empresas E ON D.empresa = E.idempresa
					INNER JOIN fixo_documentos S ON D.documento = S.iddocumento
					ORDER BY ".$coluna."
					";
				$result = parent::Execute($Sql);
				$num_rows = parent::Linha($result);
				#Monta no Html a Listagem
				if ($num_rows){
					while( $rs = mysql_fetch_array($result , MYSQL_ASSOC) )
					{
						$Linha = $Auxilio;
						$Linha = str_replace('<%ID%>',$rs['iddoc'],$Linha);
						$Linha = str_replace('<%DOCUMENTO%>', $rs['nome_d'], $Linha);
						$Linha = str_replace('<%EMPRESA%>',$rs['nome_e'],$Linha);
						$Linha = str_replace('<%CAMINHODOC%>',$rs['caminho_doc'],$Linha);
						$Linha = str_replace('<%DATAEMISSAO%>', date("d/m/Y", strtotime($rs['data_emissao'])),$Linha);
						$Linha = str_replace('<%DATAVENCIMENTO%>', date("d/m/Y", strtotime($rs['data_vencimento'])),$Linha);
						$Docs .= $Linha;
					}
				}else{
					$Docs = '<tr class="alternate-row">
							<td colspan=5>'.MsgAviso_BancoVazio.'</td>
						</tr>';
				}
				return $Docs;
			}
		
	}
?>