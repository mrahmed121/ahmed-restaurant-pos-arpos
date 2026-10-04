<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('code')->unique();
            $table->string('address')->nullable(); $table->string('phone')->nullable();
            $table->string('email')->nullable(); $table->string('status')->default('active');
            $table->timestamps(); $table->softDeletes();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name'); $table->string('email'); $table->string('phone')->nullable();
            $table->string('password'); $table->boolean('is_active')->default(true);
            $table->rememberToken(); $table->timestamps(); $table->softDeletes();
            $table->unique(['company_id', 'email']);
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); $table->string('slug')->unique(); $table->string('name');
            $table->string('description')->nullable(); $table->timestamps();
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id(); $table->string('slug')->unique(); $table->string('name');
            $table->string('description')->nullable(); $table->timestamps();
        });
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });
        Schema::create('departments', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps(); $table->softDeletes();
        });
        Schema::create('doctors', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name'); $table->string('specialization');
            $table->string('phone')->nullable(); $table->string('email')->nullable();
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps(); $table->softDeletes();
        });
        Schema::create('patients', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('patient_code')->unique(); $table->string('name');
            $table->string('phone')->nullable(); $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable(); $table->string('gender')->nullable();
            $table->string('blood_group')->nullable(); $table->text('address')->nullable();
            $table->text('medical_history')->nullable();
            $table->timestamps(); $table->softDeletes();
            $table->index(['company_id', 'name']);
        });
        Schema::create('appointments', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('appointment_number')->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at'); $table->string('status')->default('scheduled');
            $table->text('reason')->nullable(); $table->text('notes')->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps(); $table->softDeletes();
            $table->index(['company_id', 'scheduled_at']); $table->index(['doctor_id', 'scheduled_at']);
        });
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->text('diagnosis')->nullable(); $table->text('notes')->nullable();
            $table->timestamps();
        });
        Schema::create('medicines', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->string('generic_name')->nullable();
            $table->string('unit'); $table->decimal('stock_quantity', 12, 2)->default(0);
            $table->decimal('reorder_level', 12, 2)->default(0);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->boolean('requires_prescription')->default(true);
            $table->timestamps(); $table->softDeletes();
        });
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id(); $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
            $table->string('dosage'); $table->integer('quantity');
            $table->text('instructions')->nullable(); $table->timestamps();
        });
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable(); $table->json('new_values')->nullable();
            $table->json('context')->nullable(); $table->timestamps();
            $table->index(['company_id', 'created_at']);
        });
        Schema::create('settings', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('group')->default('general'); $table->string('key');
            $table->json('value')->nullable(); $table->string('type')->default('string');
            $table->timestamps(); $table->unique(['company_id', 'key']);
        });
    }
    public function down(): void {
        foreach (['settings','audit_logs','prescription_items','medicines','prescriptions','appointments','patients','doctors','departments','role_user','permission_role','permissions','roles','users','companies'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
