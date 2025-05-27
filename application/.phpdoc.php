<?php
/**
 * CodeIgniter IDE Helper
 * 
 * This file is designed to help IDEs like VS Code with Intelephense
 * to provide better autocompletion for CodeIgniter projects.
 * This file is not included in the application, it's only for IDE support.
 * 
 * @see https://codeigniter.com/userguide3/general/core_classes.html
 * @see https://codeigniter.com/userguide3/general/creating_libraries.html
 */

/**
 * Core Controller Class - IDE Helper
 * 
 * @property CI_Loader $load The loader class
 * @property CI_Input $input The input class
 * @property CI_Security $security The security class
 * @property CI_Form_validation $form_validation Form validation class
 * @property CI_Session $session Session class
 * @property CI_DB_query_builder $db Database class
 * @property CI_Output $output Output class
 * @property CI_Email $email Email class
 * @property CI_Lang $lang Language class
 * @property CI_Config $config Config class
 * @property CI_Hooks $hooks Hooks class
 * @property CI_URI $uri URI class
 * @property CI_Upload $upload Upload class
 * @property CI_Calendar $calendar Calendar class
 * @property CI_Typography $typography Typography class
 * @property CI_Table $table Table class
 * @property CI_User_agent $agent User agent class
 * @property CI_Parser $parser Parser class
 * @property CI_Image_lib $image_lib Image manipulation class
 * @property CI_Router $router Router class
 * @property CI_Benchmark $benchmark Benchmark class
 */
class CI_Controller {}

/**
 * Core Model Class - IDE Helper
 * 
 * @property CI_DB_query_builder $db Database class
 */
class CI_Model {}

/**
 * Extended Controller Class - IDE Helper
 * All the properties from CI_Controller plus:
 * 
 * @property array $datos Common data for views
 * @property LibAcceso $libacceso Access library
 * @property Ion_auth $ion_auth Authentication library
 * @property General_model $General_model General model
 * @property Admin_model $Admin_model Admin model
 * @property Ingresos_model $Ingresos_model Ingresos model
 * @property Egresos_model $Egresos_model Egresos model
 * @property FPDF $pdf PDF library
 */
class MY_Controller extends CI_Controller {}
