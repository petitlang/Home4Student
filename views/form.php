<?php include __DIR__ . '/header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>

  <meta charset="UTF-8">
  <title>Forum des annonces </title>
  <link rel="stylesheet" href="style.css">
  <style>
    
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
    }

    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #66A182;
        padding: 10px 20px;
        height: 70px;
    }
    footer {
        background: #66A182;
        color: white;
        padding: 40px 20px;
    }
    
    .footer-container {
        display: flex;
        justify-content: space-around;
        align-items: flex-start;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .footer-section {
        flex: 1;
        min-width: 200px;
        padding: 10px;
    }
    
    .footer-section h4 {
        margin-bottom: 15px;
    }
    
    .footer-section a {
        display: block;
        color: white;
        text-decoration: none;
        margin-bottom: 8px;
    }
    
    .footer-section a:hover {
        text-decoration: underline;
    }
    

    .logo img {
        height: 50px;
    }

    nav {
        display: flex;
        gap: 20px;
    }

    nav button {
        background: #444;
        color: white;
        padding: 8px 15px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    nav .active {
        background: white;
        color: black;
    }
    .nav-links {
        display: flex;
        gap: 0.5rem;
      }
  
      .nav-links a {
        color: white;
        background-color: var(--primary-dark);
        padding: 0.4rem 0.8rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
      }
  
      .nav-links a:hover {
        background-color: var(--primary-light);
        color: var(--primary-dark);
      } 
      .footer-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-start;
      }
  
      .footer-section {
        flex: 1;
        min-width: 180px;
        margin-bottom: 1rem;
      }
  
      .footer-section h4 {
        margin-bottom: 1rem;
      }
  
      .footer-section a {
        display: block;
        color: white;
        text-decoration: none;
        margin: 0.3rem 0;
      }
  
      .footer-section a:hover {
        text-decoration: underline;
      }
  
      .social-icons {
        display: flex;
        gap: 1rem;
        font-size: 1.5rem;
      }
  
      .social-icons a {
        color: white;
      }
    
    .auth {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .auth button {
        background: white;
        color: black;
        padding: 5px 10px;
        border: none;
        cursor: pointer;
    }

    .auth .register {
        background: #444;
        color: white;
    }

    main {
        padding: 40px 20px;
    }

    .recherche-cooperative {
        text-align: center;
        margin-bottom: 40px;
    }

    .recherche-cooperative input[type="text"] {
        width: 50%;
        padding: 10px;
        border: 2px solid #107838;
        border-radius: 5px;
        background: #66A182;
        color: black;
    }

    .recherche-cooperative button {
        background: #586F77;
        color: white;
        padding: 10px 20px;
        margin-left: 10px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .ajouter-annonce {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 40px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 0 auto 40px;
    }

    .ajouter-annonce textarea {
        width: 100%;
        height: 100px;
        margin-bottom: 10px;
        padding: 10px;
        border: 2px solid #107838;
        border-radius: 5px;
        color: white;
        background: #66A182;
        resize: none;
    }

    .ajouter-annonce button {
        width: 100%;
        background: #586F77;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .annonces {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .annonce {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }

    .annonce p {
        margin: 10px 0;
    }

    .mini-forum {
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px solid #ccc;
    }

    .mini-forum textarea {
        width: 100%;
        height: 60px;
        margin-bottom: 10px;
        padding: 10px;
        border: 2px solid #107838;
        background: #66A182;
        color: white;
        border-radius: 5px;
        resize: none;
    }

    .mini-forum button {
        background: #586F77;
        color: white;
        padding: 8px 16px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    /* Admin section */
    #admin-login, #admin-dashboard {
        max-width: 600px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    #admin-login input[type="password"],
    #admin-dashboard textarea {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        margin-bottom: 20px;
        border: 2px solid #107838;
        border-radius: 5px;
        color:white;
        background: #66A182;
    }

    #admin-login button,
    #admin-dashboard button {
        width: 100%;
        background: #586F77;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .annonces-admin .annonce {
        background: #f1f1f1;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 8px;
    }

    .annonces-admin button {
        margin-top: 10px;
        background: #107838;
    }


  </style>
  <script>
    const adminPassword = "1234";
    let annonceCount = 1;
    let isAdmin = false;
  
    function seConnecter() {
      const password = document.getElementById('adminPassword').value;
      if (password === adminPassword) {
        isAdmin = true;
        document.getElementById('admin-login').style.display = 'none';
        document.getElementById('admin-dashboard').style.display = 'block';
        afficherBoutonsAdmin();
      } else {
        alert("Mot de passe incorrect !");
      }
    }
  
    function ajouterAnnonce(source = 'etudiant') {
      const textareaId = source === 'admin' ? 'description-annonce-admin' : 'description-annonce';
      const description = document.getElementById(textareaId).value;
      if (description.trim() !== "") {
        const annonceSection = document.createElement("div");
        annonceSection.classList.add("annonce");
        annonceSection.innerHTML = `
          <p>Annonce ${annonceCount} ${source === 'admin' ? '(admin)' : ''}</p>
          <p>${description}</p>
          <div class="admin-controls" style="display: ${isAdmin ? 'block' : 'none'}">
            <button onclick="modifierAnnonce(this)">Modifier</button>
            <button onclick="supprimerAnnonce(this)">Supprimer</button>
          </div>
          <div class="mini-forum">
            <div class="commentaires"></div>
            <form onsubmit="ajouterCommentaire(event, this)">
              <input type="text" name="auteur" placeholder="Votre nom ou 'admin'" required>
              <textarea name="commentaire" placeholder="Laissez un commentaire..." required></textarea>
              <button type="submit">Poster</button>
            </form>
          </div>
        `;
        document.querySelector(".annonces").appendChild(annonceSection);
        annonceCount++;
        document.getElementById(textareaId).value = "";
      } else {
        alert("Veuillez saisir une description avant d'ajouter l'annonce.");
      }
    }
  
    function ajouterCommentaire(event, form, parentDiv = null) {
      event.preventDefault();
      const auteur = form.auteur.value.trim();
      const texte = form.commentaire.value.trim();
      if (!texte || !auteur) return;
  
      const commentaire = document.createElement("div");
      commentaire.className = "commentaire";
      commentaire.style.marginTop = "10px";
      commentaire.style.paddingLeft = parentDiv ? "20px" : "0";
      commentaire.innerHTML = `
        <p><strong>${auteur}</strong> : ${texte}</p>
        <button class="repondre-btn" onclick="afficherFormulaireReponse(this)">Répondre</button>
        <div class="reponses"></div>
      `;
  
      const zoneCommentaires = parentDiv
        ? parentDiv.querySelector('.reponses')
        : form.previousElementSibling;
  
      zoneCommentaires.appendChild(commentaire);
  
      form.reset();
      if (parentDiv) form.remove(); 
    }
  
    function afficherFormulaireReponse(button) {
      const commentaireDiv = button.closest('.commentaire');
      if (commentaireDiv.querySelector('form')) return;
  
      const form = document.createElement("form");
      form.onsubmit = (e) => ajouterCommentaire(e, form, commentaireDiv);
      form.innerHTML = `
        <input type="text" name="auteur" placeholder="Votre nom ou 'admin'" required>
        <textarea name="commentaire" placeholder="Votre réponse..." required></textarea>
        <button type="submit">Répondre</button>
      `;
      commentaireDiv.appendChild(form);
    }
  
    function rechercherAnnonce() {
      let input = document.getElementById('searchBar').value.toLowerCase();
      let annonces = document.getElementsByClassName('annonce');
      for (let i = 0; i < annonces.length; i++) {
        let description = annonces[i].getElementsByTagName('p')[1].innerText.toLowerCase();
        annonces[i].style.display = description.includes(input) ? "block" : "none";
      }
    }
  
    function supprimerAnnonce(button) {
      const annonce = button.closest('.annonce');
      annonce.remove();
    }
  
    function modifierAnnonce(button) {
      const annonce = button.closest('.annonce');
      const description = annonce.querySelector('p:nth-child(2)');
      const newDescription = prompt("Modifier la description de l'annonce", description.textContent);
      if (newDescription !== null) {
        description.textContent = newDescription;
      }
    }
  
    function afficherBoutonsAdmin() {
      const adminControls = document.querySelectorAll('.admin-controls');
      adminControls.forEach(control => {
        control.style.display = 'block';
      });
    }
  </script>
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

<main>
  <div id="admin-login">
    <h2>Accès Administrateur</h2>
    <input type="password" id="adminPassword" placeholder="Mot de passe">
    <button onclick="seConnecter()">Se connecter</button>
  </div>

  <div id="admin-dashboard" style="display: none;">
    <h2>Back-office Administrateur</h2>
    <section class="ajouter-annonce-admin">
      <h3>Ajouter une annonce</h3>
      <textarea id="description-annonce-admin" placeholder="Entrez une description de l'annonce"></textarea>
      <button onclick="ajouterAnnonce('admin')">Ajouter l'annonce</button>
    </section>
  </div>

  <h1>Forum des annonces</h1>
  <p>Retrouvez une coopérative et discutez-en avec les autres étudiants.</p>

  <input type="text" id="searchBar" placeholder="Rechercher une coopérative" onkeyup="rechercherAnnonce()">

  <section class="ajouter-annonce">
    <h2>Ajouter une annonce</h2>
    <textarea id="description-annonce" placeholder="Entrez la description de l'annonce" required></textarea>
    <button onclick="ajouterAnnonce()">Ajouter l'annonce</button>
  </section>

  <section class="annonces">
    
  </section>
</main>

<?php include __DIR__ . '/footer.html'; ?>

</body>
</html>
