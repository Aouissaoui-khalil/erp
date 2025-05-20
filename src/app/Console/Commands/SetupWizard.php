<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SetupWizard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:wizard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assistant d\'installation en une commande pour l\'ERP Tunisien';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Assistant d\'installation de l\'ERP Tunisien ===');
        $this->info('Bienvenue dans l\'assistant d\'installation de l\'ERP Tunisien.');
        $this->info('Cet assistant va vous guider à travers les étapes d\'installation.');
        $this->newLine();

        // Vérification des prérequis
        $this->info('Vérification des prérequis...');
        $this->checkRequirements();

        // Configuration de la base de données
        $this->setupDatabase();

        // Installation des dépendances
        $this->installDependencies();

        // Migration et seeding
        $this->migrateAndSeed();

        // Configuration de l'entreprise principale
        $this->setupCompany();

        // Finalisation
        $this->finalize();

        return Command::SUCCESS;
    }

    /**
     * Vérification des prérequis
     */
    private function checkRequirements()
    {
        $this->info('Vérification de PHP...');
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->error('PHP 8.1 ou supérieur est requis. Version actuelle: ' . PHP_VERSION);
            exit(1);
        }
        $this->info('✓ PHP ' . PHP_VERSION . ' détecté.');

        $this->info('Vérification des extensions PHP...');
        $requiredExtensions = ['pdo', 'mbstring', 'xml', 'curl', 'gd', 'zip', 'bcmath'];
        foreach ($requiredExtensions as $extension) {
            if (!extension_loaded($extension)) {
                $this->error("L'extension PHP '$extension' est requise mais n'est pas installée.");
                exit(1);
            }
        }
        $this->info('✓ Toutes les extensions PHP requises sont installées.');

        $this->info('Vérification des permissions...');
        $paths = [
            base_path('storage'),
            base_path('bootstrap/cache'),
        ];
        foreach ($paths as $path) {
            if (!is_writable($path)) {
                $this->error("Le chemin '$path' doit être accessible en écriture.");
                exit(1);
            }
        }
        $this->info('✓ Toutes les permissions sont correctes.');
    }

    /**
     * Configuration de la base de données
     */
    private function setupDatabase()
    {
        $this->info('Configuration de la base de données...');

        $dbConnection = $this->choice(
            'Quel type de base de données souhaitez-vous utiliser?',
            ['mysql', 'pgsql', 'sqlite'],
            0
        );

        if ($dbConnection === 'sqlite') {
            $this->createSqliteDatabase();
        } else {
            $dbHost = $this->ask('Hôte de la base de données', '127.0.0.1');
            $dbPort = $this->ask('Port de la base de données', $dbConnection === 'mysql' ? '3306' : '5432');
            $dbName = $this->ask('Nom de la base de données', 'erp_tunisien');
            $dbUsername = $this->ask('Nom d\'utilisateur de la base de données');
            $dbPassword = $this->secret('Mot de passe de la base de données');

            // Test de la connexion
            try {
                $this->info('Test de la connexion à la base de données...');
                $connection = [
                    'host' => $dbHost,
                    'port' => $dbPort,
                    'database' => $dbName,
                    'username' => $dbUsername,
                    'password' => $dbPassword,
                ];
                
                config(["database.connections.{$dbConnection}" => array_merge(
                    config("database.connections.{$dbConnection}"),
                    $connection
                )]);
                
                DB::connection($dbConnection)->getPdo();
                $this->info('✓ Connexion à la base de données réussie.');
            } catch (\Exception $e) {
                $this->error('Impossible de se connecter à la base de données: ' . $e->getMessage());
                if ($this->confirm('Voulez-vous réessayer?', true)) {
                    return $this->setupDatabase();
                }
                exit(1);
            }

            // Mise à jour du fichier .env
            $this->updateEnvironmentFile([
                'DB_CONNECTION' => $dbConnection,
                'DB_HOST' => $dbHost,
                'DB_PORT' => $dbPort,
                'DB_DATABASE' => $dbName,
                'DB_USERNAME' => $dbUsername,
                'DB_PASSWORD' => $dbPassword,
            ]);
        }
    }

    /**
     * Création d'une base de données SQLite
     */
    private function createSqliteDatabase()
    {
        $dbPath = database_path('database.sqlite');
        if (!File::exists($dbPath)) {
            File::put($dbPath, '');
            $this->info("✓ Base de données SQLite créée à: $dbPath");
        }

        $this->updateEnvironmentFile([
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => $dbPath,
        ]);
    }

    /**
     * Installation des dépendances
     */
    private function installDependencies()
    {
        $this->info('Installation des dépendances...');

        if (!File::exists(base_path('vendor'))) {
            $this->info('Installation des dépendances PHP via Composer...');
            $this->executeCommand('composer install --no-interaction');
        } else {
            $this->info('✓ Les dépendances PHP sont déjà installées.');
        }

        if ($this->confirm('Voulez-vous installer les dépendances JavaScript (npm)?', true)) {
            $this->info('Installation des dépendances JavaScript via NPM...');
            $this->executeCommand('npm install');
            $this->executeCommand('npm run build');
        }
    }

    /**
     * Migration et seeding de la base de données
     */
    private function migrateAndSeed()
    {
        $this->info('Préparation de la base de données...');

        if (!File::exists(base_path('.env'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->info('Fichier .env créé.');
            $this->executeCommand('php artisan key:generate');
        }

        if ($this->confirm('Voulez-vous exécuter les migrations de la base de données?', true)) {
            $this->info('Exécution des migrations...');
            $this->executeCommand('php artisan migrate');
        }

        if ($this->confirm('Voulez-vous remplir la base de données avec des données de démonstration?', true)) {
            $this->info('Exécution des seeders...');
            $this->executeCommand('php artisan db:seed');
        }
    }

    /**
     * Configuration de l'entreprise principale
     */
    private function setupCompany()
    {
        $this->info('Configuration de l\'entreprise principale...');

        if (Schema::hasTable('companies')) {
            $companyName = $this->ask('Nom de l\'entreprise', 'Ma Société');
            $companyLegalName = $this->ask('Raison sociale', 'Ma Société SARL');
            $companyTaxId = $this->ask('Matricule fiscal', '1234567/A/M/000');
            $companyAddress = $this->ask('Adresse', '123 Rue Principale');
            $companyCity = $this->ask('Ville', 'Tunis');
            $companyPhone = $this->ask('Téléphone', '+216 71 123 456');
            $companyEmail = $this->ask('Email', 'contact@masociete.com');

            // Création de l'entreprise principale
            DB::table('companies')->insert([
                'name' => $companyName,
                'legal_name' => $companyLegalName,
                'tax_id' => $companyTaxId,
                'address' => $companyAddress,
                'city' => $companyCity,
                'country' => 'Tunisie',
                'phone' => $companyPhone,
                'email' => $companyEmail,
                'is_main' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info('✓ Entreprise principale configurée avec succès.');
        } else {
            $this->warn('La table des entreprises n\'existe pas encore. Veuillez d\'abord exécuter les migrations.');
        }
    }

    /**
     * Finalisation de l'installation
     */
    private function finalize()
    {
        $this->info('Nettoyage du cache...');
        $this->executeCommand('php artisan optimize:clear');

        $this->info('Génération des liens symboliques pour le stockage...');
        $this->executeCommand('php artisan storage:link');

        $adminEmail = $this->ask('Email de l\'administrateur', 'admin@example.com');
        $adminPassword = $this->secret('Mot de passe de l\'administrateur (min. 8 caractères)');

        if (strlen($adminPassword) < 8) {
            $this->error('Le mot de passe doit contenir au moins 8 caractères.');
            $adminPassword = $this->secret('Mot de passe de l\'administrateur (min. 8 caractères)');
        }

        if (Schema::hasTable('users')) {
            // Création de l'utilisateur administrateur
            DB::table('users')->insert([
                'name' => 'Administrateur',
                'email' => $adminEmail,
                'password' => bcrypt($adminPassword),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info('✓ Utilisateur administrateur créé avec succès.');
        } else {
            $this->warn('La table des utilisateurs n\'existe pas encore. Veuillez d\'abord exécuter les migrations.');
        }

        $this->newLine();
        $this->info('=== Installation terminée avec succès! ===');
        $this->info('Vous pouvez maintenant démarrer l\'application avec:');
        $this->line('  php artisan serve');
        $this->info('Et accéder à l\'application à l\'adresse:');
        $this->line('  http://localhost:8000');
        $this->newLine();
        $this->info('Identifiants administrateur:');
        $this->line("  Email: $adminEmail");
        $this->line('  Mot de passe: ********');
    }

    /**
     * Exécute une commande shell
     */
    private function executeCommand($command)
    {
        $process = proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes, base_path());

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $status = proc_close($process);

        if ($status !== 0) {
            $this->error("Erreur lors de l'exécution de la commande: $command");
            $this->error($error);
            if (!$this->confirm('Voulez-vous continuer malgré cette erreur?', false)) {
                exit(1);
            }
        }

        return $output;
    }

    /**
     * Met à jour le fichier .env
     */
    private function updateEnvironmentFile(array $values)
    {
        $envFile = base_path('.env');

        if (!File::exists($envFile)) {
            File::copy(base_path('.env.example'), $envFile);
        }

        $envContent = File::get($envFile);

        foreach ($values as $key => $value) {
            $envContent = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $envContent
            );

            if (!preg_match("/^{$key}=/m", $envContent)) {
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envFile, $envContent);
    }
}
