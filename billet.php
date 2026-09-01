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
$badge = format_event_badge();
?>

<section>
  <div class="ticket-wrap">
    <div class="notice notice-success no-print">Paiement confirmé — voici votre billet électronique.</div>

    <div class="ticket" id="ticketCard">
      <div class="ticket-stub">
        <span class="stub-num">#<?= htmlspecialchars(strtoupper(substr($token, 0, 8))) ?></span>
      </div>
      <div class="ticket-main">
        <div class="ticket-artwork"></div>
        <div class="ticket-ribbon"><span>St-Sylvestre</span></div>
        <div class="ticket-datebadge">
          <span class="db-day"><?= htmlspecialchars($badge['day']) ?></span>
          <span class="db-month"><?= htmlspecialchars($badge['month']) ?></span>
        </div>
        <div class="ticket-content">
          <span class="ticket-brand">✦ <?= htmlspecialchars(EVENT_NAME) ?></span>
          <h2 class="ticket-pass"><?= htmlspecialchars(pass_label($ticket['pass_type'])) ?></h2>
          <div class="ticket-holder">
            <?= htmlspecialchars($ticket['buyer_name']) ?><br>
            <?= htmlspecialchars(EVENT_VENUE) ?>, <?= htmlspecialchars(EVENT_CITY) ?>
          </div>
          <div class="ticket-bottom">
            <div class="qr-box" id="qrBox"></div>
            <div class="ticket-price"><?= format_money((int)$ticket['price']) ?></div>
          </div>
        </div>
      </div>
    </div>
    <p class="ticket-legal no-print">
      N° billet : <?= htmlspecialchars(strtoupper(substr($token, 0, 12))) ?> · Ce QR code est unique et sera scanné à l'entrée pour valider votre accès.
    </p>

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
