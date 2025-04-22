<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer mon profil</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        :root { --primary: #2ecc71; --secondary: #e74c3c; --accent: #3498db; --dark: #2c3e50; --light: #ecf0f1; }
        body { background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center; color: white; min-height: 100vh; }
        header { padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; background: rgba(44, 62, 80, 0.95); }
        .logo { font-size: 2rem; font-weight: bold; color: var(--primary); }
        nav a { color: white; text-decoration: none; margin-left: 2rem; transition: color 0.3s; }
        nav a:hover { color: var(--primary); }
        .login-section { display: flex; justify-content: center; align-items: center; min-height: 100vh; background: rgba(44, 62, 80, 0.8); }
        .login-container { background: rgba(255, 255, 255, 0.95); padding: 3rem; border-radius: 10px; width: 100%; max-width: 400px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }
        .login-container h2 { text-align: center; color: var(--primary); margin-bottom: 2rem; font-size: 2rem; }
        .input-group { margin-bottom: 1.5rem; }
        .input-group label { font-size: 1rem; color: var(--dark); }
        .input-group input { width: 100%; padding: 1rem; border: 1px solid #ddd; border-radius: 5px; margin-top: 0.5rem; font-size: 1rem; }
        .input-group input:focus { border-color: var(--primary); outline: none; }
        .btn { width: 100%; padding: 1rem; border: none; border-radius: 25px; font-weight: bold; cursor: pointer; transition: opacity 0.3s; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { opacity: 0.8; }
    </style>
</head>
<body>
    <header>
        <div class="logo">SeLogerFacilement</div>
        <nav>
            <a href="#" id="homePageLink" class="btn btn-primary">Page d'accueil</a>
        </nav>
    </header>

    <div class="login-section">
        <div class="login-container">
            <h2>Compléter mon profil</h2>
            
            <form action="../controllers/UserController.php?action=update_profile" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" required placeholder="Votre prénom">
                </div>
                <div class="input-group">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" required placeholder="Votre nom">
                </div>
                <div class="input-group">
                    <label for="tele">Numéro de téléphone</label>
                    <input type="tel" name="tele" id="tele" required placeholder="Votre téléphone">
                </div>
                <div class="input-group">
                    <label for="photo">Photo de profil</label>
                    <input type="file" name="photo" id="photo" accept="image/*">
                </div>
                <input type="hidden" name="role" value="<?php echo $_SESSION['role']; ?>">
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
            </form>

        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            var email = localStorage.getItem('connectedUserEmail');
            var users = JSON.parse(localStorage.getItem('users')) || [];
            var user = users.find(u => u.email === email);

            if (!email || !user) {
                window.location.href = '/views/login.html';
            } else {
                // Pré-remplir les champs si le profil existe
                document.getElementById('firstName').value = user.firstName || '';
                document.getElementById('lastName').value = user.lastName || '';
                document.getElementById('phoneNumber').value = user.phoneNumber || '';
            }
        });

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var email = localStorage.getItem('connectedUserEmail');
            var users = JSON.parse(localStorage.getItem('users')) || [];
            var userIndex = users.findIndex(u => u.email === email);

            var firstName = document.getElementById('firstName').value;
            var lastName = document.getElementById('lastName').value;
            var phoneNumber = document.getElementById('phoneNumber').value;
            var file = document.getElementById('profileImage').files[0];

            // Validation supplémentaire pour s'assurer que le numéro de téléphone est rempli
            if (!phoneNumber) {
                alert('Veuillez entrer votre numéro de téléphone.');
                return;
            }

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    users[userIndex] = {
                        ...users[userIndex],
                        firstName: firstName,
                        lastName: lastName,
                        phoneNumber: phoneNumber,
                        profileImage: e.target.result
                    };
                    localStorage.setItem('users', JSON.stringify(users));
                    window.location.href = '/views/index2.php';
                };
                reader.readAsDataURL(file);
            } else {
                users[userIndex] = {
                    ...users[userIndex],
                    firstName: firstName,
                    lastName: lastName,
                    phoneNumber: phoneNumber,
                    profileImage: users[userIndex].profileImage || ''
                };
                localStorage.setItem('users', JSON.stringify(users));
                window.location.href = '/views/index2.php';
            }
        });

        // Ajout de la vérification avant la redirection
        document.getElementById('homePageLink').addEventListener('click', function(e) {
            e.preventDefault();

            var firstName = document.getElementById('firstName').value;
            var lastName = document.getElementById('lastName').value;
            var phoneNumber = document.getElementById('phoneNumber').value;

            if (!firstName && !lastName && !phoneNumber) {
                window.location.href = '/views/index.php';
            } else {
                window.location.href = '/views/index2.php';
            }
        });
    </script>
</body>
</html>