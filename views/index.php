<?php
require_once __DIR__ . '/../models/init_database.php';
session_start();
$user = null;
if (isset($_SESSION['user'])) {
    header('Location: index2.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>HomeStudent - Se loger Facilement</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
</head>
<body class="bg-gray-100 text-gray-900">

  <!-- HEADER -->
  <header style="background-color: #6b9080;" class="text-white py-1">
    <div class="container mx-auto px-4 py-4 flex flex-wrap items-center justify-between">
      <div class="flex items-center space-x-4">
        <img src="logo-removebg-preview.png" alt="logo" class="h-10 w-auto"/>
        <span class="text-xl font-bold text-white">Home4Student</span>
      </div>
      <nav class="flex flex-col sm:flex-row sm:space-x-6 space-y-2 sm:space-y-0 mt-4 sm:mt-0">
        <a href="#" class="hover:text-green-400">Offres</a>
        <a href="#" class="hover:text-green-400">Messagerie</a>
        <a href="/Home4Student-mvc/views/faq_back.html" class="hover:text-green-400">FAQ</a>
        <a href="/Home4Student-mvc/views/contact.html" class="hover:text-green-400">Contact</a>
        <?php if (!isset($_SESSION['user'])): ?>
          <a href="/Home4Student-mvc/views/login.html" class="text-white border border-white px-4 py-1 rounded hover:bg-white hover:text-gray-800">Sign in</a>
          <a href="/Home4Student-mvc/views/register.html" class="bg-green-500 px-4 py-1 rounded text-white hover:bg-green-600">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- HERO -->
  <section class="text-xl font-bold text-[#6b9080] text-center py-12">
    <h1 class="text-3xl md:text-5xl font-bold mb-4">Bienvenue</h1>
    <p class="text-lg">Se loger facilement, pour les étudiants par des étudiants</p>
  </section>

  <!-- SECTION 1 -->
  <main class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
      <!-- Texte -->
      <div>
        <div class="space-y-4">
          <div class="flex items-start space-x-3">
            <i class="fas fa-check-circle text-xl"style="color: #6b9080;"></i>
            <p>Des logements fiables, vérifiés par nos équipes</p>
          </div>
          <div class="flex items-start space-x-3">
            <i class="fas fa-check-circle text-xl"style="color: #6b9080;"></i>
            <p>Une communication directe avec le propriétaire</p>
          </div>
          <div class="flex items-start space-x-3">
            <i class="fas fa-check-circle  text-xl "style="color: #6b9080;"></i>
            <p>Partout en France</p>
          </div>
        </div>
        <div class="mt-6">
          <a href="/Home4Student-mvc/views/ads_list.html" class="inline-flex items-center text-white px-6 py-2 rounded hover:bg-opacity-80" style="background-color: #6b9080;">
            Accéder aux annonces <i class="fas fa-arrow-right ml-2"></i>
          </a>
        </div>
      </div>
      <!-- Image -->
      <div>
        <img src="acc.jpg" alt="img" class="w-full h-auto rounded shadow"/>
      </div>
    </div>
  </main>

  <!-- SECTION 2 -->
  <section class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
      <!-- Image -->
      <div>
        <img src="acc2.jpg" alt="img" class="w-full h-auto rounded shadow"/>
      </div>
      <!-- Texte -->
      <div>
        <h2 class="text-2xl font-bold mb-4">Vendez vous-même un bien immobilier !</h2>
        <div class="space-y-4">
          <div class="flex items-start space-x-3">
            <i class="fas fa-check-circle text-xl"style="color: #6b9080;"></i>
            <p>Présentez votre bien et ses caractéristiques</p>
          </div>
          <div class="flex items-start space-x-3">
            <i class="fas fa-check-circle text-xl"style="color: #6b9080;"></i>
            <p>Définissez un prix de vente</p>
          </div>
          <div class="flex items-start space-x-3">
            <i class="fas fa-check-circle text-xl"style="color: #6b9080;"></i>
            <p>Mettez en avant ce qui le rend unique</p>
          </div>
        </div>
        <div class="mt-6">
          <a href="/Home4Student-mvc/views/deposit_ad.html" class="inline-flex items-center text-white px-6 py-2 rounded hover:bg-opacity-80" style="background-color: #6b9080;">
            Déposer une annonce <i class="fas fa-upload ml-2"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="text-white py-8" style="background-color: #6b9080;">
    <div class="container mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div>
        <div class="flex items-center space-x-2 mb-4">
          <i class="fas fa-graduation-cap text-green-400 text-2xl"></i>
          <span class="font-bold text-lg">HomeStudent</span>
        </div>
        <div class="flex space-x-4">
          <a href="#"><i class="fab fa-twitter hover:text-green-400"></i></a>
          <a href="#"><i class="fab fa-instagram hover:text-green-400"></i></a>
          <a href="#"><i class="fab fa-facebook hover:text-green-400"></i></a>
          <a href="#"><i class="fab fa-linkedin hover:text-green-400"></i></a>
        </div>
      </div>
      <div>
        <h3 class="font-semibold mb-2">L'entreprise</h3>
        <ul class="space-y-1">
          <li><a href="#" class="hover:text-green-400">Qui sommes-nous ?</a></li>
          <li><a href="/Home4Student-mvc/views/contact.html" class="hover:text-green-400">Nous contacter</a></li>
        </ul>
      </div>
      <div>
        <h3 class="font-semibold mb-2">Services pro</h3>
        <ul>
          <li><a href="#" class="hover:text-green-400">Accès client</a></li>
        </ul>
      </div>
      <div>
        <h3 class="font-semibold mb-2">À découvrir</h3>
        <ul class="space-y-1">
          <li><a href="#" class="hover:text-green-400">Tout l'immobilier</a></li>
          <li><a href="#" class="hover:text-green-400">Toutes les villes</a></li>
          <li><a href="#" class="hover:text-green-400">Tous les départements</a></li>
          <li><a href="#" class="hover:text-green-400">Toutes les régions</a></li>
        </ul>
      </div>
    </div>
    <div class="text-center text-sm mt-6">&copy; 2023 HomeStudent - Tous droits réservés.</div>
  </footer>
</body>
</html>
