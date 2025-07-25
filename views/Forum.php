<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Forum des annonces </title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="Forum.css">
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

