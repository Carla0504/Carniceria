<section class="info">
  <div class="info-item">
    <div class="info-icon-wrap">📍</div>
    <div>
      <h3><?= $t['footer_donde'] ?></h3>
      <p>Calle Seis de Diciembre<br>Aravaca, Madrid</p>
    </div>
  </div>
  <div class="info-item">
    <div class="info-icon-wrap">🕐</div>
    <div>
      <h3><?= $t['footer_horario'] ?></h3>
      <p><?= $t['footer_horario_semana'] ?><br><?= $t['footer_horario_sabado'] ?><br><?= $t['footer_horario_domingo'] ?></p>
    </div>
  </div>
  <div class="info-item">
    <div class="info-icon-wrap">💬</div>
    <div>
      <h3><?= $t['footer_contacto'] ?></h3>
      <p>Tel: 91 234 56 78<br>info@ladehesa.es</p>
      <a href="/Carniceria/crm/app/views/contacto/index.php" class="info-link"><?= $t['footer_ir_contacto'] ?></a>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="footer-content">
    <p>&copy; <?= date('Y') ?> La Dehesa &mdash; Calle Seis de Diciembre, Aravaca (Madrid)</p>
    <p>
      <a href="/Carniceria/crm/index.php#sobre-nosotros"><?= $t['sobre_tag'] ?></a> &middot;
      <a href="/Carniceria/crm/app/views/galeria/index.php"><?= $t['nav_imagenes'] ?></a> &middot;
      <a href="/Carniceria/crm/app/views/contacto/index.php"><?= $t['nav_contacto'] ?></a>
    </p>
  </div>
</footer>

<script src="/Carniceria/crm/public/js/main.js"></script>
</body>
</html>
