
<?php
if(isset($_POST['f-submit'])){


}else{
?>
<div id="facturacion_wrapper">
  <?php get_header(); ?>
  <div class="full-width">
    <div id="primary" class="content-area">
      <div id="content" class="site-content" role="main">
        <div class="home_product">
          <div class="f-welcome-container">
            <h1 class="f-page-title">¡Bienvenido al servicio de Factura Electrónica!</h1>
            <p class="f-page-subtitle">
              Aqu&iacute; podr&aacute;s generar las facturas de tus compras realizadas en <span class="brand">Drone Shop</span>
            </p>
            <div class="f-button-container">
              <button type="button" name="fstart" id="f-start">Obtener factura</button>
            </div>
          </div>

          <div class="steps-container">

            <div id="step-one" class="step-block">
              <div class="step-header">
                <h1>
                  <span>Paso 1</span>
                  Buscar factura
                </h1>
                <div class="steps-icons">
                  <div id="progress" value="100" max="100"></div>
                  <ul class="step-badges">
                    <li><i class="fa fa-search"></i></li>
                    <li><i class="fa fa-info"></i></li>
                    <li><i class="fa fa-file-text-o"></i></li>
                    <li><i class="fa fa-arrow-circle-o-down"></i></li>
                  </ul>
                </div>
              </div>
              <div class="step-content">
                <p class="step-instruction">Ingresa tu RFC, num. de &oacute;rden y/o correo electr&oacute;nico para buscar tu pedido. </p>
                <form name="f-step-one-form" id="f-step-one-form" action="<?php echo get_permalink(); ?>" method="post">
                  <input type="hidden" name="csrf" value="" />
                  <input type="text" class="f-input" id="f-rfc" name="rfc" value="" placeholder="RFC" />
                  <input type="text" class="f-input" id="f-num-order" name="order" value="" placeholder="# de órden *" required />
                  <input type="email" class="f-input" id="f-email" name="email" value="" placeholder="Correo electrónico *" required />
                  <input type="submit" class="f-submit" id="step-one-button-next" name="f-submit" value="siguiente" />
                </form>
                <div class="f-loading">Cargando...</div>
              </div>
            </div>

            <div id="step-two" class="step-block">
              <div class="step-header">
                <h1>
                  <span>Paso 2</span>
                  Comprobar informaci&oacute;n
                </h1>
                <div class="steps-icons">
                  <div id="progress" value="100" max="100"></div>
                  <ul class="step-badges">
                    <li><i class="fa fa-search"></i></li>
                    <li><i class="fa fa-info"></i></li>
                    <li><i class="fa fa-file-text-o"></i></li>
                    <li><i class="fa fa-arrow-circle-o-down"></i></li>
                  </ul>
                </div>
              </div>
              <div class="step-content">
                <p class="step-instruction">Ingresa tu RFC, num. de &oacute;rden y/o correo electr&oacute;nico para buscar tu pedido. </p>
                <form name="f-step-one-form" id="f-step-one-form" action="<?php echo get_permalink(); ?>" method="post">
                  <input type="hidden" name="csrf" value="" />
                  <input type="text" class="f-input" id="f-rfc" name="rfc" value="" placeholder="RFC" />
                  <input type="text" class="f-input" id="f-num-order" name="order" value="" placeholder="# de órden *" required />
                  <input type="email" class="f-input" id="f-email" name="email" value="" placeholder="Correo electrónico *" required />
                  <input type="submit" class="f-submit" id="step-one-button-next" name="f-submit" value="siguiente" />
                </form>
                <div class="f-loading">Cargando...</div>
              </div>
            </div>

          </div>

        </div>
      <!-- #homepage -->
      </div>
    <!-- #content -->
    </div>
  <!-- #primary -->
  </div>
  <?php get_footer(); ?>
</div>
<?php } ?>
