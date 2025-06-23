<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
    require_once APPPATH."/third_party/numerosLetras/NumeroALetras.php";
    require_once APPPATH."/third_party/multicell/PDF_MC_Table.php";
class Proforma extends MY_Controller {
  public $dist;
  public $imgSize;

  public function __construct() {
    parent::__construct();
    $this->load->library('FileStorage');
    $this->load->config('storage', TRUE);
  }

  public function generar($id, $dist=14, $imgSize=12,$firma=0) {

    $this->load->model('Proforma_model');
    $this->dist = $dist;
    $this->imgSize = $imgSize;
    $proforma = $this->Proforma_model->getProforma($id);
    $items = $this->Proforma_model->getProformaItems($id);
    $params = get_object_vars($proforma);
    $year = date('y',strtotime($proforma->fecha));
    $mes = date('n',strtotime($proforma->fecha));
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $params["firma"]=$firma;

    $this->load->library('Proforma_lib', $params);
        $this->pdf = new Proforma_lib($params);
        $this->pdf->AddPage('P','Letter');
        $this->pdf->AliasNbPages();
        $this->pdf->SetAutoPageBreak(true,47); //40
        $this->pdf->SetTitle('PRO' . '-' .$proforma->num. '-' . $year);
        $this->items($items,$proforma);

      $this->pdf->Output('I',$proforma->num . '-' . $year . ' | ' . $proforma->clienteNombre . ' | ' . $proforma->tipo . ' | ' . strtoupper($meses[($mes)-1]) . '.pdf',true);
  }  private function getImageFromSpaces($url) {
    if (empty($url)) {
      $url = 'https://images.hergo.app/hg/articulos/check_blue.png';
    }
    
    // Log para debug
    error_log("Procesando imagen: " . $url);
    
    $tempDir = sys_get_temp_dir() . '/hergo_temp_images';
    if (!file_exists($tempDir)) {
      mkdir($tempDir, 0777, true);
    }
    error_log("Directorio temporal: " . $tempDir);

    // Obtener la extensión original del archivo
    $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
      $extension = 'jpg'; // default extension
    }

    // Crear un nombre de archivo único basado en la URL manteniendo la extensión
    $tempFile = $tempDir . '/' . md5($url) . '.' . $extension;
    
    // Si la imagen temporal ya existe, usarla
    if (!file_exists($tempFile)) {      // Descargar desde Spaces
      error_log("Intentando descargar: " . $url);
      $imageContent = @file_get_contents($url);
      if ($imageContent === false) {
        error_log("Error descargando imagen: " . $url);
        // Si falla la descarga, usar una imagen por defecto
        $defaultUrl = 'https://images.hergo.app/hg/articulos/check_blue.png';
        error_log("Intentando usar imagen por defecto: " . $defaultUrl);
        $imageContent = @file_get_contents($defaultUrl);
        if ($imageContent === false) {
          error_log("Error fatal: No se pudo cargar ni siquiera la imagen por defecto");
          throw new Exception("No se pudo cargar la imagen por defecto");
        }
        $extension = 'png';
        $tempFile = $tempDir . '/check_blue.png';
      }
      error_log("Guardando imagen en: " . $tempFile);
      file_put_contents($tempFile, $imageContent);
    }
    
