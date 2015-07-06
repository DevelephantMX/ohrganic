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
                  <h1 class="f-page-title">¡BIENVENIDO AL SERVICIO DE FACTURACI&Oacute;N ELECTR&Oacute;NICA! </h1>
                  <p class="f-page-subtitle">
                    Aqu&iacute; puedes generar las facturas de tus compras realizadas en <span class="brand">Drone Shop</span>
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
                        Identificar &oacute;rden
                      </h1>
                    </div>
                    <div class="step-content">
                      <p class="step-instruction">Ingresa tu RFC, n&uacute;m. de &oacute;rden y/o correo electr&oacute;nico para buscar tu pedido. </p>
                      <form name="f-step-one-form" id="f-step-one-form" action="<?php echo get_permalink(); ?>" method="post">
                        <input type="hidden" name="csrf" value="" />
                        <label for="f-rfc" >RFC</label>
                        <input type="text" class="input-upper f-input" id="f-rfc" name="rfc" value="" placeholder="12 o 13 dígitos" />
                        <label for="f-num-order" >N&uacute;m de órden*</label>
                        <input type="text" class="f-input" id="f-num-order" name="order" value="" placeholder="#"  />
                        <label for="f-email" >Correo electr&oacute;nico*</label>
                        <input type="email" class="f-input" id="f-email" name="email" value="" placeholder="El correo registrado en la órden"  />
                        <!-- <p class="email-msg">*El correo electr&oacute;nico debe ser el registrado en la &oacute;rden.</p> -->
                        <div class="buttons-right">
                          <input type="submit" class="f-submit" id="step-one-button-next" name="f-submit" value="siguiente" />
                        </div>
                        <div id="error_msj">Por favor complete el formulario.</div>
                        <div class="clearfix"></div>
                      </form>
                    </div>
                    <div class="loader_content">
                      <div class="loader">Cargando...</div>
                    </div>
                  </div>

                  <div id="step-two" class="step-block">
                    <div class="step-header">
                      <h1>
                        <span>Paso 2</span>
                        Informaci&oacute;n del cliente
                      </h1>
                    </div>
                    <div class="step-content">
                      <p class="step-instruction"></p>
                      <form name="f-step-two-form" id="f-step-two-form" action="<?php echo get_permalink(); ?>" method="post">
                        <input type="hidden" name="csrf" value="" />
                        <input type="hidden" id="apimethod" name="apimethod" value="create" />
                        <input type="hidden" id="uid" name="uid" value="" />
                        <h3>Datos de contacto</h3>
                        <div class="input-group">
                          <label for="general-nombre">Nombre</label>
                          <input type="text" class="input-cap f-input f-top" id="general-nombre" name="general-nombre" value="" placeholder="" readonly />
                        </div>
                        <div class="input-group">
                          <label for="general-apellidos">Apellidos</label>
                          <input type="text" class="input-cap f-input f-top" id="general-apellidos" name="general-apellidos" value="" placeholder="" readonly />
                        </div>
                        <div class="input-group">
                          <label for="general-email">Correo electr&oacute;nico</label>
                          <input type="email" class="f-input f-top" id="general-email" name="general-email" value="" placeholder="Email para envío de CFDI" readonly />
                        </div>
                        <h3>Datos fiscales</h3>
                        <div class="input-group">
                          <label for="fiscal-nombre">Nombre/Razón Social</label>
                          <input type="text" class="input-cap f-input f-top" id="fiscal-nombre" name="fiscal-nombre" value="" placeholder="" readonly />
                        </div>
                        <div class="input-group">
                          <label for="fiscal-rfc">RFC</label>
                          <input type="text" class="input-upper f-input f-top" id="fiscal-rfc" name="fiscal-rfc" value="" placeholder="12 o 13 dígitos" readonly />
                        </div>
                        <div class="input-group">
                          <label for="fiscal-calle">Calle</label>
                          <input type="text" class="input-cap f-input f-no-top" id="fiscal-calle" name="fiscal-calle" value="" placeholder="" readonly />
                        </div>
                        <div class="input-group float-left">
                          <label for="fiscal-exterior">Núm. exterior</label>
                          <input type="text" class="input-cap f-input f-right f-no-top" id="fiscal-exterior" name="fiscal-exterior" value="" placeholder="Num. exterior" readonly />
                        </div>
                        <div class="input-group float-left" style="margin-left: 9px;">
                          <label for="fiscal-interior">Núm. interior</label>
                          <input type="text" class="input-cap f-input" id="fiscal-interior" name="fiscal-interior" value="" placeholder="Num. interior" readonly />
                        </div>
                        <div class="input-group">
                          <label for="fiscal-colonia">Colonia</label>
                          <input type="text" class="input-cap f-input f-right" id="fiscal-colonia" name="fiscal-colonia" value="" placeholder="Colonia" readonly />
                        </div>
                        <div class="input-group">
                          <label for="fiscal-delegacion">Delegaci&oacute;n</label>
                          <input type="text" class="input-cap f-input f-no-top" id="fiscal-delegacion" name="fiscal-delegacion" value="" placeholder="Delegación" readonly />
                        </div>
                        <div class="input-group">
                          <label for="fiscal-municipio">Ciudad</label>
                          <input type="text" class="input-cap f-input f-no-top f-right" id="fiscal-municipio" name="fiscal-municipio" value="" placeholder="Ciudad" readonly />
                        </div>
                        <div class="input-group">
                          <label for="fiscal-estado">Estado</label>
                          <input type="text" class="input-cap f-input" id="fiscal-estado" name="fiscal-estado" value="" placeholder="" readonly />
                        </div>
                        <div class="input-group">
                          <label for="fiscal-pais">Pa&iacute;s</label>
                          <input type="text" class="input-cap f-input f-right" id="fiscal-pais" name="fiscal-pais" value="México" placeholder="" readonly />
                        </div>
                        <div class="input-group">
                          <label for="fiscal-cp">C&oacute;digo postal</label>
                          <input type="text" class="input-cap f-input f-no-top f-bottom" id="fiscal-cp" name="fiscal-cp" value="" placeholder="" readonly />
                        </div>
                        <div class="input-group">
                          <label for="fiscal-telefono">Telefono</label>
                          <input type="text" class="input-cap f-input f-no-top f-right f-bottom" id="fiscal-telefono" name="fiscal-telefono" value="" placeholder="Teléfono" readonly />
                        </div>
                        <div class="clearfix"></div>
                        <div class="buttons-right">
                          <input type="button" class="f-submit f-back" id="step-two-button-back" name="f-back" value="Volver" data-f="2" />
                          <input type="button" class="f-submit f-edit" id="step-two-button-edit" name="f-edit" value="Editar" data-b="1" />
                          <input type="submit" class="f-submit" id="step-two-button-next" name="f-submit" value="siguiente" />
                        </div>
                        <div class="f-loading">Cargando...</div>
                        <div id="error_msj">Por favor complete el formulario.</div>
                        <div class="clearfix"></div>
                      </form>
                    </div>
                    <div class="loader_content">
                      <div class="loader">Cargando...</div>
                    </div>
                  </div>

                  <div id="step-three" class="step-block step-invoice">
                    <div class="step-header">
                      <h1>
                        <span>Paso 3</span>
                        Verificar datos de pedido
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
                        <h3 class="invoice-header">Detalle del pedido</h3>
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
                        <p id="invoice-pmethod">
                          Selecciona el m&eacute;todo de pago
                          <form id="payment-method-form">
                            <div class="input-group">
                              <label for="fiscal-telefono">M&eacute;todo</label>
                              <select id="select-payment" class="input-cap f-input f-select">
                                <option value="1">PayPal</option>
                                <option value="2">Depósito a cuenta</option>
                                <option value="3">Efectivo</option>
                                <option value="4">Pago con tarjeta</option>
                                <option value="5">Transferencia electrónica</option>
                              </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="input-group">
                              <label for="fiscal-telefono">&Uacute;ltimos 4 dígitos</label>
                              <input type="text" class="input-cap f-input f-no-top f-bottom" id="f-num-cta" name="f-num-cta" value="" placeholder="de tu cuenta o tarjeta" />
                            </div>
                            <div class="clearfix"></div>
                          </form>
                        </p>
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
                        <div class="buttons-right">
                          <input type="button" class="f-submit f-back" id="step-three-button-back" name="f-back" value="Volver" data-f="3" />
                          <input type="button" class="btn-success" style="background: #67BA2F !important;border: #67BA2F !important;padding: 13px 20px;margin: 0;margin-left: 15px;font-weight: bold;" id="step-three-button-next" name="f-submit" value="Generar factura" />
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                    <div class="loader_content">
                      <div class="loader">Cargando...</div>
                    </div>
                  </div>
                  <div id="step-four" class="step-block step-invoice">
                    <div class="step-header">
                      <h1>
                        <span></span>
                        Resultado de facturaci&oacute;n
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
                        <h4 id="result-email-msg"></h4>
                        <h4 id="result-msg"></h4>
                        <a href="#" id="btn-success-email" class="btn-success invoice-button invoice-pdf" target="_blank">Enviar por correo electr&oacute;nico</a>
                        <a href="#" id="btn-success-pdf" class="btn-success invoice-button invoice-pdf" target="_blank">Descargar PDF</a>
                        <a href="#" id="btn-success-xml" class="btn-success invoice-button invoice-xml" target="_blank">Descargar XML</a>
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
