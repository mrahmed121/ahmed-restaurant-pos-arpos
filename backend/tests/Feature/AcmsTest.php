<?php
namespace Tests\Feature;
use Tests\TestCase;
class AcmsTest extends TestCase
{
    public function test_health(): void { $this->getJson('/api/v1/health')->assertOk()->assertJsonPath('service', 'acms-api'); }
    public function test_login(): void {
        $this->postJson('/api/v1/auth/login', ['email' => 'admin@ahmedclinic.local', 'password' => 'password123'])->assertOk()->assertJsonStructure(['data' => ['token', 'user']]);
    }
    public function test_patients(): void {
        $token = $this->loginAs('admin@ahmedclinic.local');
        $res = $this->withHeader('Authorization', "Bearer $token")->getJson('/api/v1/patients');
        $res->assertOk(); $this->assertGreaterThan(0, count($res->json('data')));
    }
    public function test_create_appointment(): void {
        $token = $this->loginAs('receptionist@ahmedclinic.local');
        $p = $this->withHeader('Authorization', "Bearer $token")->getJson('/api/v1/patients')->json('data')[0];
        $d = $this->withHeader('Authorization', "Bearer $token")->getJson('/api/v1/doctors')->json('data')[0];
        $res = $this->withHeader('Authorization', "Bearer $token")->postJson('/api/v1/appointments', [
            'patient_id' => $p['id'], 'doctor_id' => $d['id'],
            'scheduled_at' => now()->addDay()->setHour(10)->toDateTimeString(), 'reason' => 'Checkup',
        ]);
        $res->assertStatus(201); $this->assertNotEmpty($res->json('data.appointment_number'));
    }
    public function test_appointment_conflict(): void {
        $token = $this->loginAs('receptionist@ahmedclinic.local');
        $p = $this->withHeader('Authorization', "Bearer $token")->getJson('/api/v1/patients')->json('data')[0];
        $d = $this->withHeader('Authorization', "Bearer $token")->getJson('/api/v1/doctors')->json('data')[0];
        $time = now()->addDays(2)->setHour(10)->toDateTimeString();
        $this->withHeader('Authorization', "Bearer $token")->postJson('/api/v1/appointments', ['patient_id' => $p['id'], 'doctor_id' => $d['id'], 'scheduled_at' => $time])->assertStatus(201);
        $this->withHeader('Authorization', "Bearer $token")->postJson('/api/v1/appointments', ['patient_id' => $p['id'], 'doctor_id' => $d['id'], 'scheduled_at' => $time])->assertStatus(422);
    }
    public function test_company_isolation(): void {
        $tokenA = $this->loginAs('admin@ahmedclinic.local');
        $tokenB = $this->loginAs('admin@secondcare.local');
        $a = $this->withHeader('Authorization', "Bearer $tokenA")->getJson('/api/v1/patients')->json('data');
        $b = $this->withHeader('Authorization', "Bearer $tokenB")->getJson('/api/v1/patients')->json('data');
        $this->assertGreaterThan(0, count($a)); $this->assertEquals(0, count($b));
    }
    public function test_auditor_readonly(): void {
        $token = $this->loginAs('auditor@ahmedclinic.local');
        $this->withHeader('Authorization', "Bearer $token")->postJson('/api/v1/patients', ['name' => 'Test'])->assertStatus(403);
        $this->withHeader('Authorization', "Bearer $token")->getJson('/api/v1/patients')->assertOk();
    }
    public function test_medicines(): void {
        $token = $this->loginAs('pharmacist@ahmedclinic.local');
        $this->withHeader('Authorization', "Bearer $token")->getJson('/api/v1/medicines')->assertOk();
    }
}
