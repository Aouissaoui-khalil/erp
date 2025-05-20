<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Création des rôles selon la hiérarchie spécifique
        $roles = [
            'propriétaire' => 'Propriétaire de la société (accès aux rapports)',
            'administrateur' => 'Administrateur système',
            'agent_vente' => 'Agent de vente',
            'responsable_filiere' => 'Responsable filière',
            'gestionnaire_stock' => 'Gestionnaire de stock',
            'agent_saisie' => 'Agent de saisie comptable',
            'comptable' => 'Comptable'
        ];

        foreach ($roles as $name => $description) {
            Role::create([
                'name' => $name,
                'description' => $description,
                'guard_name' => 'web'
            ]);
        }

        // Création des permissions par module
        $permissions = [
            // Module Base
            'view_products' => 'Voir les produits',
            'create_products' => 'Créer des produits',
            'edit_products' => 'Modifier des produits',
            'delete_products' => 'Supprimer des produits',
            
            'view_services' => 'Voir les services',
            'create_services' => 'Créer des services',
            'edit_services' => 'Modifier des services',
            'delete_services' => 'Supprimer des services',
            
            'view_clients' => 'Voir les clients',
            'create_clients' => 'Créer des clients',
            'edit_clients' => 'Modifier des clients',
            'delete_clients' => 'Supprimer des clients',
            
            'view_suppliers' => 'Voir les fournisseurs',
            'create_suppliers' => 'Créer des fournisseurs',
            'edit_suppliers' => 'Modifier des fournisseurs',
            'delete_suppliers' => 'Supprimer des fournisseurs',
            
            // Module Ventes
            'view_quotes' => 'Voir les devis',
            'create_quotes' => 'Créer des devis',
            'edit_quotes' => 'Modifier des devis',
            'delete_quotes' => 'Supprimer des devis',
            'approve_quotes' => 'Approuver des devis',
            
            'view_orders' => 'Voir les commandes',
            'create_orders' => 'Créer des commandes',
            'edit_orders' => 'Modifier des commandes',
            'delete_orders' => 'Supprimer des commandes',
            
            'view_delivery_notes' => 'Voir les bons de livraison',
            'create_delivery_notes' => 'Créer des bons de livraison',
            'edit_delivery_notes' => 'Modifier des bons de livraison',
            'delete_delivery_notes' => 'Supprimer des bons de livraison',
            
            'view_invoices' => 'Voir les factures',
            'create_invoices' => 'Créer des factures',
            'edit_invoices' => 'Modifier des factures',
            'delete_invoices' => 'Supprimer des factures',
            
            'view_returns' => 'Voir les retours',
            'create_returns' => 'Créer des retours',
            'edit_returns' => 'Modifier des retours',
            'delete_returns' => 'Supprimer des retours',
            
            'view_payments' => 'Voir les paiements',
            'create_payments' => 'Créer des paiements',
            'edit_payments' => 'Modifier des paiements',
            'delete_payments' => 'Supprimer des paiements',
            
            // Module Achats
            'view_purchase_orders' => 'Voir les bons de commande',
            'create_purchase_orders' => 'Créer des bons de commande',
            'edit_purchase_orders' => 'Modifier des bons de commande',
            'delete_purchase_orders' => 'Supprimer des bons de commande',
            
            'view_purchase_invoices' => 'Voir les factures d\'achat',
            'create_purchase_invoices' => 'Créer des factures d\'achat',
            'edit_purchase_invoices' => 'Modifier des factures d\'achat',
            'delete_purchase_invoices' => 'Supprimer des factures d\'achat',
            
            // Module Stock
            'view_inventory' => 'Voir les stocks',
            'manage_inventory' => 'Gérer les stocks',
            'view_warehouses' => 'Voir les dépôts',
            'manage_warehouses' => 'Gérer les dépôts',
            'view_stock_movements' => 'Voir les mouvements de stock',
            'create_stock_movements' => 'Créer des mouvements de stock',
            
            // Module Comptabilité
            'view_accounting' => 'Voir la comptabilité',
            'manage_accounting' => 'Gérer la comptabilité',
            'view_journals' => 'Voir les journaux',
            'create_journal_entries' => 'Créer des écritures comptables',
            'view_tax_reports' => 'Voir les rapports de TVA',
            'generate_tax_reports' => 'Générer des rapports de TVA',
            
            // Module Rapports
            'view_reports' => 'Voir les rapports',
            'export_reports' => 'Exporter les rapports',
            
            // Module Administration
            'manage_users' => 'Gérer les utilisateurs',
            'manage_roles' => 'Gérer les rôles',
            'view_audit_logs' => 'Voir les logs d\'audit',
            'manage_settings' => 'Gérer les paramètres'
        ];

        foreach ($permissions as $name => $description) {
            Permission::create([
                'name' => $name,
                'description' => $description,
                'guard_name' => 'web'
            ]);
        }

        // Attribution des permissions aux rôles
        $role = Role::findByName('propriétaire');
        $role->givePermissionTo([
            'view_reports', 'export_reports',
            'view_accounting', 'view_journals', 'view_tax_reports'
        ]);

        $role = Role::findByName('administrateur');
        $role->givePermissionTo(Permission::all());

        $role = Role::findByName('agent_vente');
        $role->givePermissionTo([
            'view_products', 'view_services', 'view_clients',
            'create_clients', 'edit_clients',
            'view_quotes', 'create_quotes', 'edit_quotes',
            'view_orders', 'create_orders',
            'view_delivery_notes', 'create_delivery_notes',
            'view_invoices', 'create_invoices',
            'view_payments', 'create_payments'
        ]);

        $role = Role::findByName('responsable_filiere');
        $role->givePermissionTo([
            'view_products', 'view_services', 'view_clients', 'view_suppliers',
            'create_clients', 'edit_clients', 'delete_clients',
            'view_quotes', 'create_quotes', 'edit_quotes', 'delete_quotes', 'approve_quotes',
            'view_orders', 'create_orders', 'edit_orders', 'delete_orders',
            'view_delivery_notes', 'create_delivery_notes', 'edit_delivery_notes',
            'view_invoices', 'create_invoices', 'edit_invoices',
            'view_returns', 'create_returns',
            'view_payments', 'create_payments', 'edit_payments',
            'view_reports', 'export_reports'
        ]);

        $role = Role::findByName('gestionnaire_stock');
        $role->givePermissionTo([
            'view_products', 'create_products', 'edit_products',
            'view_inventory', 'manage_inventory',
            'view_warehouses', 'manage_warehouses',
            'view_stock_movements', 'create_stock_movements',
            'view_delivery_notes', 'create_delivery_notes',
            'view_purchase_orders'
        ]);

        $role = Role::findByName('agent_saisie');
        $role->givePermissionTo([
            'view_accounting', 'view_journals', 'create_journal_entries',
            'view_invoices', 'view_purchase_invoices',
            'view_payments'
        ]);

        $role = Role::findByName('comptable');
        $role->givePermissionTo([
            'view_accounting', 'manage_accounting',
            'view_journals', 'create_journal_entries',
            'view_tax_reports', 'generate_tax_reports',
            'view_invoices', 'view_purchase_invoices',
            'view_payments', 'create_payments', 'edit_payments',
            'view_reports', 'export_reports'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des rôles et permissions
        $roles = [
            'propriétaire', 'administrateur', 'agent_vente',
            'responsable_filiere', 'gestionnaire_stock',
            'agent_saisie', 'comptable'
        ];

        foreach ($roles as $role) {
            $roleModel = Role::findByName($role);
            if ($roleModel) {
                $roleModel->delete();
            }
        }

        // Les permissions seront automatiquement supprimées grâce aux relations de la base de données
    }
};
