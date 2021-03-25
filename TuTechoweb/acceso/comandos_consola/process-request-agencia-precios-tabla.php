<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if(isset($_POST["agenciaChoice"]) && isset($_POST['paisChoice'])){
    // Capture selected departamento
    $agencia = $_POST["agenciaChoice"];
    $pais = $_POST['paisChoice'];

    // Conexion con la database

    $tutechodb = "tutechodb_" . $pais;

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    $tutechodb_internacional = "tutechodb_internacional";

    try {
    	$conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    // Recuperar departamentos

    $nivel_acceso = $_SESSION['nivel_acceso'];
    $array_acceso = [1,11];

    if (in_array($nivel_acceso, $array_acceso) !== false) {


        $consulta_pais_info =	$conexion_internacional->prepare("SELECT moneda, moneda_code, anticretico_existe FROM paises WHERE pais = :pais");
        $consulta_pais_info->execute([':pais' => $pais]);
        $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);

        $consulta_info_agencia =	$conexion->prepare("SELECT express, departamento, location_tag FROM agencias WHERE id = :id");
        $consulta_info_agencia->execute([':id' => $agencia]);
        $info_agencia	=	$consulta_info_agencia->fetch(PDO::FETCH_ASSOC);

        $agencia_tag = $info_agencia['departamento'] . '_' .$info_agencia['location_tag'];

        $json_path = '../../agencias/' . $pais . '/' . $agencia_tag . '/tabla_precios.json';

        $moneda = $pais_info['moneda'] . $pais_info['moneda_code'];
        $anticretico = $pais_info['anticretico_existe'];
        $express = $info_agencia['express'];



        if (file_exists($json_path)) {
           $json = file_get_contents($json_path);
           $data = json_decode($json, true);

           function check_select($value, $comparison){
            if($value == $comparison){
                return 'selected';
            }else {
                return '';
            };
           };

           echo "
            <h2 class='label_tabla'>Venta " . ($anticretico == 1 ? 'o Anticretico' : '') . " de Bienes Inmuebles</h2>

            <div class='tabla_venta tabla'>

                <div class='labels_tabla row'>
                <div class='col_label'>
                    <p>RANGOS</p>
                </div>
                <div class='col_label'>
                    <p>EXCLUSIVO</p>
                </div>
                <div class='col_label'>
                    <p>NO-EXCLUSIVO</p>
                </div>
                </div>

                <div class='first_row row'>
                    <div class='rango col'>
                        <span>Hasta&nbsp</span>
                        <input type='text' value='" . $data['venta']['first']['rango']['max'] . "' class='max'>
                        <span class='moneda'>&nbsp" . $moneda . "</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='" . $data['venta']['first']['exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['venta']['first']['exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['venta']['first']['exclusivo']['tipo'], 'porcentage') . ">% PVF</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='" . $data['venta']['first']['no_exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['venta']['first']['no_exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['venta']['first']['no_exclusivo']['tipo'], 'porcentage') . ">% PVF</option>
                            <option value='no_disponible' " . check_select($data['venta']['first']['no_exclusivo']['tipo'], 'no_disponible') . ">NO HAY</option>
                        </select>
                    </div>
                </div>";

                foreach ($data['venta']['intermediate'] as $data_int) {
                    echo"
                    <div class='int_row row'>
                        <div class='rango col'>
                            <input type='text' value='" . $data_int['rango']['min'] . "' class='min'>
                            <p>&nbsp-&nbsp</p>
                            <input type='text' value='" . $data_int['rango']['max'] . "' class='max'></input>
                            <span class='moneda'>&nbsp" . $moneda . "</span>
                        </div>
                        <div class='exclusivo col'>
                            <input type='text' value='" . $data_int['exclusivo']['monto'] . "' class='monto'>
                            <select class='tipo'>
                                <option value='fijo' " . check_select($data_int['no_exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                                <option value='porcentage' " . check_select($data_int['no_exclusivo']['tipo'], 'porcentage') . ">% PVF</option>
                            </select>
                        </div>
                        <div class='no_exclusivo col'>
                            <input type='text' value='" . $data_int['no_exclusivo']['monto'] . "' class='monto'>
                            <select class='tipo'>
                                <option value='fijo' " . check_select($data_int['no_exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                                <option value='porcentage' " . check_select($data_int['no_exclusivo']['tipo'], 'porcentage') . ">% PVF</option>
                                <option value='no_disponible' " . check_select($data_int['no_exclusivo']['tipo'], 'no_disponible') . ">NO HAY</option>
                            </select>
                            <span class='btn btn_quitar btn_quitar_row1'><i class='fas fa-times-circle'></i></span>
                        </div>
                    </div>
                    ";
                };

                echo "<div class='last_row row'>
                    <div class='rango col'>
                        <span class='btn btn_agregar1 btn_agregar_row' data1='" . $moneda . "'><i class='fas fa-plus-circle'></i><i class='fas fa-caret-right'></i></span>
                        <span>Superior a&nbsp</span>
                        <input type='text' value='" . $data['venta']['last']['rango']['min'] . "' class='min'>
                        <span class='moneda'>&nbsp" . $moneda . "</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='" . $data['venta']['last']['exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['venta']['last']['exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['venta']['last']['exclusivo']['tipo'], 'porcentage') . ">% PVF</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='" . $data['venta']['last']['no_exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['venta']['last']['no_exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['venta']['last']['no_exclusivo']['tipo'], 'porcentage') . ">% PVF</option>
                            <option value='no_disponible' " . check_select($data['venta']['last']['no_exclusivo']['tipo'], 'no_disponible') . ">NO HAY</option>
                        </select>
                    </div>
                </div>

                <div class='lotes_row row'>
                    <div class='rango col'>
                        <span>Por lotes de inmuebles</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='" . $data['venta']['lotes']['exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['venta']['lotes']['exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['venta']['lotes']['exclusivo']['tipo'], 'porcentage') . ">% PVF</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='" . $data['venta']['lotes']['no_exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['venta']['lotes']['no_exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['venta']['lotes']['no_exclusivo']['tipo'], 'porcentage') . ">% PVF</option>
                            <option value='no_disponible' " . check_select($data['venta']['lotes']['no_exclusivo']['tipo'], 'no_disponible') . ">NO HAY</option>
                        </select>
                    </div>
                </div>
                <div class='max_lotes_row row'>
                    <span class='col'></span>
                    <span class='col'></span>
                    <span class='col col_max_lotes'><input type='text' value='" . $data['venta']['lotes']['max_lotes'] . "' class='max_lotes' placeholder='MAX #lotes'></span>
                    
                </div>
            </div>

            <h2 class='label_tabla'>Alquiler de Bienes Inmuebles</h2>

            <div class='tabla_alquiler tabla'>

                <div class='labels_tabla row'>
                <div class='col_label'>
                    <p>RANGOS</p>
                </div>
                <div class='col_label'>
                    <p>EXCLUSIVO</p>
                </div>
                <div class='col_label'>
                    <p>NO-EXCLUSIVO</p>
                </div>
                </div>

                <div class='first_row row'>
                    <div class='rango col'>
                        <span>Hasta&nbsp</span>
                        <input type='text' value='" . $data['alquiler']['first']['rango']['max'] . "' class='max'>
                        <span class='moneda'>&nbsp" . $moneda . "</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='" . $data['alquiler']['first']['exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['alquiler']['first']['exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['alquiler']['first']['exclusivo']['tipo'], 'porcentage') . ">% del Alquiler</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='" . $data['alquiler']['first']['no_exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['alquiler']['first']['no_exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['alquiler']['first']['no_exclusivo']['tipo'], 'porcentage') . ">% del Alquiler</option>
                            <option value='no_disponible' " . check_select($data['alquiler']['first']['no_exclusivo']['tipo'], 'no_disponible') . ">NO HAY</option>
                        </select>
                    </div>
                </div>";

                foreach ($data['alquiler']['intermediate'] as $data_int) {
                   echo"
                   <div class='int_row row'>
                        <div class='rango col'>
                            <input type='text' value='" . $data_int['rango']['min'] . "' class='min'>
                            <p>&nbsp-&nbsp</p>
                            <input type='text' value='" . $data_int['rango']['max'] . "' class='max'></input>
                            <span class='moneda'>&nbsp" . $moneda . "</span>
                        </div>
                        <div class='exclusivo col'>
                            <input type='text' value='" . $data_int['exclusivo']['monto'] . "' class='monto'>
                            <select class='tipo'>
                                <option value='fijo' " . check_select($data_int['exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                                <option value='porcentage' " . check_select($data_int['exclusivo']['tipo'], 'porcentage') . ">% del Alquiler</option>
                            </select>
                        </div>
                        <div class='no_exclusivo col'>
                            <input type='text' value='" . $data_int['no_exclusivo']['monto'] . "' class='monto'>
                            <select class='tipo'>
                                <option value='fijo' " . check_select($data_int['no_exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                                <option value='porcentage' " . check_select($data_int['no_exclusivo']['tipo'], 'porcentage') . ">% del Alquiler</option>
                                <option value='no_disponible' " . check_select($data_int['no_exclusivo']['tipo'], 'no_disponible') . ">NO HAY</option>
                            </select>
                            <span class='btn btn_quitar btn_quitar_row2'><i class='fas fa-times-circle'></i></span>
                        </div>
                    </div>
                   ";
                };


                echo "<div class='last_row row'>
                    <div class='rango col'>
                        <span class='btn btn_agregar2 btn_agregar_row' data1='" . $moneda . "'><i class='fas fa-plus-circle'></i><i class='fas fa-caret-right'></i></span>
                        <span>Superior a&nbsp</span>
                        <input type='text' value='" . $data['alquiler']['last']['rango']['min'] . "' class='min'>
                        <span class='moneda'>&nbsp" . $moneda . "</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='" . $data['alquiler']['last']['exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['alquiler']['last']['exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['alquiler']['last']['exclusivo']['tipo'], 'porcentage') . ">% del Alquiler</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='" . $data['alquiler']['last']['no_exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['alquiler']['last']['no_exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['alquiler']['last']['no_exclusivo']['tipo'], 'porcentage') . ">% del Alquiler</option>
                            <option value='no_disponible' " . check_select($data['alquiler']['last']['no_exclusivo']['tipo'], 'no_disponible') . ">NO HAY</option>
                        </select>
                    </div>
                </div>

                <div class='lotes_row row'>
                    <div class='rango col'>
                        <span>Por lotes de inmuebles</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='" . $data['alquiler']['lotes']['exclusivo']['monto'] . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['alquiler']['lotes']['exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['alquiler']['lotes']['exclusivo']['tipo'], 'porcentage') . ">% del Alquiler</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='" .$data['alquiler']['lotes']['no_exclusivo']['monto']  . "' class='monto'>
                        <select class='tipo'>
                            <option value='fijo' " . check_select($data['alquiler']['lotes']['no_exclusivo']['tipo'], 'fijo') . ">" . $moneda . "</option>
                            <option value='porcentage' " . check_select($data['alquiler']['lotes']['no_exclusivo']['tipo'], 'porcentage') . ">% del Alquiler</option>
                            <option value='no_disponible' " . check_select($data['alquiler']['lotes']['no_exclusivo']['tipo'], 'no_disponible') . ">NO HAY</option>
                        </select>
                    </div>
                </div>
                <div class='max_lotes_row row'>
                    <span class='col'></span>
                    <span class='col'></span>
                    <span class='col col_max_lotes'><input type='text' value='" . $data['alquiler']['lotes']['max_lotes'] . "' class='max_lotes' placeholder='MAX #lotes'></span>
                    
                </div>
            </div>


            ";
            if ($express == 0) {
               echo "
               <h2 class='label_tabla'>Gestion  de Bienes Inmuebles</h2>

               <div class='tabla_otros tabla'>
                   <hr>
                   <div class='administracion_row row_otros'>
                       <div class='exclusivo col'>
                           <span><b>Administracion</b></span>
                       </div>
                       <div class='exclusivo col'>
                           <span>Cobro mensual del alquiler, contacto de referencia con los inquilinos, gestión de trabajos y reparaciones</span>
                       </div>
                       <div class='exclusivo col'>
                           <input type='text' value='" . $data['otros']['administracion']['monto'] . "' class='monto otros' placeholder='XX% del Alquiler'>
                       </div>
                   </div>
   
                   <hr>
                   <div class='estado_row row_otros'>
                       <div class='exclusivo col'>
                           <span><b>Estado e Inventario&nbsp</b></span>
                       </div>
                       <div class='exclusivo col'>
                           <span>Revision y llenado de formularios de inventario al inicio o al cierre de un contrato de alquiler</span>
                       </div>
                       <div class='exclusivo col'>
                           <input type='text' value='" . $data['otros']['check_estado']['monto'] . "' class='monto otros' placeholder='XX Bs / m²'>
                       </div>
                   </div>

                   <div class='min_row row'>
                        <span class='col'></span>
                        <span class='col'></span>
                        <span class='col col_min'>
                        <input type='text' value='" . $data['otros']['check_estado']['min'] . "' class='min_precio otros' placeholder='Precio minimo de XX Bs'>
                        </span>
                        
                    </div>
               </div>
              
              ";
            };

            echo"
            <div class='botones_wrap'>
                <div class='btn_guardar_tabla_precios'>
                    <i class='far fa-save'></i>
                    <p>Guardar</p>
                </div>

                <div class='btn_previsualizar'>
                    <i class='far fa-file-pdf'></i>
                    <p>Previsualizar</p>
                </div>
            </div>
            ";


        }else {
           echo "
            <h2 class='label_tabla'>Venta " . ($anticretico == 1 ? 'o Anticretico' : '') . " de Bienes Inmuebles</h2>

            <div class='tabla_venta tabla'>

                <div class='labels_tabla row'>
                <div class='col_label'>
                    <p>RANGOS</p>
                </div>
                <div class='col_label'>
                    <p>EXCLUSIVO</p>
                </div>
                <div class='col_label'>
                    <p>NO-EXCLUSIVO</p>
                </div>
                </div>

                <div class='first_row row'>
                    <div class='rango col'>
                        <span>Hasta&nbsp</span>
                        <input type='text' value='' class='max'>
                        <span class='moneda'>&nbsp" . $moneda . "</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% PVF</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% PVF</option>
                            <option value='no_disponible'>NO HAY</option>
                        </select>
                    </div>
                </div>

                <div class='int_row row'>
                    <div class='rango col'>
                        <input type='text' value='' class='min'>
                        <p>&nbsp-&nbsp</p>
                        <input type='text' value='' class='max'></input>
                        <span class='moneda'>&nbsp" . $moneda . "</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% PVF</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% PVF</option>
                            <option value='no_disponible'>NO HAY</option>
                        </select>
                        <span class='btn btn_quitar btn_quitar_row1'><i class='fas fa-times-circle'></i></span>
                    </div>
                </div>
                

                <div class='last_row row'>
                    <div class='rango col'>
                        <span class='btn btn_agregar1 btn_agregar_row' data1='" . $moneda . "'><i class='fas fa-plus-circle'></i><i class='fas fa-caret-right'></i></span>
                        <span>Superior a&nbsp</span>
                        <input type='text' value='' class='min'>
                        <span class='moneda'>&nbsp" . $moneda . "</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% PVF</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% PVF</option>
                            <option value='no_disponible'>NO HAY</option>
                        </select>
                    </div>
                </div>

                <div class='lotes_row row'>
                    <div class='rango col'>
                        <span>Por lotes de inmuebles</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% PVF</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% PVF</option>
                            <option value='no_disponible'>NO HAY</option>
                        </select>
                    </div>
                </div>
                <div class='max_lotes_row row'>
                    <span class='col'></span>
                    <span class='col'></span>
                    <span class='col col_max_lotes'><input type='text' value='' class='max_lotes' placeholder='MAX #lotes'></span>
                    
                </div>
            </div>

            <h2 class='label_tabla'>Alquiler de Bienes Inmuebles</h2>

            <div class='tabla_alquiler tabla'>

                <div class='labels_tabla row'>
                <div class='col_label'>
                    <p>RANGOS</p>
                </div>
                <div class='col_label'>
                    <p>EXCLUSIVO</p>
                </div>
                <div class='col_label'>
                    <p>NO-EXCLUSIVO</p>
                </div>
                </div>

                <div class='first_row row'>
                    <div class='rango col'>
                        <span>Hasta&nbsp</span>
                        <input type='text' value='' class='max'>
                        <span class='moneda'>&nbsp" . $moneda . "</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% del Alquiler</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% del Alquiler</option>
                            <option value='no_disponible'>NO HAY</option>
                        </select>
                    </div>
                </div>

                <div class='int_row row'>
                    <div class='rango col'>
                        <input type='text' value='' class='min'>
                        <p>&nbsp-&nbsp</p>
                        <input type='text' value='' class='max'></input>
                        <span class='moneda'>&nbsp" . $moneda . "</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% del Alquiler</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% del Alquiler</option>
                            <option value='no_disponible'>NO HAY</option>
                        </select>
                        <span class='btn btn_quitar btn_quitar_row2'><i class='fas fa-times-circle'></i></span>
                    </div>
                </div>

                <div class='last_row row'>
                    <div class='rango col'>
                        <span class='btn btn_agregar2 btn_agregar_row' data1='" . $moneda . "'><i class='fas fa-plus-circle'></i><i class='fas fa-caret-right'></i></span>
                        <span>Superior a&nbsp</span>
                        <input type='text' value='' class='min'>
                        <span class='moneda'>&nbsp" . $moneda . "</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% del Alquiler</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% del Alquiler</option>
                            <option value='no_disponible'>NO HAY</option>
                        </select>
                    </div>
                </div>

                <div class='lotes_row row'>
                    <div class='rango col'>
                        <span>Por lotes de inmuebles</span>
                    </div>
                    <div class='exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% del Alquiler</option>
                        </select>
                    </div>
                    <div class='no_exclusivo col'>
                        <input type='text' value='' class='monto'>
                        <select class='tipo'>
                            <option value='fijo'>" . $moneda . "</option>
                            <option value='porcentage'>% del Alquiler</option>
                            <option value='no_disponible'>NO HAY</option>
                        </select>
                    </div>
                </div>
                <div class='max_lotes_row row'>
                    <span class='col'></span>
                    <span class='col'></span>
                    <span class='col col_max_lotes'><input type='text' value='' class='max_lotes' placeholder='MAX #lotes'></span>
                    
                </div>
            </div>


            ";
            if ($express == 0) {
               echo "
               <h2 class='label_tabla'>Gestion  de Bienes Inmuebles</h2>

               <div class='tabla_otros tabla'>
                   <hr>
                   <div class='administracion_row row_otros'>
                       <div class='exclusivo col'>
                           <span><b>Administracion</b></span>
                       </div>
                       <div class='exclusivo col'>
                           <span>Cobro mensual del alquiler, contacto de referencia con los inquilinos, gestión de trabajos y reparaciones</span>
                       </div>
                       <div class='exclusivo col'>
                           <input type='text' value='' class='monto otros' placeholder='XX% del Alquiler'>
                       </div>
                   </div>
   
                   <hr>
                   <div class='estado_row row_otros'>
                       <div class='exclusivo col'>
                           <span><b>Estado e Inventario&nbsp</b></span>
                       </div>
                       <div class='exclusivo col'>
                           <span>Revision y llenado de formularios de inventario al inicio o al cierre de un contrato de alquiler</span>
                       </div>
                       <div class='exclusivo col'>
                           <input type='text' value='' class='monto otros' placeholder='XX Bs / m²'>
                       </div>
                   </div>

                   <div class='min_row row'>
                        <span class='col'></span>
                        <span class='col'></span>
                        <span class='col col_min'>
                        <input type='text' value='' class='min_precio otros' placeholder='Precio minimo de XX Bs'>
                        </span>
                        
                    </div>
               </div>
              
              ";
            };

            echo"
            <div class='botones_wrap'>
                <div class='btn_guardar_tabla_precios'>
                    <i class='far fa-save'></i>
                    <p>Guardar</p>
                </div>

                <div class='btn_previsualizar' style='display: none'>
                    <i class='far fa-file-pdf'></i>
                    <p>Previsualizar</p>
                </div>
            </div>
            ";
            
        };
        
        
    };
   
}
?>
