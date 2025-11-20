<?php

namespace Database\Seeders;

use App\Models\AccountPayable;
use App\Models\AccountReceivable;
use App\Models\Alert;
use App\Models\Appointment;
use App\Models\Company;
use App\Models\Customer;
use App\Models\FiscalDocument;
use App\Models\OrderService;
use App\Models\Product;
use App\Models\Reseller;
use App\Models\Sale;
use App\Models\Service;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warranty;
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
                'manage customers',
                'manage products',
                'manage services',
                'manage order services',
                'manage sales',
                'apply sale discount',
                'manage finances',
                'manage integrations',
                'manage warranties',
                'manage stock',
                'manage alerts',
                'manage scheduling',
            ])->map(fn (string $name) => Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
            ]));

            $roles = collect([
                'Administrador' => $permissions->pluck('name')->all(),
                'Vendedor' => [
                    'view platform status',
                    'manage customers',
                    'manage products',
                    'manage services',
                    'manage sales',
                    'apply sale discount',
                    'manage integrations',
                ],
                'Técnico' => [
                    'view platform status',
                    'manage customers',
                    'manage order services',
                    'manage scheduling',
                    'manage warranties',
                ],
                'Financeiro' => [
                    'view platform status',
                    'manage finances',
                    'manage customers',
                    'apply sale discount',
                    'manage integrations',
                    'manage alerts',
                ],
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
                [
                    'company_id' => $company->id,
                    'document' => $attributes['document'],
                ],
                $attributes + ['company_id' => $company->id]
            ));

            $customers = collect([
                [
                    'name' => 'Loja Center Auto',
                    'document' => '12345678901',
                    'email' => 'contato@centerauto.test',
                    'phone' => '+55 11 95555-0011',
                    'mobile_phone' => '+55 11 95555-0011',
                    'state' => 'SP',
                    'city' => 'São Paulo',
                    'zip_code' => '01000-000',
                    'address' => 'Rua do Motor, 10 - São Paulo/SP',
                    'reseller_id' => $resellers[0]->id,
                ],
                [
                    'name' => 'Mercado Central',
                    'document' => '23456789012',
                    'email' => 'compras@mercadocentral.test',
                    'phone' => '+55 48 93333-0222',
                    'mobile_phone' => '+55 48 93333-0222',
                    'state' => 'SC',
                    'city' => 'Florianópolis',
                    'zip_code' => '88000-000',
                    'address' => 'Av. das Nações, 50 - Florianópolis/SC',
                    'reseller_id' => $resellers[0]->id,
                ],
                [
                    'name' => 'Auto Peças Brasil',
                    'document' => '34567890123',
                    'email' => 'vendas@autobrasil.test',
                    'phone' => '+55 92 92222-0333',
                    'mobile_phone' => '+55 92 92222-0333',
                    'state' => 'AM',
                    'city' => 'Manaus',
                    'zip_code' => '69000-000',
                    'address' => 'Rua da Oficina, 70 - Manaus/AM',
                    'reseller_id' => $resellers[1]->id,
                ],
                [
                    'name' => 'Restaurante Bom Sabor',
                    'document' => '45678901234',
                    'email' => 'financeiro@bomsabor.test',
                    'phone' => '+55 11 96666-0444',
                    'mobile_phone' => '+55 11 96666-0444',
                    'state' => 'SP',
                    'city' => 'São Paulo',
                    'zip_code' => '04000-000',
                    'address' => 'Rua do Lazer, 12 - São Paulo/SP',
                    'reseller_id' => null,
                ],
                [
                    'name' => 'Clínica Vida Plena',
                    'document' => '56789012345',
                    'email' => 'contato@vidaplena.test',
                    'phone' => '+55 11 97777-0555',
                    'mobile_phone' => '+55 11 97777-0555',
                    'state' => 'SP',
                    'city' => 'São Paulo',
                    'zip_code' => '05000-000',
                    'address' => 'Alameda da Saúde, 101 - São Paulo/SP',
                    'reseller_id' => null,
                ],
            ])->map(function (array $customerData) use ($company) {
                return Customer::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'document' => $customerData['document'],
                    ],
                    $customerData + ['company_id' => $company->id]
                );
            });

            $products = collect([
                [
                    'name' => 'Sistema PDV Homstech',
                    'sku' => 'PDV-001',
                    'category' => 'Software',
                    'retail_price' => 149.90,
                    'wholesale_price' => 129.90,
                    'description' => 'Licença mensal do PDV integrado ao estoque e fiscal.',
                    'stock' => 50,
                    'stock_minimum' => 5,
                ],
                [
                    'name' => 'Impressora Térmica USB',
                    'sku' => 'IMP-USB-100',
                    'category' => 'Equipamentos',
                    'retail_price' => 899.00,
                    'wholesale_price' => 799.00,
                    'description' => 'Impressora térmica 80mm homologada para NFC-e.',
                    'stock' => 25,
                    'stock_minimum' => 5,
                ],
                [
                    'name' => 'Gaveta Automática de Dinheiro',
                    'sku' => 'GAV-AUTO-50',
                    'category' => 'Acessórios',
                    'retail_price' => 459.90,
                    'wholesale_price' => 399.90,
                    'description' => 'Gaveta com abertura elétrica para PDVs Homstech.',
                    'stock' => 15,
                    'stock_minimum' => 3,
                ],
            ])->map(fn (array $product) => Product::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'sku' => $product['sku'],
                ],
                $product + ['company_id' => $company->id]
            ));

            $services = collect([
                [
                    'name' => 'Implantação PDV',
                    'category' => 'Implantação',
                    'price' => 1200.00,
                    'duration_minutes' => 240,
                    'description' => 'Pacote completo de implantação e treinamento do PDV.',
                ],
                [
                    'name' => 'Suporte Técnico Remoto',
                    'category' => 'Suporte',
                    'price' => 199.90,
                    'duration_minutes' => 60,
                    'description' => 'Plano mensal de suporte remoto 24/7.',
                ],
                [
                    'name' => 'Consultoria Fiscal NFC-e',
                    'category' => 'Consultoria',
                    'price' => 750.00,
                    'duration_minutes' => 120,
                    'description' => 'Acompanhamento e configuração completa de NFC-e.',
                ],
            ])->map(fn (array $service) => Service::updateOrCreate(
                ['name' => $service['name'], 'company_id' => $company->id],
                $service + ['company_id' => $company->id]
            ));

            $users = collect([
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
            ])->map(function (array $userData) use ($company, $roles) {
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

                $user->syncRoles([$roles[$userData['role']]]);

                return $user;
            });

            $technicians = $users->filter(fn (User $user) => $user->hasRole('Técnico'))->values();

            foreach ($products as $product) {
                StockMovement::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'reference_type' => null,
                        'reference_id' => null,
                        'type' => 'adjustment',
                    ],
                    [
                        'company_id' => $company->id,
                        'user_id' => $users[0]->id,
                        'quantity' => $product->stock,
                        'description' => 'Saldo inicial de estoque',
                        'occurred_at' => now()->subDays(10),
                    ]
                );
            }

            $orderServices = [
                [
                    'customer_id' => $customers[0]->id,
                    'title' => 'Implantação completa PDV',
                    'description' => 'Configuração inicial, treinamento e checklist fiscal.',
                    'status' => 'in_progress',
                    'priority' => 'high',
                    'total_value' => 1899.90,
                    'opened_at' => now()->subDays(5),
                ],
                [
                    'customer_id' => $customers[3]->id,
                    'title' => 'Suporte remoto emergencial',
                    'description' => 'Correção de falha em emissor NFC-e.',
                    'status' => 'open',
                    'priority' => 'normal',
                    'total_value' => 299.90,
                    'opened_at' => now()->subDay(),
                ],
            ];

            $orderServices = collect($orderServices)->map(fn (array $data) => OrderService::updateOrCreate(
                ['title' => $data['title'], 'company_id' => $company->id],
                $data + [
                    'company_id' => $company->id,
                    'reseller_id' => null,
                    'assigned_user_id' => $users[2]->id,
                ]
            ));

            $sales = [
                [
                    'customer_id' => $customers[0]->id,
                    'subtotal' => 2049.80,
                    'discount_total' => 49.80,
                    'total' => 2000.00,
                    'status' => 'confirmed',
                    'sold_at' => now()->subDays(2),
                ],
                [
                    'customer_id' => $customers[4]->id,
                    'subtotal' => 1049.80,
                    'discount_total' => 0,
                    'total' => 1049.80,
                    'status' => 'confirmed',
                    'sold_at' => now()->subDay(),
                ],
            ];

            $sales = collect($sales)->map(fn (array $sale) => Sale::updateOrCreate(
                ['customer_id' => $sale['customer_id'], 'sold_at' => $sale['sold_at']],
                $sale + [
                    'company_id' => $company->id,
                    'reseller_id' => null,
                    'user_id' => $users[1]->id,
                ]
            ));

            $receivables = collect([
                [
                    'customer_id' => $customers[0]->id,
                    'sale_id' => $sales[0]->id,
                    'amount' => 1000.00,
                    'due_date' => now()->addDays(10),
                    'status' => 'pending',
                ],
                [
                    'customer_id' => $customers[0]->id,
                    'order_service_id' => $orderServices[0]->id,
                    'amount' => 899.90,
                    'due_date' => now()->addDays(30),
                    'status' => 'pending',
                ],
            ])->map(fn (array $receivable) => AccountReceivable::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'customer_id' => $receivable['customer_id'],
                    'sale_id' => $receivable['sale_id'] ?? null,
                    'order_service_id' => $receivable['order_service_id'] ?? null,
                ],
                $receivable + ['company_id' => $company->id]
            ));

            $payables = collect([
                [
                    'reseller_id' => $resellers[0]->id,
                    'vendor_name' => 'Fornecedor Equipamentos Sul',
                    'amount' => 3500.00,
                    'due_date' => now()->addDays(20),
                    'status' => 'pending',
                ],
                [
                    'reseller_id' => null,
                    'vendor_name' => 'Serviços de Consultoria Fiscal',
                    'amount' => 1200.00,
                    'due_date' => now()->addDays(5),
                    'status' => 'pending',
                ],
            ])->map(fn (array $payable) => AccountPayable::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'vendor_name' => $payable['vendor_name'],
                ],
                $payable + ['company_id' => $company->id]
            ));

            foreach ($receivables as $receivable) {
                $receivable->payments()->updateOrCreate(
                    ['company_id' => $company->id, 'method' => 'boleto'],
                    [
                        'company_id' => $company->id,
                        'amount' => $receivable->amount,
                        'due_date' => $receivable->due_date,
                        'notes' => 'Parcela a receber',
                    ]
                );
            }

            foreach ($payables as $payable) {
                $payable->payments()->updateOrCreate(
                    ['company_id' => $company->id, 'method' => 'transferencia'],
                    [
                        'company_id' => $company->id,
                        'amount' => $payable->amount,
                        'due_date' => $payable->due_date,
                        'notes' => 'Conta a pagar registrada',
                    ]
                );
            }

            $warranties = [
                [
                    'customer_id' => $customers[0]->id,
                    'sale_id' => $sales[0]->id,
                    'product_id' => $products[0]->id,
                    'service_id' => $services[0]->id,
                    'starts_at' => now()->subDays(2),
                    'expires_at' => now()->addMonths(12),
                    'status' => 'active',
                ],
                [
                    'customer_id' => $customers[3]->id,
                    'order_service_id' => $orderServices[1]->id,
                    'product_id' => $products[1]->id,
                    'service_id' => $services[1]->id,
                    'starts_at' => now()->subDay(),
                    'expires_at' => now()->addMonths(6),
                    'status' => 'active',
                ],
            ];

            foreach ($warranties as $warranty) {
                Warranty::updateOrCreate(
                    [
                        'customer_id' => $warranty['customer_id'],
                        'product_id' => $warranty['product_id'],
                    ],
                    $warranty + ['company_id' => $company->id]
                );
            }

            Alert::updateOrCreate(
                ['company_id' => $company->id, 'type' => 'os_stale'],
                ['threshold_days' => 3, 'is_active' => true]
            );

            \App\Models\Appointment::factory()->create([
                'company_id' => $company->id,
                'customer_id' => $customers[0]->id,
                'order_service_id' => $orderServices[0]->id,
                'technician_id' => $technicians[0]->id,
                'starts_at' => now()->addDay(),
                'ends_at' => now()->addDay()->addHour(),
                'status' => 'scheduled',
                'is_blocked' => false,
            ]);

            FiscalDocument::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'document_type' => 'nfe',
                    'uf' => 'MS',
                ],
                [
                    'customer_id' => $customers[0]->id,
                    'total' => 1200,
                    'status' => 'pending',
                    'scheduled_at' => now()->addMinutes(5),
                ]
            );
        });
    }
}
