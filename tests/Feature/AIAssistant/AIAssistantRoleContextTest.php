<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\AIAssistant\Models\AIConversation;
use App\Domains\AIAssistant\Services\AIManager;
use App\Domains\AIAssistant\Services\AIAssistantService;
use App\Domains\Plans\Models\Plan;
use App\Domains\Subscriptions\Models\Subscription;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('injects role-aware documentation context in assistant prompts', function (string $role, string $expectedSnippet): void {
    $company = sec_create_company('AI Context Co '.$role, 'ai-context-'.$role.'-'.Str::lower(Str::random(6)));
    $user = sec_create_user(
        $company,
        $role.'-context@example.com',
        $role
    );

    $conversation = AIConversation::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'user_id' => $user->id,
        'title' => 'Context Test',
        'provider' => 'azure_openai',
        'model' => 'gpt-4.1-mini',
        'is_active' => true,
    ]);

    $capturedMessages = [];

    $mock = Mockery::mock(AIManager::class);
    $mock->shouldReceive('chat')
        ->once()
        ->andReturnUsing(function (array $messages) use (&$capturedMessages): array {
            $capturedMessages = $messages;

            return [
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Respuesta IA simulada',
                        ],
                    ],
                ],
            ];
        });

    app()->instance(AIManager::class, $mock);

    /** @var AIAssistantService $service */
    $service = app(AIAssistantService::class);

    $response = $service->sendMessage($user, $conversation, 'Necesito ayuda para el flujo');

    expect($response['message'])->toBe('Respuesta IA simulada');
    expect($capturedMessages)->not->toBeEmpty();
    expect($capturedMessages[0]['role'])->toBe('system');
    expect($capturedMessages[0]['content'])->toContain('Enfoque por rol actual');
    expect($capturedMessages[0]['content'])->toContain($expectedSnippet);
})->with([
    ['super_admin', 'gobierno de plataforma'],
    ['owner', 'operaciones de empresa'],
    ['technician', 'ticket -> diagnostico -> cotizacion -> reparacion -> cierre'],
    ['customer', 'estado de ticket/factura/cotizacion'],
]);

it('allows super admin to chat on own conversation through admin endpoint', function (): void {
    $company = sec_create_company('AI Admin Co', 'ai-admin-co');
    $superAdmin = sec_create_user(
        $company,
        'ai-super-admin@example.com',
        'super_admin',
        ['ai.view', 'ai.use']
    );

    $conversation = AIConversation::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'user_id' => $superAdmin->id,
        'title' => 'Admin Conversation',
        'provider' => 'azure_openai',
        'model' => 'gpt-4.1-mini',
        'is_active' => true,
    ]);

    $mock = Mockery::mock(AIManager::class);
    $mock->shouldReceive('chat')
        ->once()
        ->andReturn([
            'choices' => [
                [
                    'message' => [
                        'content' => 'Respuesta admin',
                    ],
                ],
            ],
        ]);

    app()->instance(AIManager::class, $mock);

    $this->actingAs($superAdmin)
        ->post('/admin/ai/chat', [
            'conversation_id' => $conversation->id,
            'message' => 'Dame resumen operativo',
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Respuesta admin');

    $this->actingAs($superAdmin)
        ->get('/admin/ai/conversations')
        ->assertOk()
        ->assertJsonPath('data.0.id', $conversation->id)
        ->assertJsonPath('data.0.last_message_preview', 'Respuesta admin')
        ->assertJsonPath('data.0.last_message_role', 'assistant');

    $this->actingAs($superAdmin)
        ->get('/admin/ai/conversations/'.$conversation->id.'/messages')
        ->assertOk()
        ->assertJsonPath('data.0.role', 'user')
        ->assertJsonPath('data.1.role', 'assistant');
});

it('forbids non-admin roles on admin ai routes even with ai permissions', function (): void {
    $company = sec_create_company('AI Owner Co', 'ai-owner-co');
    $owner = sec_create_user(
        $company,
        'ai-owner@example.com',
        'owner',
        ['ai.view', 'ai.use']
    );

    $conversation = AIConversation::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'user_id' => $owner->id,
        'title' => 'Owner Conversation',
        'provider' => 'azure_openai',
        'model' => 'gpt-4.1-mini',
        'is_active' => true,
    ]);

    $this->actingAs($owner)
        ->post('/admin/ai/chat', [
            'conversation_id' => $conversation->id,
            'message' => 'Consulta de owner',
        ])
        ->assertForbidden();
});

it('prevents cross-user conversation access for super admin', function (): void {
    $company = sec_create_company('AI Isolation Co', 'ai-isolation-co');
    $superAdminA = sec_create_user(
        $company,
        'ai-admin-a@example.com',
        'super_admin',
        ['ai.view', 'ai.use']
    );
    $superAdminB = sec_create_user(
        $company,
        'ai-admin-b@example.com',
        'super_admin',
        ['ai.view', 'ai.use']
    );

    $conversationOwnedByB = AIConversation::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'user_id' => $superAdminB->id,
        'title' => 'Private Conversation',
        'provider' => 'azure_openai',
        'model' => 'gpt-4.1-mini',
        'is_active' => true,
    ]);

    $this->actingAs($superAdminA)
        ->post('/admin/ai/chat', [
            'conversation_id' => $conversationOwnedByB->id,
            'message' => 'No deberia pasar',
        ])
        ->assertNotFound();
});

