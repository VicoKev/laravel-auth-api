# Laravel Sanctum Auth API

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=flat&logo=laravel) ![Sanctum](https://img.shields.io/badge/Sanctum-Auth-blue?style=flat&logo=laravel) ![Scramble](https://img.shields.io/badge/API%20Docs-Scramble-orange)

Laravel Sanctum Auth API est une implémentation complète d'un système d'authentification via API en utilisant **Laravel Sanctum** et **dedoc/scramble** pour la documentation des endpoints.

## ✨ Fonctionnalités

- 📌 Inscription et connexion
- 🔐 Authentification via Laravel Sanctum
- ✉️ Vérification d'email
- 🔄 Déconnexion sécurisée
- 🔑 Gestion du mot de passe (oublié et réinitialisation)
- 📜 Documentation API interactive avec **Scramble**

## 🚀 Installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/VicoKev/laravel-sanctum-auth-api.git
   cd laravel-sanctum-auth-api
   ```
2. **Installer les dépendances**
   ```bash
   composer install
   ```
3. **Configurer l'application**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Configurer la base de données**
   - Mettre à jour `.env` avec les informations de votre base de données.
   - Exécuter les migrations :
     ```bash
     php artisan migrate
     ```

5. **Lancer le serveur**
   ```bash
   php artisan serve
   ```

## 📡 Endpoints API

La documentation des endpoints est générée avec **dedoc/scramble**. Pour l'afficher :
```bash
php artisan serve
```
Puis rendez-vous sur : `http://localhost:8000/docs/api`

## 🛡️ Sécurité & Authentification
- Utilisation de **Laravel Sanctum** pour l'authentification par token.
- Les tokens sont stockés et utilisés pour authentifier les requêtes API.

## 📜 Licence
Ce projet est sous licence [MIT](LICENSE).

## 🤝 Contribution
Toute contribution est la bienvenue ! Merci de créer une issue ou une pull request sur GitHub.
