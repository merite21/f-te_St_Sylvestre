<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/db.php';

$token = trim($_GET['token'] ?? '');
$db = get_db();
$stmt = $db->prepare('SELECT t.*, o.payment_status, o.payment_method FROM tickets t JOIN orders o ON o.id = t.order_id WHERE t.token = ?');
$stmt->execute([$token]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

$pageTitle = 'Mon billet — ' . EVENT_NAME;
require __DIR__ . '/includes/header.php';

if (!$ticket || $ticket['payment_status'] !== 'paid') {
    echo '<div class="verify-card"><div class="verify-icon">✕</div><div class="verify-status verify-bad">Billet introuvable</div><p>Ce billet n\'existe pas ou son paiement n\'a pas été confirmé.</p><a href="billetterie.php" class="btn btn-gold">Réserver un billet</a></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$verifyUrl = rtrim(SITE_URL, '/') . '/verify.php?token=' . urlencode($token);

$eventStart = new DateTime(EVENT_START);
$moisAbr = [1=>'JANV',2=>'FÉVR',3=>'MARS',4=>'AVR',5=>'MAI',6=>'JUIN',
    7=>'JUIL',8=>'AOÛT',9=>'SEPT',10=>'OCT',11=>'NOV',12=>'DÉC'];
?>

<section>
  <div class="ticket-wrap">
    <div class="notice notice-success no-print">Paiement confirmé — voici votre billet électronique.</div>

    <div class="ticket-main" id="ticketCard">
      <div class="ticket-artwork">
        <div class="ticket-burst b1"></div>
        <div class="ticket-burst b2"></div>
        <span class="ticket-badge-date"><?= $eventStart->format('d') ?><small><?= $moisAbr[(int)$eventStart->format('n')] ?></small></span>
        <span class="ticket-brand">✦ <?= htmlspecialchars(EVENT_NAME) ?></span>
      </div>

      <div class="ticket-content">
        <div class="ticket-top">
          <span class="ticket-pass"><?= htmlspecialchars(pass_label($ticket['pass_type'])) ?></span>
          <span class="ticket-holder"><?= htmlspecialchars($ticket['buyer_name']) ?></span>
        </div>
        <div class="ticket-meta">
          <div><span class="lbl">Date</span><div class="val"><?= format_event_date() ?></div></div>
          <div><span class="lbl">Lieu</span><div class="val"><?= htmlspecialchars(EVENT_VENUE) ?>, <?= htmlspecialchars(EVENT_CITY) ?></div></div>
        </div>
      </div>

      <div class="ticket-stub">
        <div class="qr-box" id="qrBox"></div>
        <div class="stub-text">
          <span class="stub-num">N° <?= htmlspecialchars(strtoupper(substr($token, 0, 12))) ?></span>
        </div>
        <span class="stub-price"><?= format_money((int)$ticket['price']) ?></span>
      </div>
      <div class="ticket-legal">Ce QR code est unique et sera scanné à l'entrée pour valider votre accès.</div>
    </div>

    <div class="ticket-actions no-print">
      <button class="btn btn-gold" onclick="window.print()">Imprimer mon billet</button>
      <a href="index.php" class="btn btn-line">Retour à l'accueil</a>
    </div>
  </div>
</section>

<script src="assets/js/qrcode.js"></script>
<script>
  (function () {
    var qr = qrcode(0, 'M');
    qr.addData(<?= json_encode($verifyUrl) ?>);
    qr.make();
    document.getElementById('qrBox').innerHTML = qr.createSvgTag(5, 0);
  })();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
