<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';

class Excel extends CI_Controller
{
	public $arr;
    public $file;
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Excel_model");
        $this->arr = [];

	}
    public function importdata()
	{
		$this->load->view('import_data');

        if(isset($_POST["submit"]))
		{
            $this->procesarFile('');
        }
        if(isset($_POST["save"])){
            $this->procesarFile('save');
        }
	}
    public function procesarFile($save)
    {
        /* $date = "13/06/2015";
        echo DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        return;  */

        $fila = 0;
        $c = 0;
        $this->file = $_FILES['file']['tmp_name'];
            if ($this->file) {
                if (($gestor = fopen($this->file, "r")) !== FALSE) {

                    while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {
                        //*** bnb ***/
                        /* $data = [
                            'fila' => $fila,
                            'banco' => intval($datos[0]),
                            'fecha' => $datos[1],
                            'hora' => $datos[2],
                            'oficina' => trim($datos[3]),
                            'descripcion' => trim($datos[4]),
                            'referencia' => trim($datos[5]),
                            'codigo' => trim($datos[6]),
                            'itf' => floatval($datos[7]),
                            'adicional' => trim($datos[11]),
                            'cheque' => trim($datos[12]),
                            'tipo' => intval($datos[13]),
                            'monto' => floatval($datos[14]),
                        ]; */
                        //*** bcp ***/
                        /* $data = [
                            'fila' => $fila,
                            'banco' => intval($datos[0]),
                            'fecha' => $datos[1],
                            'hora' => $datos[2],
                            'oficina' => trim($datos[8]),
                            'descripcion' => trim($datos[4]),
                            'referencia' => trim($datos[7]),
                            'codigo' => trim($datos[10]),
                            'itf' => 0,
                            'adicional' => trim($datos[3]),
                            'cheque' => trim($datos[12]),
                            'tipo' => intval($datos[11]),
                            'monto' => floatval($datos[5]),
                        ]; */
                        //*** union ***/
                        $data = [
                            'fila' => $fila,
                            'banco' => intval($datos[0]),
                         //Time::createFromFormat('d/m/Y', $datos[1])->format('Y-m-d'),
                            'fecha' => $datos[1],
                            'hora' => '',
                            'oficina' => trim($datos[2]),
                            'descripcion' => trim($datos[3]),
                            'referencia' => trim($datos[4]),
                            'codigo' => trim($datos[4]),
                            'itf' => 0,
                            'adicional' => trim($datos[7]),
                            'cheque' => trim($datos[9]),
                            'tipo' => intval($datos[8]),
                            'monto' => floatval($datos[5]),
                        ];
                        if ($save == 'save') {
                            unset($data['fila']);
                        }
                        if($c<>0){
                            array_push($this->arr , $data);
                        }
                        echo '<pre>';
                            print_r($this->arr);
                            echo '<br>';
                        echo '</pre>'; 

                        if($c<>0 && $save == 'save'){
                            $this->Excel_model->saverecord($data);
                        }

                        $c = $c + 1;
                        $fila += 1;

                    }
                    fclose($gestor);
                    $creditos = 0;
                    $debitos = 0;
                    $itf = 0;
                    $cheques = 0;
                    foreach ($this->arr as $key => $value) {
                        $itf += $value['itf'];
                        if ($value['cheque'] <> '') {
                            $cheques += 1;
                        }
                        if ($value['tipo'] == 1) {
                            $creditos += $value['monto'];
                        } else if($value['tipo'] == 0) {
                            $debitos += $value['monto'];
                        }
                    }

                    echo '<pre>';
                   //print_r($arr);
                        print_r('Creditos: ' . $creditos);
                        echo '<br>';
                        print_r('Debitos: ' . $debitos*-1);
                        echo '<br>';
                        print_r('ITF: ' . $itf);
                        echo '<br>';
                        print_r('Operaciones: ' . $fila - 1);
                        echo '<br>';
                        print_r('Cheques: ' . $cheques);
                        echo '<br>';
                        if($c<>0 && $save == 'save'){
                            echo "sucessfully import data !";
                        }
                    echo '</pre>';


                }
            }
    }
    public function saludar()
    {
        echo 'hola a todos';
    }


}