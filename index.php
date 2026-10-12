<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CyberMind – Cybersecurity Awareness Tool</title>
  <script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID&vault=true&intent=subscription&currency=EUR"></script>
  <link rel="icon" type="" href="Icon.png">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #0b1630ff, #1e293b);
      color: white;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
    }
    header h1 {
      display: flex;
      align-items: center;
      font-size: 1.5rem;
      font-weight: bold;
    }
    header svg {
      width: 40px;
      height: 40px;
      margin-right: 10px;
    }
    .hero {
      text-align: center;
      padding: 4rem 2rem;
    }
    .hero h2 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
    }
    .hero p {
      max-width: 600px;
      margin: 0 auto 2rem auto;
      color: #cbd5e1;
    }
    .hero button {
      background: #3b82f6;
      border: none;
      padding: 1rem 2rem;
      border-radius: 12px;
      font-size: 1.1rem;
      cursor: pointer;
      color: white;
    }
    .pricing {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
      padding: 3rem 2rem;
    }
    .card {
      background: #334155;
      padding: 2rem;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .card h3 {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }
    .card p {
      color: #cbd5e1;
      margin-bottom: 1.5rem;
    }
    .benefits {
      text-align: left;
      margin: 1rem 0;
    }
    .benefits li {
      margin: 0.5rem 0;
      list-style: none;
      color: #d1fae5;
    }
    .benefits li::before {
      content: "✅ ";
      color: #22c55e;
    }
    footer {
      text-align: center;
      padding: 1.5rem;
      border-top: 1px solid #475569;
      color: #94a3b8;
    }
    .logo {
      height: 100px;
    }
    .Free_Trial_Button {
      background: #3b82f6;
      border: none;
      padding: 1rem 2rem;
      border-radius: 12px;
      font-size: 1.1rem;
      cursor: pointer;
      color: white;
      margin-top: 1rem;
    }
    .none_benefits {
      text-align: left;
      margin: 1rem 0;
    }
    .none_benefits li {
      margin: 0.5rem 0;
      list-style: none;
      color: #d1fae5;
    }
    .none_benefits li::before {
      content: "❌ ";
      color: #22c55e;
    }
  </style>
</head>
<body>

  <header>
    <img class="logo" src="Logo_no_background.png" alt="">
    <button onclick="window.location.href='login.php'" style="background:#3b82f6;color:white;padding:0.5rem 1rem;border-radius:8px;border:none;cursor:pointer;">
      Login
    </button>
  </header>

  <section class="hero">
    <h2>Schütze Dich oder deine Firma vor Cyberangriffen</h2>
    <p>CyberMind macht Cybersecurity verständlich, interaktiv und sicher. Starte jetzt mit deiner kostenlosen 3-Tage-Testphase!</p>
    <button onclick="redirect_register()">Jetzt kostenlos testen</button>
  </section>

  <section class="pricing">
    <div class="card">
      <h3>Free Trial</h3>
      <p>Teste alle Funktionen 3 Tage kostenlos.</p>
      <ul class="benefits">
        <li>Zugang zu allen Awareness-Modulen</li>
        <li>3 Tage volle Nutzung</li>
        <li>Keine Kreditkarte erforderlich</li>
      </ul>
      <ul class="none_benefits">
        <li>Kein Zugang zum Dashboard</li>
      </ul>
      <button class="Free_Trial_Button" onclick="redirect_register()">Jetzt starten</button>
    </div>

    <div class="card">
      <h3>Einzelperson</h3>
      <p>Nur 6 € / Monat</p>
      <ul class="benefits">
        <li>Alle Lernmodule & Quizze</li>
        <li>Regelmäßige Updates mit neuen Inhalten</li>
        <li>Support per E-Mail</li>
        <li>Ideal für Einzelpersonen</li>
      </ul>
      <button class="Free_Trial_Button" onclick="redirect_payment_school()">Jetzt starten</button>
    </div>

    <div class="card">
      <h3>Firmen</h3>
      <p>10 € / Monat</p>
      <ul class="benefits">
        <li>Alle Lernmodule & Quizze</li>
        <li>Mitarbeiter-Dashboard & Statistiken</li>
        <li>Regelmäßige Updates mit neuen Inhalten</li>
        <li>Support & Beratung inklusive</li>
        <li>Ideal für Unternehmen</li>
      </ul>
      <button class="Free_Trial_Button" onclick="redirect_payment_business()">Jetzt starten</button>
    </div>
  </section>

  <div class="hero">
    <button onclick="window.location.href='invite_register.php'">Mit Invite Code beitreten</button>
  </div>

  <footer>
    © 2025 CyberMind – Alle Rechte vorbehalten
  </footer>

  <script>
    function redirect_register() {
      window.location.href = "register.php";
    }
    function redirect_payment_business() {
      window.location.href = "payment_business.php";
    }
    function redirect_payment_school() {
      window.location.href = "payment_solo.php";
    }
    paypal.Buttons({
      createSubscription: function(data, actions) {
        return actions.subscription.create({
          plan_id: "P-SCHULEN-ID"
        });
      },
      onApprove: function(data, actions) {
        alert('Danke! Dein Schul-Abo ist aktiv.');
      }
    }).render('#paypal-schule');

    paypal.Buttons({
      createSubscription: function(data, actions) {
        return actions.subscription.create({
          plan_id: "P-FIRMEN-ID"
        });
      },
      onApprove: function(data, actions) {
        alert('Danke! Dein Firmen-Abo ist aktiv.');
      }
    }).render('#paypal-firma');
  </script>

</body>
</html>
