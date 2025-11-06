<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Company;
use App\Models\Product;
use App\Models\Reseller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::transaction(function () {
            $permissions = collect([
                'view platform status',
                'manage companies',
                'manage resellers',
                'manage clients',
                'manage catalog',
            ])->map(fn (string $name) => Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
            ]));

            $roles = collect([
                'Administrador' => ['view platform status', 'manage companies', 'manage resellers', 'manage clients', 'manage catalog'],
                'Vendedor' => ['view platform status', 'manage clients', 'manage catalog'],
                'Técnico' => ['view platform status'],
                'Financeiro' => ['view platform status'],
            ])->map(function (array $permissionNames, string $roleName) {
                $role = Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web',
                ]);

                $role->syncPermissions($permissionNames);

                return $role;
            });

            $company = Company::firstOrCreate(
                ['document' => '12.345.678/0001-00'],
                [
                    'name' => 'Homstech Tecnologia LTDA',
                    'trade_name' => 'Homstech',
                    'email' => 'contato@homstech.test',
                    'phone' => '+55 11 4002-8922',
                    'address' => 'Av. Paulista, 1000 - São Paulo/SP',
                ]
            );

            $resellers = collect([
                [
                    'document' => '45.678.901/0001-11',
                    'name' => 'Canal Sul Soluções',
                    'email' => 'vendas@canalsul.test',
                    'phone' => '+55 48 3333-0001',
                    'address' => 'Rua das Flores, 321 - Florianópolis/SC',
                ],
                [
                    'document' => '98.765.432/0001-22',
                    'name' => 'Tech Norte Distribuidora',
                    'email' => 'contato@technorte.test',
                    'phone' => '+55 92 3666-1000',
                    'address' => 'Av. Amazonas, 987 - Manaus/AM',
                ],
            ])->map(fn (array $attributes) => Reseller::firstOrCreate(
                ['document' => $attributes['document']],
                $attributes + ['company_id' => $company->id]
            ));

            $clients = [
                [
                    'name' => 'Loja Center Auto',
                    'document' => '12345678901',
                    'email' => 'contato@centerauto.test',
                    'phone' => '+55 11 95555-0011',
                    'address' => 'Rua do Motor, 10 - São Paulo/SP',
                    'reseller_id' => $resellers[0]->id,
                ],
                [
                    'name' => 'Mercado Central',
                    'document' => '23456789012',
                    'email' => 'compras@mercadocentral.test',
                    'phone' => '+55 48 93333-0222',
                    'address' => 'Av. das Nações, 50 - Florianópolis/SC',
                    'reseller_id' => $resellers[0]->id,
                ],
                [
                    'name' => 'Auto Peças Brasil',
                    'document' => '34567890123',
                    'email' => 'vendas@autobrasil.test',
                    'phone' => '+55 92 92222-0333',
                    'address' => 'Rua da Oficina, 70 - Manaus/AM',
                    'reseller_id' => $resellers[1]->id,
                ],
                [
                    'name' => 'Restaurante Bom Sabor',
                    'document' => '45678901234',
                    'email' => 'financeiro@bomsabor.test',
                    'phone' => '+55 11 96666-0444',
                    'address' => 'Rua do Lazer, 12 - São Paulo/SP',
                    'reseller_id' => null,
                ],
                [
                    'name' => 'Clínica Vida Plena',
                    'document' => '56789012345',
                    'email' => 'contato@vidaplena.test',
                    'phone' => '+55 11 97777-0555',
                    'address' => 'Alameda da Saúde, 101 - São Paulo/SP',
                    'reseller_id' => null,
                ],
            ];

            foreach ($clients as $client) {
                Client::firstOrCreate(
                    ['document' => $client['document']],
                    [
                        'company_id' => $company->id,
                        'reseller_id' => $client['reseller_id'],
                        'name' => $client['name'],
                        'email' => $client['email'],
                        'phone' => $client['phone'],
                        'address' => $client['address'],
                    ]
                );
            }

            $products = [
                [
                    'name' => 'Sistema PDV Homstech',
                    'sku' => 'PDV-001',
                    'price' => 149.90,
                    'description' => 'Licença mensal do PDV integrado ao estoque e fiscal.',
                ],
                [
                    'name' => 'Impressora Térmica USB',
                    'sku' => 'IMP-USB-100',
                    'price' => 899.00,
                    'description' => 'Impressora térmica 80mm homologada para NFC-e.',
                ],
                [
                    'name' => 'Gaveta Automática de Dinheiro',
                    'sku' => 'GAV-AUTO-50',
                    'price' => 459.90,
                    'description' => 'Gaveta com abertura elétrica para PDVs Homstech.',
                ],
            ];

            foreach ($products as $product) {
                Product::firstOrCreate(
                    ['sku' => $product['sku']],
                    $product + ['company_id' => $company->id]
                );
            }

            $services = [
                [
                    'name' => 'Implantação PDV',
                    'price' => 1200.00,
                    'description' => 'Pacote completo de implantação e treinamento do PDV.',
                ],
                [
                    'name' => 'Suporte Técnico Remoto',
                    'price' => 199.90,
                    'description' => 'Plano mensal de suporte remoto 24/7.',
                ],
                [
                    'name' => 'Consultoria Fiscal NFC-e',
                    'price' => 750.00,
                    'description' => 'Acompanhamento e configuração completa de NFC-e.',
                ],
            ];

            foreach ($services as $service) {
                Service::firstOrCreate(
                    ['name' => $service['name'], 'company_id' => $company->id],
                    $service + ['company_id' => $company->id]
                );
            }

            $users = [
                [
                    'name' => 'Alice Administradora',
                    'email' => 'admin@homstech.test',
                    'document' => '11122233344',
                    'phone' => '+55 11 90000-0001',
                    'role' => 'Administrador',
                    'reseller_id' => null,
                ],
                [
                    'name' => 'Bruno Vendas',
                    'email' => 'vendas@homstech.test',
                    'document' => '22233344455',
                    'phone' => '+55 11 90000-0002',
                    'role' => 'Vendedor',
                    'reseller_id' => $resellers[0]->id,
                ],
                [
                    'name' => 'Carla Técnica',
                    'email' => 'tecnico@homstech.test',
                    'document' => '33344455566',
                    'phone' => '+55 11 90000-0003',
                    'role' => 'Técnico',
                    'reseller_id' => null,
                ],
                [
                    'name' => 'Diego Financeiro',
                    'email' => 'financeiro@homstech.test',
                    'document' => '44455566677',
                    'phone' => '+55 11 90000-0004',
                    'role' => 'Financeiro',
                    'reseller_id' => null,
                ],
            ];

            foreach ($users as $userData) {
                $user = User::updateOrCreate(
                    ['email' => $userData['email']],
                    [
                        'company_id' => $company->id,
                        'reseller_id' => $userData['reseller_id'],
                        'name' => $userData['name'],
                        'document' => $userData['document'],
                        'phone' => $userData['phone'],
                        'password' => Hash::make('password'),
                    ]
                );

                $user->assignRole($roles[$userData['role']]);
            }
        });
    }
}
