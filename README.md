# ERP Tunisien Laravel - Facturation & Comptabilité

Application de facturation et comptabilité conforme aux normes tunisiennes, développée avec Laravel et le template Falcon.

## Fonctionnalités

### Modules de base
- Gestion des produits et services
- Gestion des clients et fournisseurs
- Catégories d'articles
- Unités de mesure
- Dépôts (multi-stock)

### Ventes / Achats
- Devis
- Bons de commande
- Bons de livraison (BL)
- Factures (TVA incluse + timbre fiscal)
- Retours (Avoirs clients / fournisseurs)
- Paiements clients / fournisseurs

### Comptabilité
- Journaux (Achat, Vente, Banque, Caisse)
- Plan Comptable Tunisien (PCG 1997)
- Écritures comptables
- Grand livre
- Bilan
- Compte de résultat
- Suivi TVA (collectée / déductible)
- Déclarations fiscales automatiques (mensuelles / trimestrielles)

### Rapports
- Relevé client / fournisseur
- Rapport de stock
- Rapport de ventes / achats
- Rapport comptable (PDF + Excel)

### Conformité légale (Tunisie)
- TVA gérée par famille de produit
- Timbre fiscal sur factures
- Calcul de la retenue à la source (R.S)
- Génération des fichiers PDF normalisés
- Intégration des formats d'export conformes à la DGI

## Prérequis techniques

- PHP 8.1 ou supérieur
- MySQL 5.7 ou supérieur
- Composer
- Node.js et NPM

## Installation

1. Cloner le dépôt
```bash
git clone [URL_DU_DEPOT]
cd erp_tunisien
```

2. Installer les dépendances PHP
```bash
cd src
composer install
```

3. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurer la base de données dans le fichier .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_tunisien
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

5. Exécuter les migrations et les seeders
```bash
php artisan migrate --seed
```

6. Lancer l'application
```bash
php artisan serve
```

## Structure des rôles utilisateurs

L'application utilise une hiérarchie de rôles spécifique :
- Propriétaire de la société (accès aux rapports)
- Administrateur système
- Agent de vente
- Responsable filière
- Gestionnaire de stock
- Agent de saisie comptable
- Comptable

## Gestion multi-entreprise

L'application permet de gérer une entreprise principale avec plusieurs filiales, avec une structure hiérarchique claire.

## Multilinguisme

L'application est disponible en :
1. Français (langue principale)
2. Anglais
3. Arabe

## Méthodes de paiement supportées

- Paiements en espèces
- Paiements par chèque
- Paiements par billet à ordre tunisien
- Paiements par TPE (Terminal de Paiement Électronique)
- Gestion des échéances de paiement

## Gestion avancée des stocks

- Multi-dépôts (gestion des emplacements multiples)
- Suivi des numéros de série/lot
- Gestion des dates d'expiration
- Mouvements de stock
- Inventaires

## Licence

Ce projet est sous licence propriétaire.
