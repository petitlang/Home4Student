const express = require('express');
const bodyParser = require('body-parser');
const fs = require('fs').promises; // Utilisation de fs.promises pour les opérations asynchrones
const path = require('path');

const app = express();
const port = 3000;

// Middleware pour parser les requêtes JSON avec une limite augmentée pour les photos
app.use(bodyParser.json({ limit: '50mb' }));

// Middleware pour gérer CORS
app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
    res.header('Access-Control-Allow-Headers', 'Content-Type');
    console.log(`Requête reçue: ${req.method} ${req.url}`);
    next();
});

// Chemins des fichiers de données
const USERS_FILE = path.join(__dirname, 'users.json');
const ADS_FILE = path.join(__dirname, 'ads.json');

// Fonctions utilitaires pour lire et écrire les fichiers
async function loadData(file) {
    try {
        const data = await fs.readFile(file, 'utf8');
        return JSON.parse(data);
    } catch (err) {
        if (err.code === 'ENOENT') {
            console.log(`${file} n’existe pas, création d’une liste vide`);
            return [];
        }
        console.error(`Erreur lors de la lecture de ${file}:`, err);
        throw err;
    }
}

async function saveData(file, data) {
    try {
        await fs.writeFile(file, JSON.stringify(data, null, 2), 'utf8');
        console.log(`Données sauvegardées dans ${file}:`, data.length);
    } catch (err) {
        console.error(`Erreur lors de l’écriture dans ${file}:`, err);
        throw err;
    }
}

// Route pour l'inscription
app.post('/register', async (req, res) => {
    const { email, password } = req.body;

    if (!email || !password) {
        return res.status(400).json({ message: 'Email et mot de passe sont requis.' });
    }

    if (password.length < 6) {
        return res.status(400).json({ message: 'Le mot de passe doit contenir au moins 6 caractères.' });
    }

    try {
        const users = await loadData(USERS_FILE);

        if (users.some(user => user.email === email)) {
            return res.status(409).json({ message: 'Cet email est déjà utilisé !' });
        }

        users.push({ email, password });
        await saveData(USERS_FILE, users);
        res.json({ message: 'Compte créé avec succès !' });
    } catch (err) {
        res.status(500).json({ message: 'Erreur serveur lors de l’inscription.' });
    }
});

// Route pour la connexion
app.post('/login', async (req, res) => {
    const { email, password } = req.body;

    if (!email || !password) {
        return res.status(400).json({ message: 'Email et mot de passe sont requis.' });
    }

    try {
        const users = await loadData(USERS_FILE);
        const user = users.find(u => u.email === email && u.password === password);

        if (user) {
            res.json({ message: 'Connexion réussie !', hasProfile: !!user.firstName && !!user.lastName });
        } else {
            res.status(401).json({ message: 'Email ou mot de passe incorrect.' });
        }
    } catch (err) {
        res.status(500).json({ message: 'Erreur serveur lors de la connexion.' });
    }
});

// Route pour déposer une annonce
app.post('/deposit-ad', async (req, res) => {
    const { email, title, price, description, location, photos } = req.body;

    if (!email || !title || !price || !description || !location) {
        return res.status(400).json({ message: 'Tous les champs (email, titre, prix, description, localisation) sont requis.' });
    }

    if (photos && photos.length > 5) {
        return res.status(400).json({ message: 'Maximum 5 photos autorisées.' });
    }

    try {
        const ads = await loadData(ADS_FILE);
        ads.push({ email, title, price, description, location, photos: photos || [] });
        await saveData(ADS_FILE, ads);
        res.json({ message: 'Annonce déposée avec succès !' });
    } catch (err) {
        res.status(500).json({ message: 'Erreur serveur lors du dépôt de l’annonce.' });
    }
});

// Route pour modifier une annonce
app.post('/edit-ad', async (req, res) => {
    const { email, title, price, description, location, photos, index } = req.body;

    if (!email || !title || !price || !description || !location || index === undefined) {
        return res.status(400).json({ message: 'Tous les champs (email, titre, prix, description, localisation, index) sont requis.' });
    }

    if (photos && photos.length > 5) {
        return res.status(400).json({ message: 'Maximum 5 photos autorisées.' });
    }

    try {
        const ads = await loadData(ADS_FILE);

        if (index < 0 || index >= ads.length || ads[index].email !== email) {
            return res.status(403).json({ message: 'Vous ne pouvez modifier que vos propres annonces.' });
        }

        ads[index] = { email, title, price, description, location, photos: photos || ads[index].photos };
        await saveData(ADS_FILE, ads);
        res.json({ message: 'Annonce modifiée avec succès !' });
    } catch (err) {
        res.status(500).json({ message: 'Erreur serveur lors de la modification de l’annonce.' });
    }
});

// Route pour récupérer toutes les annonces
app.get('/ads', async (req, res) => {
    try {
        const ads = await loadData(ADS_FILE);
        res.json(ads);
    } catch (err) {
        res.status(500).json({ message: 'Erreur serveur lors de la récupération des annonces.' });
    }
});

// Démarrer le serveur
app.listen(port, () => {
    console.log(`Serveur démarré sur http://localhost:${port}`);
});

// Gestion des erreurs non capturées
process.on('uncaughtException', (err) => {
    console.error('Erreur non capturée:', err);
});