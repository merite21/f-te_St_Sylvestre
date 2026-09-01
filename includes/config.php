<?php
/**
 * Configuration du site "Organisation Fête St Sylvestre".
 * Modifiez les valeurs ci-dessous pour adapter le site à votre événement réel.
 */

// URL de base du site, sans slash final.
// En local : http://localhost:8000
// En production : https://votre-domaine.com
define('SITE_URL', 'http://localhost:8000');

define('EVENT_NAME', 'Organisation Fête St Sylvestre');
define('EVENT_TAGLINE', "Réveillon de la Saint-Sylvestre");
define('EVENT_START', '2026-12-31 21:00:00'); // début de soirée
define('EVENT_MIDNIGHT', '2026-12-31 23:59:00'); // cible du compte à rebours (passage à la nouvelle année)
define('EVENT_END', '2027-01-01 05:00:00');
define('EVENT_VENUE', 'Immeuble du Supermarché Delta (avant la Pharmacie St Abel)');
define('EVENT_CITY', 'Calavi Tankpè');

// Devise affichée sur le site (FCFA / XOF par défaut pour Momo, Flooz, Celtiis Cash).
define('CURRENCY_LABEL', 'FCFA');

// Tarifs des pass.
define('PRICE_SOLO', 10000);
define('PRICE_COUPLE', 15000);

// Email de contact / organisation affiché sur le site.
define('CONTACT_EMAIL', 'contact@fete-saint-sylvestre.example');

// ---------------------------------------------------------------------------
// Paiement — KkiaPay (agrégateur qui gère en un seul widget : MTN MoMo,
// Moov Flooz, Celtiis Cash et carte bancaire Visa/Mastercard).
// Créez un compte marchand sur https://kkiapay.me puis renseignez vos clés
// ci-dessous. Tant que KKIAPAY_PUBLIC_KEY est vide, le site fonctionne en
// MODE DÉMO : un bouton "Simuler le paiement" permet de tester tout le
// parcours (billet + QR code + validation) sans paiement réel.
// ---------------------------------------------------------------------------
define('KKIAPAY_PUBLIC_KEY', '');   // clé publique (widget côté client)
define('KKIAPAY_PRIVATE_KEY', '');  // clé privée (vérification côté serveur)
define('KKIAPAY_SANDBOX', true);    // true = bac à sable KkiaPay, false = production

define('DEMO_MODE', KKIAPAY_PUBLIC_KEY === '');

// Fuseau horaire pour les dates affichées.
date_default_timezone_set('Africa/Porto-Novo');
