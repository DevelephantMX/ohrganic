<?php
class FacturaWidget {

    public function form_creation(){
      ?>
      <div id="facturacion_wrapper">
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
                        <input type="text" class="input-upper f-input" id="f-rfc" name="rfc" value="" placeholder="RFC" />
                        <input type="text" class="f-input" id="f-num-order" name="order" value="" placeholder="# de órden *"  />
                        <input type="email" class="f-input" id="f-email" name="email" value="" placeholder="Correo electrónico *"  />
                        <input type="submit" class="f-submit" id="step-one-button-next" name="f-submit" value="siguiente" />
                        <div class="f-loading">Cargando...</div>
                        <div id="error_msj">Por favor complete el formulario.</div>
                        <div class="clearfix"></div>
                      </form>
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
                      <p class="step-instruction"></p>
                      <form name="f-step-two-form" id="f-step-two-form" action="<?php echo get_permalink(); ?>" method="post">
                        <input type="hidden" name="csrf" value="" />
                        <input type="hidden" id="apimethod" name="apimethod" value="create" />
                        <input type="hidden" id="uid" name="uid" value="" />
                        <h3>Datos generales</h3>
                        <input type="text" class="input-cap f-input f-top" id="general-nombre" name="general-nombre" value="" placeholder="Nombre" readonly />
                        <input type="text" class="input-cap f-input f-top" id="general-apellidos" name="general-apellidos" value="" placeholder="Apellidos" readonly />
                        <input type="email" class="f-input f-top" id="general-email" name="general-email" value="" placeholder="Email para envío de CFDI" readonly />
                        <hr>
                        <h3>Datos fiscales</h3>
                        <input type="text" class="input-cap f-input f-top" id="fiscal-nombre" name="fiscal-nombre" value="" placeholder="Nombre/Razón Social" readonly />
                        <input type="text" class="input-upper f-input f-top" id="fiscal-rfc" name="fiscal-rfc" value="" placeholder="RFC" readonly />
                        <input type="text" class="input-cap f-input f-no-top" id="fiscal-calle" name="fiscal-calle" value="" placeholder="Calle" readonly />
                        <input type="text" class="input-cap f-input f-right f-no-top" id="fiscal-exterior" name="fiscal-exterior" value="" placeholder="Num. exterior" readonly />
                        <div class="clearfix"></div>
                        <input type="text" class="input-cap f-input" id="fiscal-interior" name="fiscal-interior" value="" placeholder="Num. interior" readonly />
                        <input type="text" class="input-cap f-input f-right" id="fiscal-colonia" name="fiscal-colonia" value="" placeholder="Colonia" readonly />
                        <div class="clearfix"></div>
                        <input type="text" class="input-cap f-input f-no-top" id="fiscal-delegacion" name="fiscal-delegacion" value="" placeholder="Delegación" readonly />
                        <input type="text" class="input-cap f-input f-no-top f-right" id="fiscal-municipio" name="fiscal-municipio" value="" placeholder="Ciudad" readonly />
                        <div class="clearfix"></div>
                        <input type="text" class="input-cap f-input" id="fiscal-estado" name="fiscal-estado" value="" placeholder="Estado" readonly />
                        <input type="text" class="input-cap f-input f-right" id="fiscal-pais" name="fiscal-pais" value="México" placeholder="País" readonly />
                        <div class="clearfix"></div>
                        <input type="text" class="input-cap f-input f-no-top f-bottom" id="fiscal-cp" name="fiscal-cp" value="" placeholder="Código postal" readonly />
                        <input type="text" class="input-cap f-input f-no-top f-right f-bottom" id="fiscal-telefono" name="fiscal-telefono" value="" placeholder="Teléfono" readonly />
                        <div class="clearfix"></div>
                        <input type="submit" class="f-submit" id="step-two-button-next" name="f-submit" value="siguiente" />
                        <input type="button" class="f-submit f-edit" id="step-two-button-edit" name="f-edit" value="Editar" data-b="1" />
                        <div class="f-loading">Cargando...</div>
                        <div id="error_msj">Por favor complete el formulario.</div>
                        <div class="clearfix"></div>
                      </form>
                      <div class="f-loading">Cargando...</div>
                    </div>
                  </div>

                  <div id="step-three" class="step-block step-invoice">
                    <div class="step-header">
                      <h1>
                        <span>Paso 3</span>
                        Verificar factura
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
                      <h3 class="invoice-title"> <span id="invoice-id">3526321</span></h3>
                      <h3 class="invoice-title"> <span id="invoice-date">30/06/2015</span></h3>
                      <div class="invoice-sections">

                        <div class="invoice-emisor">
                          <h3 class="invoice-header">Emisor</h3>
                          <span id="emisor-nombre" class="ref-data"></span>
                          <span id="emisor-rfc" class="ref-data"></span>
                          <span id="emisor-direccion" class="ref-data"></span>
                          <span id="emisor-direccion-zone" class="ref-data"></span>
                          <span id="emisor-telefono" class="ref-data"></span>
                          <span id="emisor-email" class="ref-data"></span>
                        </div>

                        <div class="invoice-receptor">
                          <h3 class="invoice-header">Receptor</h3>
                          <span id="receptor-nombre" class="ref-data"></span>
                          <span id="receptor-rfc" class="ref-data"></span>
                          <span id="receptor-direccion" class="ref-data"></span>
                          <span id="receptor-direccion-zone" class="ref-data"></span>
                          <span id="receptor-email" class="ref-data"></span>
                        </div>

                      <div class="invoice-details">
                        <h3 class="invoice-header">Detalle a facturar</h3>
                        <table id="table-details">
                          <thead>
                            <tr>
                              <td>Producto</td>
                              <td>Cantidad</td>
                              <td>Precio unitario</td>
                              <td>Total</td>
                            </tr>
                          </thead>
                          <tbody id="datails-body">

                          </tbody>
                        </table>
                      </div>

                      <div class="invoice-payment">
                        <h3 class="invoice-header">M&eacute;todo de pago</h3>
                        <p id="invoice-pmethod"></p>
                      </div>

                      <div class="invoice-totals">
                        <table>
                          <tr>
                              <td>Subotal</td>
                              <td><span id="invoice-subtotal"></span></td>
                          </tr>
                          <tr>
                              <td>IVA</td>
                              <td><span id="invoice-iva"></span></td>
                          </tr>
                          <tr>
                              <td>Total</td>
                              <td><span id="invoice-total"></span></td>
                          </tr>
                        </table>
                      </div>
                        <div class="clearfix"></div>
                        <input type="button" class="btn-success" style="background: #67BA2F !important;border: #67BA2F !important;padding: 13px 20px;" id="step-three-button-next" name="f-submit" value="Generar factura" />
                        <div class="clearfix"></div>
                      </div>
                    </div>
                  </div>
                  <div id="step-four" class="step-block step-invoice">
                    <div class="step-header">
                      <h1>
                        <span></span>
                        La factura ha sido creada
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
                      <div class="buttons_container">
                        <h1 id="result-msg-title">La factura ha sido creada y enviada con &eacute;xito.</h1>
                        <div class="clearfix"></div>
                        <h4 id="result-msg"></h4>
                        <a href="#" id="btn-success-pdf" class="btn-success invoice-button invoice-pdf">Descargar PDF</a>
                        <a href="#" id="btn-success-xml" class="btn-success invoice-button invoice-xml">Descargar XML</a>
                      </div>
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
      </div>
      <?php
    }

}
