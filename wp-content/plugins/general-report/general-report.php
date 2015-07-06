<?php
/*
Plugin Name: Reporte General de Ventas
Plugin URI: http://droneshop.mx
Description: Plugin para obtener un reporte general de ventas de todos los meses.
Version: 1.0
Author: Paul Soberanes
Author URI: http://neubox.com
License: GPL2
*/

define( 'REPORT__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'REPORT__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( REPORT__PLUGIN_DIR . '/report-export.php' );

//init hooks
add_action('admin_menu', 'master_report_setup_menu');
add_action('wp_ajax_get_sales_report', 'get_sales_report');
add_action('wp_ajax_nopriv_get_sales_report', 'get_sales_report');
add_action('wp_ajax_get_products_report', 'get_products_report');
add_action('wp_ajax_nopriv_get_products_report', 'get_products_report');
add_action('wp_ajax_get_top_products_report', 'get_top_products_report');
add_action('wp_ajax_nopriv_get_top_products_report', 'get_top_products_report');
add_action('wp_ajax_get_sales_products_report', 'get_sales_products_report');
add_action('wp_ajax_nopriv_get_sales_products_report', 'get_sales_products_report');
add_action( 'init', 'script_enqueuer' );

//hooks
function script_enqueuer(){
  wp_register_script( 'report_script', REPORT__PLUGIN_URL . 'report_script.js', array('jquery') );
  wp_localize_script( 'report_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'report_script' );
}

function master_report_setup_menu(){
  add_menu_page( 'Reporte General de Ventas', 'Reporte Ventas', 'manage_woocommerce', 'general-report', 'report_init', plugin_dir_url( __FILE__ ).'products-icon.png' );
}

function report_init(){

    $default_date_from = date('Y-m-d', strtotime("-1 months", time()));
    $default_date_to   = date('Y-m-d', time());
        ?>
        <div class="wrap">
            <h1>Reporte General de Ventas</h1>
            <form method="post" name="fz_csv_exporter_form" action="<?php echo REPORT__PLUGIN_URL ?>report-export.php">
                <h4>
                  Generar y/o actualizar reportes dando clic en el bot&oacute;n <strong>Generar Reporte</strong> y descargar desde el link de cada secci&oacute;n.
                </h4>
                <br />

                <div style="margin: 0;padding: 15px;background: #FFFFFF;border: 1px solid #B8B8B8;position: relative;">
                    <h2>Reporte de ventas</h2>
                    <h4>Selecciona un rango de fechas para generar el reporte.</h4>
                    <p>
                      <?php _e("Desde: " ) ?><input type="date" name="from_date"id="from_date" value="<?php echo $default_date_from ?>" />&nbsp;
                      <?php _e("Hasta: " ) ?><input type="date" name="to_date" id="to_date" value="<?php echo $default_date_to ?>" />
                    </p>
                    <input type="button" style="background: rgb(0, 158, 68);color: #FFFFFF;padding: 20px;line-height: 0;border: 0;" id="gen-sales-report" name="gen-report" class="button action" value="Generar Reporte de ventas" />
                    <p id="process-msg-one" style="height: 30px;line-height: 1.6;background:url('<?php echo plugin_dir_url( __FILE__ ) ?>loading.gif') left center no-repeat;padding-left: 30px;line-height: 1.6;display: inline-block;visibility: hidden;margin-left: 20px;margin-bottom: 20px;position: relative;top: -4px;">Cargando reporte...</p>

                    <hr>
                    <h3>Archivo reporte de ventas</h3>
                    <p id="sales-reports">
                      <?php
                      $dir_open = opendir(WP_PLUGIN_DIR .'/general-report/sales-reports');
                      $links = array();
                      $style="style='background: url(".plugin_dir_url( __FILE__ )."sales-icon.png) left center no-repeat;text-decoration:none;padding-left: 20px;'";

                      while(false !== ($filename = readdir($dir_open))){
                          if($filename != "." && $filename != ".."){
                              echo $link = "<a href='".plugin_dir_url( __FILE__ )."sales-reports/" .$filename . "'
                              $style
                              >". $filename ." </a><br />";
                          }
                      }

                      closedir($dir_open);
                      ?>
                    </p>
                </div>
                <br />
                <br />
                <div style="margin: 0;padding: 15px;background: #FFFFFF;border: 1px solid #B8B8B8;">
                  <h2>Reporte de productos</h2>
                  <h4>Clic en el botón para generar y/o actualizar el reporte de productos.</h4>

                    <input type="button" style="width:350px;background: rgb(0, 158, 68);color: #FFFFFF;padding: 20px;line-height: 0;border: 0;" id="gen-products-report" name="gen-report" class="button action" value="Generar Reporte de productos" />
                    <p id="process-msg-two" style="height: 30px;line-height: 1.6;background:url('<?php echo plugin_dir_url( __FILE__ ) ?>loading.gif') left center no-repeat;padding-left: 30px;line-height: 1.6;display: inline-block;visibility: hidden;margin-left: 20px;margin-bottom: 20px;position: relative;top: -4px;">Cargando reporte...</p>
                    <br />
                    <input type="button" style="width:350px;background: rgb(0, 110, 158);color: #FFFFFF;padding: 20px;line-height: 0;border: 0;" id="gen-top-products-report" name="gen-report" class="button action" value="Generar Reporte de productos más vendidos" />
                    <p id="process-msg-three" style="height: 30px;line-height: 1.6;background:url('<?php echo plugin_dir_url( __FILE__ ) ?>loading.gif') left center no-repeat;padding-left: 30px;line-height: 1.6;display: inline-block;visibility: hidden;margin-left: 20px;margin-bottom: 20px;position: relative;top: -4px;">Cargando reporte...</p>
                    <br />
                    <h4>Selecciona un rango de fechas para generar el reporte.</h4>
                    <p>
                      <?php _e("Desde: " ) ?><input type="date" name="from_date"id="from_date_p" value="<?php echo $default_date_from ?>" />&nbsp;
                      <?php _e("Hasta: " ) ?><input type="date" name="to_date" id="to_date_p" value="<?php echo $default_date_to ?>" />
                    </p>
                    <br />
                    <input type="button" style="width:350px;background: rgb(0, 110, 158);color: #FFFFFF;padding: 20px;line-height: 0;border: 0;" id="gen-sales-product-report" name="gen-report" class="button action" value="Generar Reporte ventas por producto" />
                    <p id="process-msg-four" style="height: 30px;line-height: 1.6;background:url('<?php echo plugin_dir_url( __FILE__ ) ?>loading.gif') left center no-repeat;padding-left: 30px;line-height: 1.6;display: inline-block;visibility: hidden;margin-left: 20px;margin-bottom: 20px;position: relative;top: -4px;">Cargando reporte...</p>

                  <br /><br />
                  <hr>
                  <h3>Archivos reporte de productos</h3>
                  <p id="products-reports">
                    <?php
                    $dir_open = opendir(WP_PLUGIN_DIR .'/general-report/products-reports');
                    $links = array();
                    $style="style='background: url(".plugin_dir_url( __FILE__ )."products-icon.png) left center no-repeat;text-decoration:none;padding-left: 20px;'";

                    while(false !== ($filename = readdir($dir_open))){
                        if($filename != "." && $filename != ".."){
                            echo $link = "<a href='".plugin_dir_url( __FILE__ )."products-reports/" .$filename . "'
                            $style
                            >". $filename ." </a><br />";
                        }
                    }

                    closedir($dir_open);
                    ?>
                  </p>
                </div>

            </form>
        </div>
        <?php
}

//response with orders
function get_sales_report(){
    $report = new ReportExport();

    $f_date = $_POST['from_date'];
    $t_date = $_POST['to_date'];

    list($f_year, $f_month, $f_day) = explode('-', $f_date);
    list($t_year, $t_month, $t_day) = explode('-', $t_date);

    $filter_dates = array(
      "after" => array(
          "year"  => $f_year,
          "month" => $f_month,
          "day"   => $f_day
      ),
      "before" => array(
          "year"  => $t_year,
          "month" => $t_month,
          "day"   => $t_day
      )
    );

    $response = $report->create_sales_csv($filter_dates);

    echo json_encode($response, JSON_PRETTY_PRINT);
    die;
}

//response with top selling products
function get_sales_by_product(){
    $report = new ReportExport();

    $response = $report->create_spectro();

    echo json_encode($response, JSON_PRETTY_PRINT);
    die;
}

//response with products
function get_products_report(){
    $report = new ReportExport();

    $response = $report->create_products_csv();

    echo json_encode($response, JSON_PRETTY_PRINT);
    die;
}

//response with top selling products
function get_top_products_report(){
    $report = new ReportExport();

    $response = $report->create_top_products_csv();

    echo json_encode($response, JSON_PRETTY_PRINT);
    die;
}

//response with sales by products
function get_sales_products_report(){
    $report = new ReportExport();

    $f_date = $_POST['from_date'];
    $t_date = $_POST['to_date'];

    list($f_year, $f_month, $f_day) = explode('-', $f_date);
    list($t_year, $t_month, $t_day) = explode('-', $t_date);

    $filter_dates = array(
      "after" => array(
          "year"  => $f_year,
          "month" => $f_month,
          "day"   => $f_day
      ),
      "before" => array(
          "year"  => $t_year,
          "month" => $t_month,
          "day"   => $t_day
      )
    );

    $response = $report->create_sales_products_csv($filter_dates);

    echo json_encode($response, JSON_PRETTY_PRINT);
    die;
}