    return $tempFile;
  }

  public function items($items,$proforma)
  {
        $l = 0;
        $n = 1;
        $total = 0;
        foreach ($items as $item) {
          $total += $item->total;
          $this->pdf->SetFillColor(255,255,255);
          $this->pdf->SetTextColor(0,0,0);
          $this->pdf->SetFont('Roboto','',8);
            $this->pdf->Cell(5,5,$n++,$l,0,'C',0);            // Debug info - Usar print_r para ver la estructura completa
            error_log("DEBUG =====================================");
            error_log("Procesando item: " . print_r($item, true));
            
            $imageUrl = '';
            
            // Primero intentar usar ImagenUrl
            if (!empty($item->ImagenUrl)) {
                $imageUrl = 'https://images.hergo.app/' . ltrim($item->ImagenUrl, '/');
                error_log("Usando ImagenUrl directamente: " . $imageUrl);
            }
            // Si no hay ImagenUrl pero hay Imagen, construir la URL
            else if (!empty($item->Imagen)) {
                $imageUrl = 'https://images.hergo.app/hg/articulos/' . ltrim($item->Imagen, '/');
                error_log("Usando campo Imagen: " . $imageUrl);
            }

            try {
                if (!empty($imageUrl)) {
                    error_log("Intentando cargar imagen desde: " . $imageUrl);
                    $imgPath = $this->getImageFromSpaces($imageUrl);
                    if (file_exists($imgPath)) {
                        error_log("Imagen descargada exitosamente en: " . $imgPath);
                        $this->pdf->Cell(25,10,$this->pdf->Image($imgPath, $this->pdf->GetX() + 5, $this->pdf->GetY()+1, $this->imgSize ),$l,0,'C',0);
                    } else {
                        throw new Exception("Archivo temporal no existe");
                    }
                } else {
                    throw new Exception("No hay URL de imagen");
                }
            } catch (Exception $e) {
                error_log("Error procesando imagen: " . $e->getMessage() . ". Usando default");
                $defaultImgPath = $this->getImageFromSpaces('https://images.hergo.app/hg/articulos/check_blue.png');
                $this->pdf->Cell(25,10,$this->pdf->Image($defaultImgPath, $this->pdf->GetX() + 10, $this->pdf->GetY()+2, 5 ),$l,0,'R',0);
            }
            error_log("DEBUG =====================================");

            $this->pdf->Cell(15,5,iconv('UTF-8', 'windows-1252//TRANSLIT', $item->codigo),$l,0,'C',0);
            $this->pdf->MultiCell(45,5,iconv('UTF-8', 'windows-1252//TRANSLIT', ($item->descrip)),$l,'L',0);
            $this->pdf->SetXY(100,$this->pdf->GetY()-5);
            $this->pdf->Cell(15,5,$item->marca,$l,0,'C',0);
            $this->pdf->Cell(15,5,$item->industria,$l,0,'C',0);
            $this->pdf->Cell(15,5,$item->tiempoEntrega,$l,0,'C',0);
            $this->pdf->Cell(15,5,number_format($item->cantidad, 2, ".", ","),$l,0,'R',0);
            $this->pdf->Cell(10,5,$item->uni,$l,0,'R',0);
            $this->pdf->Cell(20,5,number_format($item->precioLista, 2, ".", ","),$l,0,'R',0);
            $this->pdf->Cell(20,5,number_format($item->total, 2, ".", ","),$l,0,'R',0);
          $this->pdf->Ln($this->dist);
          $this->pdf->Line($this->pdf->GetX(),$this->pdf->GetY(),$this->pdf->GetX()+200,$this->pdf->GetY());
        }

        $this->pdf->SetFont('Roboto','B',8);
        $this->pdf->Cell(180,5,'Total:','T',0,'R',1); 
        $this->pdf->Cell(20,5,number_format($total, 2, ".", ","),'T',0,'R',1); 
        $this->pdf->Ln(5);
        if ($proforma->porcentajeDescuento) {
          $sub = 'Descuento ' . $proforma->porcentajeDescuento . '%';
          $this->pdf->Cell(180,5,$sub,'0',0,'R',1); 
          $this->pdf->Cell(20,5,number_format($proforma->descuento, 2, ".", ","),'0',0,'R',1); 
          $this->pdf->Ln(5);
          $this->pdf->Cell(180,5,'Total Final','0',0,'R',1); 
          $this->pdf->Cell(20,5,number_format($total - $proforma->descuento, 2, ".", ","),'TB',0,'R',1); 
        }
        $entera = intval($proforma->total);
        $ctvs = round(($proforma->total - $entera) * 100);
        $ctvs = sprintf('%02d',$ctvs);
        $ctvs = ($ctvs == 0) ? '00' : $ctvs;
        $literal = NumeroALetras::convertir($entera).$ctvs.'/100 '. $proforma->moneda;
        $this->pdf->Ln(1);
        $this->pdf->Cell(7,6,'SON:',$l,0,'L',1);
        $this->pdf->Cell(156,6,iconv('UTF-8', 'windows-1252//TRANSLIT', $literal),$l,0,'l',1);
        $this->pdf->SetFillColor(20,60,190);
        $this->observaciones($proforma->glosa);
  }
  public function observaciones($glosas)
  {
    if ($glosas) {
      $this->pdf->Ln(6);
      $this->pdf->SetFillColor(20,60,190);
      $this->pdf->SetFont('Roboto','B',8); 
      $this->pdf->SetTextColor(255,255,255);
      $this->pdf->Cell(200,5, iconv('UTF-8', 'windows-1252//TRANSLIT', 'OBSERVACIONES: '),1,0,'C',1);
      $this->pdf->Ln(5);
      $this->pdf->SetTextColor(0,0,0);
      $this->pdf->SetFont('Roboto','',8);
      $glosas = explode('<br />',$glosas);
      foreach ($glosas as $glosa) {
        $glosa = iconv('UTF-8', 'windows-1252//TRANSLIT', trim($glosa));
        $this->pdf->MultiCell(200,5,$glosa,0,'L',0);
      }
      $this->pdf->SetDrawColor(20,60,190);
      $this->pdf->SetLineWidth(1);
      $this->pdf->Line(10,$this->pdf->GetY(),210,$this->pdf->GetY());
    }
  }
}