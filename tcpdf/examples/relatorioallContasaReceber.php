<?php
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');
include('databasestring.php');
header('Content-Type: text/html; charset=utf-8');
setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );

// ------- by xboxslim788@gmail.com --------------

// item, nome, valor, vencimento
$cwidth = array(10,50,25,15);

$TableWidth = 630;

class YOURPDF extends TCPDF {

	//Page header
	public function Header() {
		if ($this->header_xobjid === false) {
			// start a new XObject Template
			$this->header_xobjid = $this->startTemplate($this->w, $this->tMargin);
			$headerfont = $this->getHeaderFont();
			$headerdata = $this->getHeaderData();
			$this->y = $this->header_margin;
			if ($this->rtl) {
				$this->x = $this->w - $this->original_rMargin;
			} else {
				$this->x = $this->original_lMargin;
			}
			if (($headerdata['logo']) AND ($headerdata['logo'] != K_BLANK_IMAGE)) {
				$imgtype = TCPDF_IMAGES::getImageFileType(K_PATH_IMAGES.$headerdata['logo']);
				if (($imgtype == 'eps') OR ($imgtype == 'ai')) {
					$this->ImageEps(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
				} elseif ($imgtype == 'svg') {
					$this->ImageSVG(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
				} else {
          $image_file = K_PATH_IMAGES.'Logotipo.jpg';
					$this->Image($image_file, 20, 10, 20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
				}
				$imgy = $this->getImageRBY();
			} else {
				$imgy = $this->y;
			}
			$cell_height = $this->getCellHeight($headerfont[2] / $this->k);
			// set starting margin for text data cell
			if ($this->getRTL()) {
				$header_x = $this->original_rMargin + ($headerdata['logo_width'] * 1.1);
			} else {
				$header_x = $this->original_lMargin + ($headerdata['logo_width'] * 1.1);
			}
			$cw = $this->w - $this->original_lMargin - $this->original_rMargin - ($headerdata['logo_width'] * 1.1);
			$this->SetTextColorArray($this->header_text_color);
			// header title
			$this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);
			$this->SetX($header_x);
			$this->Cell($cw, $cell_height, $headerdata['title'], 0, 1, '', 0, '', 0);
			// header string
			$this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
			$this->SetX($header_x);
			$this->MultiCell($cw, $cell_height, $headerdata['string'], 0, '', 0, 1, '', '', true, 0, false, true, 0, 'T', false);
			// print an ending header line
			$this->SetLineStyle(array('width' => 0.85 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $headerdata['line_color']));
			$this->SetY((2.835 / $this->k) + max($imgy, $this->y));
			if ($this->rtl) {
				$this->SetX($this->original_rMargin);
			} else {
				$this->SetX($this->original_lMargin);
			}
			$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
			$this->endTemplate();
		}
		// print header template
		$x = 0;
		$dx = 0;
		if (!$this->header_xobj_autoreset AND $this->booklet AND (($this->page % 2) == 0)) {
			// adjust margins for booklet mode
			$dx = ($this->original_lMargin - $this->original_rMargin);
		}
		if ($this->rtl) {
			$x = $this->w + $dx;
		} else {
			$x = 0 + $dx;
		}
		$this->printTemplate($this->header_xobjid, $x, 0, 0, 0, '', '', false);
		if ($this->header_xobj_autoreset) {
			// reset header xobject template at each page
			$this->header_xobjid = false;
		}
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

function cabecalho($idcoligada)
{
  global $servername, $username, $password, $dbname;
  $link = mysqli_connect($servername, $username, $password, $dbname);
  
	$sql = "select * from V_PDFREL_PRODUTOS where IDCOLIGADA = $idcoligada";
      
  
  $res = $link->query($sql);
	
  $x = 0;
  while ($Dados = $res->fetch_assoc()) {
    		
		$cnpj[$x] = $Dados["CNPJ"];
    $nome[$x] = $Dados["RAZAOSOCIAL"];
    $endereco[$x] = $Dados["ENDERECOCOMPLETO"];
    $imagem[$x] = $Dados["LOGO"];    
		$x++;
	}
  
  $Cont = $x;
  //echo "Cont = " .$Cont;
  
  echo "<div style='text-align:center; font-size:25px; font-weight:bold;'>RELATÓRIO DE PRODUTOS</div>
			<div>
        <table style='font-size: 12px;' width='100%' border='0' cellspacing='0' cellpadding='0'>";
        
  for($x = 0; $x < $Cont; $x++){
			echo "<tr>
				<td align='center' valign='top'>";
        $img_template = '<img src="data:image/png;base64,'.base64_encode($imagem[0]) . '" alt="Primeira Foto" height="41" />';
        echo $img_template;
				echo "	 
				</td>
				<td width='100%' valign='top'>
					<div style='padding-left:20px;'>
						<div style='font-weight:bold;'>$nome[$x]</div>
						<div>CNPJ: $cnpj[$x]</div>
						<div>$endereco[$x]</div>
					</div>
				</td>
			  </tr>";
	}	
  echo "</table>
			</div>
			  <div><hr></div>";
  
  //echo $nome[$x];
  
  mysqli_close($link);
  //return $imagem;	
}

function FUN_cabecalho($idcoligada, $opc){
  
  global $servername, $username, $password, $dbname;
  $link = mysqli_connect($servername, $username, $password, $dbname);
  
	$sql = "select * from V_PDFREL_COLIGADA where IDCOLIGADA = $idcoligada";      
  
  $res = $link->query($sql);
	
  $x = 0;
  while ($Dados = $res->fetch_assoc()) {
    		
		$cnpj[$x] = $Dados["CNPJ"];
    $nome[$x] = $Dados["RAZAOSOCIAL"];
    $endereco[$x] = $Dados["ENDERECOCOMPLETO"];
    $imagem[$x] = base64_encode($Dados["LOGO"]);    
		$x++;
	}    
  mysqli_close($link);
  
  if($opc == 1){
    return $cnpj;
  }
  if($opc == 2){
    return $nome;
  }
  if($opc == 3){
    return $endereco;
  }
  if($opc == 4){
    return $imagem;
  }
  //return $imagem;  
}


function listaprodutos($idpedido, $item)
{
  global $servername, $username, $password, $dbname;
  global $cwidth;
  $link = mysqli_connect($servername, $username, $password, $dbname);

	$sql = "SELECT A.ID, A.IDPEDIDO, C.NOME, A.VALORPAGO, A.VENCIMENTO, A.PARCELA,
  case A.STATUSCONTA when 1 then 
  'PENDENTE' 
  when 2 then 
  'PAGO' 
  when 3 then 
  'CANCELADO' 
  else '' END as STATUSCONTA,
YEAR(A.VENCIMENTO) AS ANO,
MONTH(A.VENCIMENTO) AS MES

FROM G006_CONTASRECEBER AS A 
INNER JOIN G003_PEDIDO AS B ON (A.IDPEDIDO = B.ID) 
INNER JOIN C001_PESSOAS AS C ON (B.IDCLIENTE = C.ID) where A.IDPEDIDO = " .$idpedido;  
  
  $fhtml = "";    
  $res = $link->query($sql);
  
	$x = 0;
  
 	$id = array();
  $pedido = array();
	$cliente = array();
	$statusdavenda = array();
  $valorpago = array();
  $vencimento = array();
	
  
  while ($Dados = $res->fetch_assoc()) {
    $id[$x] = $Dados["ID"];
    $pedido[$x] = $Dados["IDPEDIDO"];
		$cliente[$x] = $Dados["NOME"];
    $valorpago[$x] = $Dados["VALORPAGO"];
    $vencimento[$x] = $Dados["VENCIMENTO"];;
		$statusdavenda[$x] = $Dados["STATUSCONTA"];	
    
		$x++;
	}   
  
  $Cont = $x;
  
	
	$i = 0;
  
  $tr1 = ' style="border-bottom:1px #000 solid; background-color:#E9E9E9; font-size:12px;"';
  $tr2 = ' style="border-bottom:1px #000 solid; font-size:12px;"';
  
  $celula_Litem = ' style="font-weight: normal;
				text-align:center;
				padding:3px;
				
				color:#000;
				width:'.$cwidth[0] .'%;
        height: 50px;
				vertical-align:middle;"';
  
   $celula_Lnome = ' style="font-weight: normal;
				text-align: center;
				padding:3px;
				
				color:#000;
				width:'.$cwidth[1] .'%;
        height: 50px;
				vertical-align:middle;"';
  
  $celula_Lvalor = ' style="font-weight: normal;
				text-align: right;
				padding:3px;
				
				color:#000;
				width:'.$cwidth[2] .'%;
        height: 50px;
				vertical-align:middle;"';
  
   
  $celula_Lcodigo = ' style="font-weight: normal;
				text-align: center;
				padding: 3px;
				
				color:#000;
				width:'.$cwidth[3] .'%;
        height: 50px;
				vertical-align: middle;"'; 
    
    
 
  
 
	 for($x = 0; $x < $Cont; $x++){
    if($item % 2 == 0){ $fhtml .= '<tr'.$tr1.'>';} else { $fhtml .= '<tr'.$tr2.'>';} 
    $fhtml .= '
					<td' .$celula_Litem .'>'.$pedido[$x].'</td>					
					<td' .$celula_Lnome .'>' .utf8_encode($cliente[$x]).'</td>         
					<td' .$celula_Lcodigo . '>'. date('d/m/Y', strtotime($vencimento[$x])).'</td> 
          <td' .$celula_Lvalor .'>R$ ' .number_format($valorpago[$x],2,",",".").'</td>
				</tr>';
       $item++;
    }	
   
  
  mysqli_close($link);
  return $fhtml;
}

function listafornecedor()
{
  header('Content-Type: text/html; charset=utf-8');
  setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
  $sql = "";
  global $servername, $username, $password, $dbname;
  global $TableWidth;
  global $cwidth;
  $link = mysqli_connect($servername, $username, $password, $dbname);
  
  $fhtml = "";
  
  //c9f0f895fb98ab9159f51fd0297e236d
  
 // if($_GET["pID"] == 0){
  
    $sql = "SELECT A.ID, A.IDPEDIDO, C.NOME, A.VALORPAGO, A.VENCIMENTO, A.PARCELA,
    case A.STATUSCONTA when 1 then 
  'PENDENTE' 
  when 2 then 
  'PAGO' 
  when 3 then 
  'CANCELADO' 
  else '' END as STATUSCONTA, 
YEAR(A.VENCIMENTO) AS ANO,
MONTH(A.VENCIMENTO) AS MES

FROM G006_CONTASRECEBER AS A 
INNER JOIN G003_PEDIDO AS B ON (A.IDPEDIDO = B.ID) 
INNER JOIN C001_PESSOAS AS C ON (B.IDCLIENTE = C.ID) ";   
    
 // }else 
 // {
  /*
    $sql .= "SELECT ID, UPPER(NOME) AS FORNECEDOR
  	FROM C001_PESSOAS
	  WHERE md5(ID) = '$id' AND TIPO = 2 AND ID IN (SELECT DISTINCT IDFORNECEDOR FROM E005_PRODUTOS WHERE ATIVO = 1)
	  ORDER BY NOME";
  */
 // }
  $id = array();
  $pedido = array();
	$cliente = array();
	$statusdavenda = array();
	
	
  $res = $link->query($sql);
  
	$x = 0;
  while ($Dados = $res->fetch_assoc()) {
    		
		$pedido[$x] = $Dados["IDPEDIDO"];
		$cliente[$x] = $Dados["NOME"];
		$statusdavenda[$x] = $Dados["STATUSCONTA"];	
    
		$x++;
	} 
  
   $Cont = $x;
  //echo "Cont = " .$Cont;
  
  
  
  $celula_Titem = ' style="font-weight:bold;
				text-align:center;
				padding:3px;
				background-color:#003366;
				color:#fff;
				width:'.$cwidth[0] .'%;
				vertical-align:middle;"';
  
  $celula_Tnome = ' style="font-weight: bold;
				text-align:center;
				padding:3px;
				background-color:#003366;
				color:#fff;
				width:'.$cwidth[1] .'%;
				vertical-align:middle;"';
  
  $celula_Tvalor = ' style="font-weight: bold;
				text-align:center;
				padding:3px;
				background-color:#003366;
				color:#fff;
				width:'.$cwidth[2] .'%;
				vertical-align:middle;"';
  
   
  $celula_Tcodigo = ' style="font-weight: bold;
				text-align: center;
				padding: 3px;
				background-color: #003366;
				color: #fff;
				width:'.$cwidth[3] .'%;
				vertical-align: middle;"';    
    
   $fhtml .= '<h4 style="text-align: center;"> RELATÓRIO DE CLIENTES </h4>';
  
  for($x = 0; $x < $Cont; $x++){
  
  $fhtml .= '<b>Cliente:&nbsp;&nbsp;</b>' .utf8_encode($cliente[$x]) .'<br><b>Pedido:&nbsp;<b> '.$pedido[$x].'<br><b>Status da Venda:&nbsp;<b>'.$statusdavenda[$x].'<br>
				<div style="padding-left:200px;">
				<table width="'.$TableWidth.'" border="0" cellspacing="0" cellpadding="0">
				  <tr>					
          <td' .$celula_Titem .'>PEDIDO No.</td>
          <td' .$celula_Tnome .'>CLIENTE</td>
          <td' .$celula_Tcodigo .'>VENCIMENTO </td>	
          <td' .$celula_Tvalor .'>VALOR A PAGAR R$</td>
				  </tr>';    
          
          $item = 1;
          
         $fhtml .= listaprodutos($pedido[$x], $item++);
          
          /*
          $res = $link->query($sql);
          while ($Dados = $res->fetch_assoc()){
            
            foreach($Dados as $_ds){
				      listaprodutos($_ds[0], $item++);
		        }
            
          }
          */
    
  $fhtml .= '</table>
				</div><br><hr>';  
  } 
  mysqli_close($link);
  return $fhtml;
}


// create new PDF document
$pdf = new YOURPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MDI ERP Soluções');
$pdf->SetTitle('Visualização');
$pdf->SetSubject('Relatório de Produto');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$cnpj = FUN_cabecalho(1, 1);
$hnome = FUN_cabecalho(1, 2);
$endereco = FUN_cabecalho(1, 3);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $hnome[0], $cnpj[0] ."\n" .$endereco[0], array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print

/*
$html = <<<EOD
<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;
*/

//$cabecalho = "cabeçalho";
//$rodape = "rodapé";
//$conteudo = "Conteudo";

//$cabecalho = cabecalho(1);
//$rodape =  htmlspecialchars($_GET["rodape"]);
//$conteudo = htmlspecialchars($_GET["Conteudo"]);

$idcoligada = 1;

$img_template = FUN_cabecalho($idcoligada, 4);
$cnpj = FUN_cabecalho($idcoligada, 1);
$nome = FUN_cabecalho($idcoligada, 2); 
$endereco = FUN_cabecalho($idcoligada, 3);




$inhtml = listafornecedor();
// debug echo $inhtml;
//echo $inhtml;
  
$html = <<<EOD
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>RELATÓRIO DE PEDIDO</title>
		<style type='text/css'>
		
			#thead_0 { display: table-header-group; }
			#thead { page-break-before: always; }
			#tbody { display: table-row-group; }
			#tfoot { display: table-footer-group; }
		
			body {
				padding:0px 20px 0px 20px;
				font-family:Arial, sans-serif;
				font-size:11px;
			}
						
			.conteiner {
				border:1px solid gray;
				-webkit-border-radius: 8px;
				border-radius: 8px;
				padding:10px;
				margin-bottom:10px;
				page-break-inside: inherit;
			}

			.conteiner:hover{
				background-color:#FFFF99;
			}
			
			@media screen {
				div.topo, div.rodape {
					display: none;
				}
			}
			
			@media print {
				div.topo {
					position: fixed;
					top: 0;
				}
				
				div.rodape {
					position: fixed;
					bottom: 0;
				}
			}
			
			.grupo
			{
				font-size:14px;
				font-weight: bold;
				text-align:justify;
				padding:5px;
				background-color:#003366;
				color:#fff;
				margin-bottom:10px;
			}
			
			.celula
			{
				font-family: Arial, 'Times New Roman';
				font-size: 12px;
			}
			
			.celula.Titem
			{
				font-weight: bold;
				text-align:center;
				padding:3px;
				background-color:#003366;
				color:#fff;
				width:5px;
				vertical-align:middle;
			}
				
			.celula.Litem
			{
				text-align:center;
				padding:3px;
				width:5px;
				vertical-align:top;
				border-bottom:1px #000 solid;
			}
	
			.celula.Tcodigo
			{
				font-weight: bold;
				text-align:center;
				padding:3px;
				background-color:#003366;
				color:#fff;
				width:5px;
				vertical-align:middle;
			}
				
			.celula.Lcodigo
			{
				text-align:center;
				padding:3px;
				width:5px;
				vertical-align:top;
				border-bottom:1px #000 solid;
			}

			.celula.Tnome
			{
				font-weight: bold;
				text-align:center;
				padding:3px;
				background-color:#003366;
				color:#fff;
				width:5px;
				vertical-align:middle;
			}
				
			.celula.Lnome
			{
				text-align:left;
				padding:3px;
				width:5px;
				vertical-align:top;
				border-bottom:1px #000 solid;
			}

			.celula.Tvalor
			{
				font-weight: bold;
				text-align:center;
				padding:3px;
				background-color:#003366;
				color:#fff;
				width:5px;
				vertical-align:middle;
			}
			
							
			.celula.Lvalor
			{
				text-align:right;
				padding:3px;
				width:5px;
				vertical-align:top;
				border-bottom:1px #000 solid;
			}
			
		</STYLE>
		</HEAD>
		<BODY style='padding:20px;'>
			
			$inhtml
			
			<br>
			<br>
			
			
		</BODY>
		</HTML>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.


$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
