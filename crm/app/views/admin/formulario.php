<?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
<link rel="stylesheet" href="/Carniceria/crm/public/css/admin.css">
<div id="modal-overlay" class="modal-overlay" hidden>
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modal-titulo"><?= $t['admin_form_nuevo'] ?></h3>
            <button type="button" id="modal-cerrar" class="modal-cerrar">✕</button>
        </div>
        <form id="form-producto" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_action" id="field-action">
            <input type="hidden" name="id" id="field-id">
            <input type="hidden" name="foto_actual" id="field-foto-actual">
            <div class="form-group">
                <label><?= $t['admin_form_seccion'] ?></label>
                <select name="id_seccion" id="field-seccion" required>
                    <option value="1"><?= $t['admin_form_sec_carniceria'] ?></option>
                    <option value="2"><?= $t['admin_form_sec_charcuteria'] ?></option>
                    <option value="3"><?= $t['admin_form_sec_polleria'] ?></option>
                    <option value="4"><?= $t['admin_form_sec_conservas'] ?></option>
                </select>
            </div>
            <input type="hidden" name="nombre_en" id="field-nombre-en">
            <input type="hidden" name="descripcion_en" id="field-descripcion-en">
            <div class="form-group">
                <label><?= $t['admin_form_nombre'] ?></label>
                <input type="text" name="nombre" id="field-nombre" required>
            </div>
            <div class="form-group">
                <label><?= $t['admin_form_descripcion'] ?></label>
                <textarea name="descripcion" id="field-descripcion"></textarea>
            </div>
            <div class="form-group form-row">
                <div>
                    <label><?= $t['admin_form_precio'] ?></label>
                    <input type="number" name="precio" id="field-precio" step="0.01" min="0" required>
                </div>
                <div>
                    <label><?= $t['admin_form_unidad'] ?></label>
                    <select name="unidad_medida" id="field-unidad">
                        <option value="kg">€/kg</option>
                        <option value="g">€/g</option>
                        <option value="100g">€/100g</option>
                        <option value="unidad">€/unidad</option>
                        <option value="bandeja">€/bandeja</option>
                        <option value="pack">€/pack</option>
                        <option value="blister">€/blíster</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label><?= $t['admin_form_stock'] ?></label>
                <input type="number" name="stock" id="field-stock" step="0.001" min="0" value="0" required>
            </div>
            <div class="form-group" id="grupo-foto-actual" hidden>
                <p class="foto-actual-texto"><?= $t['admin_form_foto_actual'] ?> <span id="texto-foto-actual"></span></p>
            </div>
            <div class="form-group">
                <label><?= $t['admin_form_foto_nueva'] ?></label>
                <input type="file" name="foto" id="field-foto" accept="image/*">
            </div>
            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="disponible" id="field-disponible" value="1">
                    <?= $t['admin_form_disponible'] ?>
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $t['admin_form_guardar'] ?></button>
                <button type="button" class="btn btn-secondary" id="btn-cancelar"><?= $t['admin_form_cancelar'] ?></button>
            </div>
        </form>
    </div>
</div>
<div id="modal-overlay-promo" class="modal-overlay" hidden>
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modal-titulo-promo"><?= $t['admin_form_promo_titulo'] ?></h3>
            <button type="button" id="modal-cerrar-promo" class="modal-cerrar">✕</button>
        </div>
        <form id="form-promo" novalidate>
            <input type="hidden" name="_action" id="field-promo-action">
            <input type="hidden" name="id" id="field-promo-id">
            <input type="hidden" name="id_producto" id="field-promo-id-producto">
            <div class="form-group">
                <label><?= $t['admin_form_promo_precio'] ?></label>
                <input type="number" name="precio_promocional" id="field-promo-precio" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label><?= $t['admin_form_promo_inicio'] ?></label>
                <input type="date" name="fecha_inicio" id="field-promo-inicio">
            </div>
            <div class="form-group">
                <label><?= $t['admin_form_promo_fin'] ?></label>
                <input type="date" name="fecha_fin" id="field-promo-fin">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $t['admin_form_promo_guardar'] ?></button>
                <button type="button" class="btn btn-secondary" id="btn-cancelar-promo"><?= $t['admin_form_cancelar'] ?></button>
            </div>
        </form>
    </div>
</div>
<script src="/Carniceria/crm/public/js/admin.js"></script>
<?php endif; ?>
