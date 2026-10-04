<?php
namespace Tests\Feature;
use Tests\TestCase;

class ArposTest extends TestCase
{
    public function test_health(): void
    {
        $this->getJson('/api/v1/health')->assertOk()->assertJsonPath('service', 'arpos-api');
    }

    public function test_login(): void
    {
        $this->postJson('/api/v1/auth/login', ['email' => 'owner@ahmedfoods.local', 'password' => 'password123'])
            ->assertOk()->assertJsonStructure(['data' => ['token', 'user']]);
    }

    public function test_invalid_login(): void
    {
        $this->postJson('/api/v1/auth/login', ['email' => 'owner@ahmedfoods.local', 'password' => 'wrong'])
            ->assertStatus(401);
    }

    public function test_protected_route(): void
    {
        $this->getJson('/api/v1/menu/items')->assertStatus(401);
    }

    public function test_menu_items(): void
    {
        $token = $this->loginAs('owner@ahmedfoods.local');
        $res = $this->withHeader('Authorization', "Bearer $token")->getJson('/api/v1/menu/items');
        $res->assertOk();
        $this->assertGreaterThan(0, count($res->json('data')));
    }

    public function test_create_order(): void
    {
        $token = $this->loginAs('cashier@ahmedfoods.local');
        $menuRes = $this->withHeader('Authorization', "Bearer $token")->getJson('/api/v1/menu/items?per_page=2');
        $items = $menuRes->json('data');
        $ownerToken = $this->loginAs('owner@ahmedfoods.local');
        $branchRes = $this->withHeader('Authorization', "Bearer $ownerToken")->getJson('/api/v1/branches');
        $branchId = $branchRes->json('data')[0]['id'];

        $res = $this->withHeader('Authorization', "Bearer $token")->postJson('/api/v1/orders', [
            'branch_id' => $branchId, 'type' => 'dine_in',
            'items' => [
                ['menu_item_id' => $items[0]['id'], 'quantity' => 2],
                ['menu_item_id' => $items[1]['id'], 'quantity' => 1],
            ],
        ]);
        $res->assertStatus(201);
        $this->assertNotEmpty($res->json('data.order_number'));
    }

    public function test_company_isolation(): void
    {
        $tokenA = $this->loginAs('owner@ahmedfoods.local');
        $resA = $this->withHeader('Authorization', "Bearer $tokenA")->getJson('/api/v1/menu/items');
        $tokenB = $this->loginAs('owner@secondeats.local');
        $resB = $this->withHeader('Authorization', "Bearer $tokenB")->getJson('/api/v1/menu/items');
        // Company B has no menu items
        $this->assertGreaterThan(0, count($resA->json('data')));
        $this->assertEquals(0, count($resB->json('data')));
    }

    public function test_auditor_readonly(): void
    {
        $token = $this->loginAs('auditor@ahmedfoods.local');
        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/orders', ['branch_id' => 1, 'type' => 'dine_in', 'items' => []])
            ->assertStatus(403);
        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/orders')->assertOk();
    }

    public function test_kitchen_tickets(): void
    {
        $token = $this->loginAs('kitchen@ahmedfoods.local');
        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/kitchen/tickets')->assertOk();
    }

    public function test_waiter_cannot_manage_menu(): void
    {
        $token = $this->loginAs('waiter@ahmedfoods.local');
        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/menu/items', ['category_id' => 1, 'name' => 'Test', 'price' => 100])
            ->assertStatus(403);
    }
}
