<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mes candidatures</title>
  <style>
    :root {
      --primary: #2ecc71;
      --accent: #3498db;
      --dark: #2c3e50;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                  url('https://images.unsplash.com/photo-1501183638710-841dd1904471?auto=format&fit=crop&w=1600&q=80');
      background-size: cover;
      background-position: center;
      min-height: 100vh;
      padding: 2rem;
      color: white;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .logo {
      font-size: 1.8rem;
      font-weight: bold;
      color: var(--primary);
    }

    .back-link {
      color: white;
      text-decoration: underline;
      font-size: 0.95rem;
    }

    h2 {
      font-size: 1.6rem;
      margin-bottom: 1rem;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.5rem;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      color: #333;
      overflow: hidden;
      transition: transform 0.2s ease;
      display: flex;
      flex-direction: column;
    }

    .card:hover {
      transform: translateY(-4px);
    }

    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .card-content {
      padding: 1rem;
    }

    .title {
      font-weight: bold;
      font-size: 1.1rem;
      margin-bottom: 0.4rem;
    }

    .meta {
      font-size: 0.95rem;
      color: #555;
      margin-bottom: 0.4rem;
    }

    .status {
      font-weight: bold;
      color: var(--accent);
    }

    .loading {
      text-align: center;
      color: #ccc;
      margin: 2rem auto;
    }

    a.card-link {
      color: inherit;
      text-decoration: none;
    }

    a.card-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

  <h2>📄 Mes candidatures</h2>

  <div class="grid" id="grid">
    <!-- JS 动态添加 Ajout dynamique JS-->
  </div>
  <div class="loading" id="loading">Chargement...</div>

  <script>
    const data = Array.from({ length: 60 }).map((_, i) => ({
      id: 1000 + i,
      img: `https://source.unsplash.com/random/600x400?sig=${i + 20}`,
      title: `Appartement ${i + 1} avec balcon`,
      meta: `Ville ${75000 + i} - ${600 + i} € / mois`,
      status: "En attente"
    }));

    let index = 0;
    const step = 8;
    const grid = document.getElementById("grid");
    const loading = document.getElementById("loading");

    function loadMore() {
      const next = data.slice(index, index + step);
      next.forEach(item => {
        const card = document.createElement("a");
        card.href = `ad.html?id=${item.id}`;
        card.classList.add("card-link");
        card.innerHTML = `
          <div class="card">
            <img src="${item.img}" alt="${item.title}" />
            <div class="card-content">
              <div class="title">${item.title}</div>
              <div class="meta">${item.meta}</div>
              <div class="status">${item.status}</div>
            </div>
          </div>
        `;
        grid.appendChild(card);
      });

      index += step;

      if (index >= data.length) {
        observer.disconnect();
        loading.textContent = "Toutes les candidatures sont affichées.";
      }
    }

    const observer = new IntersectionObserver(entries => {
      if (entries[0].isIntersecting) loadMore();
    }, { rootMargin: "100px" });

    observer.observe(loading);
  </script>
  <?php include __DIR__ . '/footer.html'; ?>
</body>
</html>
