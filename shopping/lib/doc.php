<?php
	#Declara Variaveis
	$data_e = '';
	$data_v = '';
	$listadoc = '<select tabindex="1" name="documento">';
	$empresa = '<select tabindex="2" name="empresa">';	
	$botao = Botao_Salvar;
	$botaocancelar = Botao_Cancelar;
	$botaodeletar = '';

	#Include nas funcoes do documento
	include('functions/banco-doc.php');
	
	#Instancia objeto que vai tratar o banco de dados dessa pagina
	$banco = new bancodoc;
	
	#Trabalha com o editar documento
	if ($this->PaginaAux[0] == 'editar'){
		$botao = Botao_Atualizar;
		$id = $this->PaginaAux[1];
		$deletar = UrlPadrao."doc/deletar/".$id;
		$botaodeletar = "<button tabindex='22' onclick=\"if(confirm('Tem certeza que deseja deletar?'))location.href='$deletar'\" class=\"btn btn-danger\" type=\"button\">Deletar</button>";
		$result = $banco->BuscaDoc($id);
		$num_rows = $banco->Linha($result);
		
		if($num_rows){
			$rs = mysql_fetch_array($result , MYSQL_ASSOC);
			$id = $rs['iddoc'];
			$iddocumento = $rs['documento'];
			$idempresa = $rs['empresa'];
			$data_e = date("d/m/Y",strtotime($rs['data_emissao']));
			$data_v = date("d/m/Y",strtotime($rs['data_vencimento']));
		}
	}
	
	#Lista as empresas e monta o select
	$result = $banco->ListaEmpresa();
	while($aux = mysql_fetch_array($result, MYSQL_ASSOC)){
		$selected = '';
		if($idempresa == $aux['idempresa']){
			$selected = 'selected';
		}
		$empresa .= '<option value="'.$aux['idempresa'].'"' .$selected.'>'.$aux['nome'].'</option>';
	}
	$empresa .= "</select>";
	
	#Lista os documentos e monta o select
	$result = $banco->ListaDocumentos();
	while($aux = mysql_fetch_array($result, MYSQL_ASSOC)){
		$selected = '';
		if($iddocumento == $aux['iddocumento']){
			$selected = 'selected';
		}
		$listadoc .= '<option value="'.$aux['iddocumento'].'"' .$selected.'>'.$aux['nome'].'</option>';
	}
	$listadoc .= "</select>";
	
	#Trabalha com o deletar documento
	if ($this->PaginaAux[0] == 'deletar'){
		$id = $this->PaginaAux[1];
		$banco->AtualizaDoc($id);
		$banco->DeletaDoc($id);
		$banco->RedirecionaPara('lista-doc/delete');
	}
	
	#Trabalha com o download do documento
	if ($this->PaginaAux[0] == 'download'){
		$id = $this->PaginaAux[1];
		$banco->DownloadDoc($id);
	}
	
	#Trabalha com Post
	if( isset($_POST["acao"]) && $_POST["acao"] != '' ){
		$data_e = strip_tags(trim(addslashes($_POST["data_emissao"])));
		$newdata_e = implode("-",array_reverse(explode("/",$data_e)));
		$data_v = strip_tags(trim(addslashes($_POST["data_vencimento"])));
		$newdata_v = implode("-",array_reverse(explode("/",$data_v)));
		$idempresa = strip_tags(trim(addslashes($_POST["empresa"])));
		$iddocumento = strip_tags(trim(addslashes($_POST["documento"])));
		$documento = $_FILES["documento"];
		#Monta caminho do documento
		$nomeEmpresa = $banco->BuscaNomeEmpresa($idempresa);
		$nomeDoc = $banco->BuscaNomeDoc($iddocumento);
		$caminho_documento = "arq/documentos/" . $nomeEmpresa . " - ". $nomeDoc . " - ". $newdata_v . ".pdf";
		if(empty($documento['name']) && $botao != Botao_Atualizar){
			$msg = MsgErro_SelecioneArquivo;
			goto imprime;
		}elseif($botao == Botao_Atualizar){
			#EDITAR
			#Se o arquivo não foi atualizado, atualiza somente os outros campos
			if(empty($documento['name'])){
				#Renomeia o documento com os dados alterados
				$banco->RenomeiaDoc($id, $caminho_documento);
				$SqlUpdate = "Update c_docs SET documento = '".$iddocumento."' , empresa = '".$idempresa."', data_emissao = '".$newdata_e."', data_vencimento = '".$newdata_v."' , caminho_doc = '".$caminho_documento."' where iddoc = '".$id."'";
			}elseif(substr($documento['name'],(strlen($documento['name'])-4),strlen($documento['name'])) != ".pdf"){
			#Testa se não é pdf
				$msg = MsgErro_NaoPdf;
				goto imprime;
			}else{
			#PDF = atualiza tudo, substituindo o arquivo
				$banco->AtualizaDoc($id);
				move_uploaded_file($documento["tmp_name"], $caminho_documento);
				$SqlUpdate = "Update c_docs SET documento = '".$iddocumento."' , empresa = '".$idempresa."', data_vencimento = '".$newdata_v."', data_emissao = '".$newdata_e."' , caminho_doc = '".$caminho_documento."' where iddoc = '".$id."'";
			}
			$banco->Execute($SqlUpdate);
			$banco->RedirecionaPara('lista-doc/update');
		}else{
			if(substr($documento['name'],(strlen($documento['name'])-4),strlen($documento['name'])) != ".pdf"){
			#Testa se é pdf				
				$msg = MsgErro_NaoPdf;
				goto imprime;
			}else{
				#CADASTRO
				#Faz o upload do documento para seu respectivo caminho
				move_uploaded_file($documento["tmp_name"], $caminho_documento);
				$SqlInsert = "Insert Into c_docs (iddoc, documento, empresa, data_emissao, data_vencimento, caminho_doc) VALUES ('', '".$iddocumento."','".$idempresa."','".$newdata_e."','".$newdata_v."','".$caminho_documento."')";
				$banco->Execute($SqlInsert);
				$banco->RedirecionaPara('lista-doc/insert');
			}
		}
	}
	
	imprime:
	#Imprime Valores
	$Conteudo = $banco->CarregaHtml('doc');
	$Conteudo = str_replace('<%ID%>',$id,$Conteudo);
	$Conteudo = str_replace('<%DOCUMENTO%>',$listadoc,$Conteudo);
	$Conteudo = str_replace('<%EMPRESA%>',$empresa,$Conteudo);
	$Conteudo = str_replace('<%DATAEMISSAO%>',$data_e,$Conteudo);
	$Conteudo = str_replace('<%DATAVENCIMENTO%>',$data_v,$Conteudo);
	$Conteudo = str_replace('<%MSG%>',$msg,$Conteudo);
	$Conteudo = str_replace('<%BOTAO%>',$botao,$Conteudo);
	$Conteudo = str_replace('<%BOTAOCANCELAR%>',$botaocancelar,$Conteudo);
	$Conteudo = str_replace('<%BOTAODELETAR%>',$botaodeletar,$Conteudo);
?>