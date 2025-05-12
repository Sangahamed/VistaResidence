<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Gestion des propriétés',
                'slug' => 'property-management',
                'description' => 'Module de gestion des propriétés immobilières',
                'is_core' => true,
                'version' => '1.0',
                'settings' => json_encode([
                    'max_properties' => -1,
                    'max_images' => 20,
                    'enable_featured' => true,
                ]),
            ],
            [
                'name' => 'Gestion des agences',
                'slug' => 'agency-management',
                'description' => 'Module de gestion des agences immobilières',
                'is_core' => true,
                'version' => '1.0',
                'settings' => json_encode([
                    'max_agencies' => -1,
                    'enable_branding' => true,
                ]),
            ],
            [
                'name' => 'Gestion des agents',
                'slug' => 'agent-management',
                'description' => 'Module de gestion des agents immobiliers',
                'is_core' => true,
                'version' => '1.0',
                'settings' => json_encode([
                    'max_agents' => -1,
                    'enable_commissions' => true,
                ]),
            ],
            [
                'name' => 'Gestion des leads',
                'slug' => 'lead-management',
                'description' => 'Module de gestion des prospects et clients potentiels',
                'is_core' => false,
                'version' => '1.0',
                'settings' => json_encode([
                    'enable_auto_assignment' => true,
                    'enable_scoring' => true,
                ]),
            ],
            [
                'name' => 'Alertes de propriétés',
                'slug' => 'property-alerts',
                'description' => 'Module d\'alertes pour les nouvelles propriétés correspondant aux critères',
                'is_core' => false,
                'version' => '1.0',
                'settings' => json_encode([
                    'max_alerts_per_user' => 5,
                    'frequency_options' => ['daily', 'weekly', 'monthly'],
                ]),
            ],
            [
                'name' => 'Calculateur de prêt',
                'slug' => 'mortgage-calculator',
                'description' => 'Module de calcul de prêt immobilier',
                'is_core' => false,
                'version' => '1.0',
                'settings' => json_encode([
                    'default_interest_rate' => 3.5,
                    'max_term_years' => 30,
                ]),
            ],
            [
                'name' => 'Enchères immobilières',
                'slug' => 'property-auctions',
                'description' => 'Module de gestion des enchères immobilières',
                'is_core' => false,
                'version' => '1.0',
                'settings' => json_encode([
                    'enable_live_auctions' => true,
                    'enable_auto_extend' => true,
                ]),
            ],
            [
                'name' => 'Recommandations',
                'slug' => 'property-recommendations',
                'description' => 'Module de recommandations de propriétés basées sur les préférences',
                'is_core' => false,
                'version' => '1.0',
                'settings' => json_encode([
                    'enable_ai_recommendations' => false,
                    'max_recommendations' => 10,
                ]),
            ],
            [
                'name' => 'Rapports et analyses',
                'slug' => 'reports-analytics',
                'description' => 'Module de rapports et d\'analyses avancées',
                'is_core' => false,
                'version' => '1.0',
                'settings' => json_encode([
                    'enable_export' => true,
                    'enable_scheduled_reports' => true,
                ]),
            ],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
