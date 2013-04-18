<?php
	class bancoprincipal extends banco{
		
		#Função que busca os documentos que estão para vencer (10 dias de antecedência)
		function DashboardDocumentos(){
			#Setando a data de hoje
			$anoH = date("Y");
			$mesH = date("m");
			$diaH = date("d");
			#Criando timestamp (em segundos) com a data de hoje
			$timestampH = mktime(0, 0, 0, $mesH, $diaH, $anoH);
			$alerta_documentos = "";
			#Sql buscando todos os documentos
			$Sql = "SELECT D.*, E.nome AS nome_e, S.nome AS nome_d 
					FROM c_docs D
					INNER JOIN fixo_empresas E ON D.empresa = E.idempresa
					INNER JOIN fixo_documentos S ON D.documento = S.iddocumento
					WHERE DATEDIFF(DATE(data_vencimento), DATE(NOW())) <= 10
					ORDER BY D.data_vencimento ASC
					";
			$result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
			if($num_rows){
				while($rs = mysql_fetch_array($result , MYSQL_ASSOC) ){
					$data = explode("-", $rs["data_vencimento"]);
					#Criando timestamp (em segundos) da data de vencimento do documento
					$timestampC = mktime(0, 0, 0, $data[1], $data[2], $data[0]);
					#Calculando a diferença dos tempos
					$diferenca = $timestampC - $timestampH;
					#Transformando a diferença em dias
					$dias = $diferenca / (60 * 60 * 24);
					#Arredondando os dias para baixo
					$dias = floor($dias);
					#Testando: Se os dias forem positivos e com até 10 dias de antecedência, dá um aviso normal
					if($dias > 0 && $dias <= 10){
						$alerta_documentos .= '<div class="alert alert-info">
													<button class="close" data-dismiss="alert" type="button">×</button>
													<strong>Aviso!</strong>
													Falta(m) <b>'.$dias.'</b> dia(s) para vencer o documento <b>"'.$rs['nome_d'].'"</b> da empresa <b>'.$rs['nome_e'].'</b>.
						  					  </div>';
					#Testando: Se os dias forem negativos, documento já venceu. Dá aviso de atenção
					}elseif($dias < 0){
						$alerta_documentos .= '<div class="alert alert-error">
													<button class="close" data-dismiss="alert" type="button">×</button>
													<strong>Atenção!</strong>
													O documento <b>"'.$rs['nome_d'].'"</b> da empresa <b>'.$rs['nome_e'].'</b> venceu faz(em) <b>'.abs($dias).'</b> dia(s)!	
											  </div>';
					}elseif($dias == 0){
						$alerta_documentos .= '<div class="alert alert-error">
													<button class="close" data-dismiss="alert" type="button">×</button>
													<strong>Atenção!</strong>
													O documento <b>"'.$rs['nome_d'].'"</b> da empresa <b>'.$rs['nome_e'].'</b> vence <b>HOJE</b>!
											  </div>';
					}
				}
			}
			return $alerta_documentos;
		}
		
		#Função que busca os acompanhamentos não-visualizados (linha vermelha)
		function DashboardAcomp(){
			$alerta_acomp = "";
			#Sql buscando somente os acompanhamentos com visualizado = 0
			$Sql = "SELECT A . * , E.nome AS nome_e
					FROM c_acomp A
					INNER JOIN fixo_empresas E ON A.idempresa = E.idempresa 
					WHERE A.visualizado = 0
					ORDER by A.pasta ASC
					";
			$result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
			#Se tiver algum acomp sem visualizar, dá o aviso
			if($num_rows){
				while($rs = mysql_fetch_array($result , MYSQL_ASSOC) ){
					$alerta_acomp .= '<div class="alert">
										<button class="close" data-dismiss="alert" type="button">×</button>
										<strong>Aviso!</strong>
										O pregão <b>"'.$rs['pregao'].'"</b> da empresa <b>'.$rs['nome_e'].'</b>, pasta <b>'.$rs['pasta'].'</b> ainda não foi visualizado!	
									  </div>';
				}
			#Senão, dá mensagem de OK
			}else{
				$alerta_acomp .= '<div class="alert alert-success">
									<button class="close" data-dismiss="alert" type="button">×</button>
									<strong>OK!</strong>
									O acompanhamento está em dia!
								 </div>';
			}
			return $alerta_acomp;
		}
		
		#Função que busca os avisos (10 dias de antecedência)
		function DashboardAvisos(){
			#Setando a data de hoje
			$anoH = date("Y");
			$mesH = date("m");
			$diaH = date("d");
			#Criando timestamp (em segundos) com a data de hoje
			$timestampH = mktime(0, 0, 0, $mesH, $diaH, $anoH);
			$alerta_avisos = "";
			#Sql buscando os avisos com até 10 dias de antecedência
			$Sql = "SELECT * FROM c_avisos 
					WHERE DATEDIFF(DATE(data), DATE(NOW())) <= 10
					AND DATEDIFF(DATE(data), DATE(NOW())) >= 0
					ORDER BY data ASC
					";
			$result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
			if($num_rows){
				while($rs = mysql_fetch_array($result , MYSQL_ASSOC) ){
					$data = explode("-", $rs["data"]);
					#Criando timestamp (em segundos) da data de vencimento do documento
					$timestampC = mktime(0, 0, 0, $data[1], $data[2], $data[0]);
					#Calculando a diferença dos tempos
					$diferenca = $timestampC - $timestampH;
					#Transformando a diferença em dias
					$dias = $diferenca / (60 * 60 * 24);
					#Arredondando os dias para baixo
					$dias = floor($dias);
					#Testando: Se os dias forem positivos e com até 10 dias de antecedência, dá um aviso normal
					if($dias > 0 && $dias <= 10){
						$alerta_avisos .= '<div class="alert alert-success">
													<button class="close" data-dismiss="alert" type="button">×</button>
													<strong>Aviso!</strong>
													Falta(m) <b>'.$dias.'</b> dia(s) para <b>"'.$rs['assunto'].'"</b>!
						  					  </div>';
					}elseif($dias == 0){
						$alerta_avisos .= '<div class="alert alert-success">
													<button class="close" data-dismiss="alert" type="button">×</button>
													<strong>Aviso!</strong>
													<b>"'.$rs['assunto'].'"</b> é HOJE!
						  					  </div>';
					}
				}
			}
			return $alerta_avisos;
		}
	
	}