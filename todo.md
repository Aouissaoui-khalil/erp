# Liste des tâches pour le développement de l'ERP Tunisien

## Configuration initiale
- [ ] Créer la structure du projet Laravel
- [ ] Intégrer le template Falcon
- [ ] Configurer la base de données
- [ ] Mettre en place l'assistant d'installation (wizard setup)

## Modules de base
- [ ] Gestion des utilisateurs avec hiérarchie de rôles spécifique
  - [ ] Propriétaire de la société (accès aux rapports)
  - [ ] Administrateur système
  - [ ] Agent de vente
  - [ ] Responsable filière
  - [ ] Gestionnaire de stock
  - [ ] Agent de saisie comptable
  - [ ] Comptable
- [ ] Gestion des produits et services
  - [ ] Distinction claire entre produits physiques et services
  - [ ] Catégories d'articles
  - [ ] Unités de mesure
- [ ] Gestion des clients
- [ ] Gestion des fournisseurs

## Gestion avancée des stocks
- [ ] Multi-dépôts (gestion des emplacements multiples)
- [ ] Suivi des numéros de série/lot
- [ ] Gestion des dates d'expiration
- [ ] Mouvements de stock
- [ ] Inventaires

## Ventes et Achats
- [ ] Devis
- [ ] Bons de commande
- [ ] Bons de livraison (BL)
- [ ] Factures (TVA incluse + timbre fiscal)
- [ ] Retours (Avoirs clients / fournisseurs)
- [ ] Paiements clients / fournisseurs
  - [ ] Paiements en espèces
  - [ ] Paiements par chèque
  - [ ] Paiements par billet à ordre tunisien
  - [ ] Paiements par TPE (Terminal de Paiement Électronique)
  - [ ] Gestion des échéances de paiement

## Comptabilité
- [ ] Journaux (Achat, Vente, Banque, Caisse)
- [ ] Plan Comptable Tunisien (PCG 1997)
- [ ] Écritures comptables
- [ ] Grand livre
- [ ] Bilan
- [ ] Compte de résultat
- [ ] Suivi TVA (collectée / déductible)
- [ ] Déclarations fiscales automatiques (mensuelles / trimestrielles)

## Rapports
- [ ] Relevé client / fournisseur
- [ ] Rapport de stock
- [ ] Rapport de ventes / achats
- [ ] Rapport comptable (PDF + Excel)

## Conformité légale (Tunisie)
- [ ] TVA gérée par famille de produit
- [ ] Timbre fiscal sur factures
- [ ] Calcul de la retenue à la source (R.S)
- [ ] Génération des fichiers PDF normalisés
- [ ] Intégration des formats d'export conformes à la DGI

## Sécurité
- [ ] Authentification Laravel Fortify / Breeze
- [ ] CSRF, XSS protection
- [ ] Logging et audit trail

## Livrables
- [ ] Code source Laravel complet
- [ ] Script d'installation automatique avec base de données et seeders
- [ ] Documentation d'installation et d'utilisation
- [ ] Interface responsive intégrée avec le template Falcon
- [ ] Démo locale avec données fictives
- [ ] Dépôt Git complet avec tous les fichiers sources
