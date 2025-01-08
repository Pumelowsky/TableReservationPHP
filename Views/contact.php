<?php include './layout/header.php'; ?>

<div class="container contact py-5">
  <h2 class="text-center mb-4">Skontaktuj się z nami</h2>
  <p class="text-center mb-5">Masz pytania? Wypełnij formularz poniżej, a skontaktujemy się z Tobą tak szybko, jak to możliwe.</p>

  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <form action="send_message.php" method="POST">
        <div class="mb-3">
          <label for="name" class="form-label">Imię i nazwisko</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Adres e-mail</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Wiadomość</label>
          <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Wyślij</button>
      </form>
    </div>
  </div>
</div>

<?php include './layout/footer.php'; ?>