it('allows owner to use company ai routes with ai permissions and active ai plan', function (): void {
    $company = sec_create_company('AI Company Portal Co', 'ai-company-portal-co');

    $plan = Plan::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Enterprise AI',
        'slug' => 'enterprise-ai-owner',
        'monthly_price' => 120,
        'yearly_price' => 1200,
        'trial_days' => 0,
        'status' => 'active',
        'has_ai' => true,
    ]);

    Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 120,
        'starts_at' => today()->subDay(),
        'ends_at' => today()->addMonth(),
        'status' => 'active',
    ]);

    $owner = sec_create_user(
        $company,
        'ai-company-owner@example.com',
        'owner',
        ['ai.view', 'ai.use', 'analytics.view']
    );

    $conversation = AIConversation::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'user_id' => $owner->id,
        'title' => 'Owner AI Conversation',
        'provider' => 'azure_openai',
        'model' => 'gpt-4.1-mini',
        'is_active' => true,
    ]);

    $mock = Mockery::mock(AIManager::class);
    $mock->shouldReceive('chat')->once()->andReturn([
        'choices' => [[
            'message' => ['content' => 'Respuesta owner company'],
        ]],
    ]);
    app()->instance(AIManager::class, $mock);

    $this->actingAs($owner)
        ->get('/portal/company/ai/conversations')
        ->assertOk()
        ->assertJsonPath('data.0.id', $conversation->id);

    $this->actingAs($owner)
        ->post('/portal/company/ai/chat', [
            'conversation_id' => $conversation->id,
            'message' => 'Necesito ayuda como owner',
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Respuesta owner company');

    $this->actingAs($owner)
        ->get('/portal/company/ai/conversations/'.$conversation->id.'/messages')
        ->assertOk()
        ->assertJsonPath('data.0.role', 'user')
        ->assertJsonPath('data.1.role', 'assistant');
});

it('allows technician to use technician ai routes with ai permissions', function (): void {
    $company = sec_create_company('AI Tech Portal Co', 'ai-tech-portal-co');

    $technician = sec_create_user(
        $company,
        'ai-tech-user@example.com',
        'technician',
        ['ai.view', 'ai.use', 'tickets.view', 'diagnostics.view', 'repairs.view']
    );

    $conversation = AIConversation::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'user_id' => $technician->id,
        'title' => 'Tech AI Conversation',
        'provider' => 'azure_openai',
        'model' => 'gpt-4.1-mini',
        'is_active' => true,
    ]);

    $mock = Mockery::mock(AIManager::class);
    $mock->shouldReceive('chat')->once()->andReturn([
        'choices' => [[
            'message' => ['content' => 'Respuesta tecnico'],
        ]],
    ]);
    app()->instance(AIManager::class, $mock);

    $this->actingAs($technician)
        ->get('/portal/technician/ai/conversations')
        ->assertOk()
        ->assertJsonPath('data.0.id', $conversation->id);

    $this->actingAs($technician)
        ->post('/portal/technician/ai/chat', [
            'conversation_id' => $conversation->id,
            'message' => 'Ayuda tecnica',
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Respuesta tecnico');

    $this->actingAs($technician)
        ->get('/portal/technician/ai/conversations/'.$conversation->id.'/messages')
        ->assertOk()
        ->assertJsonPath('data.0.role', 'user')
        ->assertJsonPath('data.1.role', 'assistant');
});

it('blocks owner from technician ai routes', function (): void {
    $company = sec_create_company('AI Route Isolation Co', 'ai-route-isolation-co');
    $owner = sec_create_user(
        $company,
        'ai-route-owner@example.com',
        'owner',
        ['ai.view', 'ai.use']
    );

    $this->actingAs($owner)
        ->get('/portal/technician/ai/conversations')
        ->assertForbidden();
});

it('blocks owner from company ai routes when plan has no ai module', function (): void {
    $company = sec_create_company('AI No Plan Co', 'ai-no-plan-co');

    $plan = Plan::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Basic',
        'slug' => 'basic-no-ai-owner',
        'monthly_price' => 80,
        'yearly_price' => 800,
        'trial_days' => 0,
        'status' => 'active',
        'has_ai' => false,
    ]);

    Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 80,
        'starts_at' => today()->subDay(),
        'ends_at' => today()->addMonth(),
        'status' => 'active',
    ]);

    $owner = sec_create_user(
        $company,
        'ai-owner-no-plan@example.com',
        'owner',
        ['ai.view', 'ai.use']
    );

    $this->actingAs($owner)
        ->get('/portal/company/ai/conversations')
        ->assertForbidden();
});

it('creates conversation automatically when chat is sent without conversation_id', function (): void {
    $company = sec_create_company('AI Auto Conversation Co', 'ai-auto-conversation-co');
    $technician = sec_create_user(
        $company,
        'ai-auto-tech@example.com',
        'technician',
        ['ai.view', 'ai.use', 'tickets.view', 'diagnostics.view', 'repairs.view']
    );

    $mock = Mockery::mock(AIManager::class);
    $mock->shouldReceive('chat')->once()->andReturn([
        'choices' => [[
            'message' => ['content' => 'Conversacion creada'],
        ]],
    ]);
    app()->instance(AIManager::class, $mock);

    $response = $this->actingAs($technician)
        ->post('/portal/technician/ai/chat', [
            'message' => 'Necesito iniciar conversacion',
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Conversacion creada')
        ->json();

    expect($response['conversation_id'] ?? null)->toBeInt();
    expect(AIConversation::query()->where('id', $response['conversation_id'])->exists())->toBeTrue();
});
