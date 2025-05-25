<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recherchez un logement</title>
<link
  rel="stylesheet" 
  href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
<link rel="stylesheet" href="/views/search.css" />
  
</head>
<body>
  <?php include __DIR__ . '/header.php';  echo "<style> .container {
    background: #4d6a5c;
  }</style>"; ?>
  <div class="container search-bg">
    <div class="filters">
      <input type="text" id="search" placeholder="Search services...">
      <select id="category">
        <option value="All">All Categories</option>
      </select>
      <select id="city">
        <option value="All">All Cities</option>
      </select>
      <input type="number" id="priceMin" placeholder="Prix min (€)"  min="0" step="1">
      <input type="number" id="priceMax" placeholder="Prix max (€)"  min="0" step="1">
    </div>
    <div id="cards" class="cards"></div>
  </div>
  <script>
    let annonces = [];
    const categories = ['Studio', 'Appartement', 'Chambre', 'Maison'];
    const cities = ['Paris', 'Lyon', 'sMarseille', 'Nice', 'Bordeaux', 'Lille', 'Nantes', 'Strasbourg', 'Toulouse', 'Rennes', 'Grenoble', 'Dijon', 'Nancy', 'Montpellier', 'Vendôme', 'Amiens'];

    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('category');
    const citySelect = document.getElementById('city');
    const cardsContainer = document.getElementById('cards');
    const priceMinInput = document.getElementById('priceMin');
    const priceMaxInput = document.getElementById('priceMax');

    function populateFilters() {
      categories.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat;
        option.textContent = cat;
        categorySelect.appendChild(option);
      });

      cities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        citySelect.appendChild(option);
      });
    }

    function renderCards() {
      const search = searchInput.value.toLowerCase();
      const category = categorySelect.value;
      const city = citySelect.value;
      const priceMin = priceMinInput.value ? parseFloat(priceMinInput.value) : 0;
      const priceMax = priceMaxInput.value ? parseFloat(priceMaxInput.value) : Infinity;

      const filtered = annonces.filter(item => {
        const matchText = item.Titre.toLowerCase().includes(search) || item.Descriptions.toLowerCase().includes(search);
        const matchCat = category === 'All' || item.Type === category;
        const matchCity = city === 'All' || item.ville === city;
        const price      = parseFloat(item.Prix);
        const matchPrice = price >= priceMin && price <= priceMax;
        return matchText && matchCat && matchCity&& matchPrice;
      });

      cardsContainer.innerHTML = '';
      filtered.forEach(card => {
        const div = document.createElement('div');
        div.className = 'card';
        div.dataset.id = card.IdAnnonce;

        div.innerHTML = `
            <h3>${card.Titre}</h3>
            <p>${card.ville} • ${card.Type} • ${card.Prix}€</p>
            <p>${card.Descriptions}</p>
          `;

        div.addEventListener('click', () => {
          window.location.href = '../controllers/AdController.php?action=show&id=' + div.dataset.id;
        });

        cardsContainer.appendChild(div);
      });
    }

    searchInput.addEventListener('input', renderCards);
    categorySelect.addEventListener('change', renderCards);
    citySelect.addEventListener('change', renderCards);
    priceMinInput.addEventListener('input', renderCards);
    priceMaxInput.addEventListener('input', renderCards);

    fetch('../models/Admodel.php')
            .then(res => res.json())
            .then(data => {
              annonces = data;
              const categories = [...new Set(annonces.map(a => a.Type))];
              const cities     = [...new Set(annonces.map(a => a.ville))];
              categories.forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat;  opt.textContent = cat;
                categorySelect.appendChild(opt);
              });
              cities.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c;  opt.textContent = c;
                citySelect.appendChild(opt);
              });
              renderCards();
            })
            .catch(err => {
              console.error('Impossible de récupérer les annonces :', err);
              cardsContainer.innerHTML = '<p style="color:red">Erreur de chargement des données.</p>';
            });
  </script>
<?php include __DIR__ . '/footer.html'; ?>
</body>
</html>
