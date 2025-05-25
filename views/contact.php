<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact</title>
  <link rel="stylesheet" href="/views/contact.css">
  
</head>
<body>
  <?php include __DIR__ . '/header.php'; ?>

  <div class="contact-card">
    <h2>📬 Contactez-nous</h2>
    <form action="submit_contact.php" method="POST">
      <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
      </div>

      <div class="form-group">
        <label for="sujet">Sujet</label>
        <input type="text" name="sujet" id="sujet" required>
      </div>

      <div class="form-group">
        <label for="message">Message</label>
        <textarea name="message" id="message" required></textarea>
      </div>

      <button class="submit-btn" type="submit">Envoyer</button>
    </form>
  </div>
  <?php include __DIR__ . '/footer.html'; ?>
</body>
</html>
