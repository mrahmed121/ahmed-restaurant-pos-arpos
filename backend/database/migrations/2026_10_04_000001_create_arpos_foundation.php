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
        Schema::create('branches', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->string('code');
            $table->string('address')->nullable(); $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps(); $table->softDeletes();
            $table->unique(['company_id', 'code']);
        });
        Schema::create('dining_tables', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->string('code');
            $table->integer('capacity')->default(4); $table->string('status')->default('available');
            $table->timestamps(); $table->softDeletes();
            $table->unique(['branch_id', 'code']);
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->text('description')->nullable();
            $table->integer('sort_order')->default(0); $table->boolean('is_active')->default(true);
            $table->timestamps(); $table->softDeletes();
        });
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->text('description')->nullable();
            $table->decimal('price', 10, 2); $table->decimal('cost', 10, 2)->default(0);
            $table->string('sku')->nullable(); $table->boolean('is_available')->default(true);
            $table->integer('preparation_time')->default(15);
            $table->timestamps(); $table->softDeletes();
            $table->index(['company_id', 'is_available']);
        });
        Schema::create('modifiers', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->timestamps(); $table->softDeletes();
        });
        Schema::create('menu_item_modifier', function (Blueprint $table) {
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modifier_id')->constrained()->cascadeOnDelete();
            $table->primary(['menu_item_id', 'modifier_id']);
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique(); $table->string('type')->default('dine_in');
            $table->string('status')->default('pending');
            $table->foreignId('table_id')->nullable()->constrained('dining_tables')->nullOnDelete();
            $table->string('customer_name')->nullable(); $table->string('customer_phone')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0); $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0); $table->decimal('total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps(); $table->softDeletes();
            $table->index(['company_id', 'status']); $table->index(['branch_id', 'created_at']);
        });
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); $table->json('modifiers')->nullable(); $table->text('notes')->nullable();
            $table->string('kot_status')->default('pending'); $table->timestamps();
        });
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2); $table->string('method')->default('cash');
            $table->string('reference')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->string('unit');
            $table->decimal('stock_quantity', 12, 3)->default(0);
            $table->decimal('reorder_level', 12, 3)->default(0);
            $table->timestamps(); $table->softDeletes();
        });
        Schema::create('recipe_items', function (Blueprint $table) {
            $table->id(); $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 12, 3); $table->timestamps();
            $table->unique(['menu_item_id', 'ingredient_id']);
        });
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name'); $table->string('role'); $table->string('phone')->nullable();
            $table->decimal('salary', 12, 2)->default(0); $table->boolean('is_active')->default(true);
            $table->timestamps(); $table->softDeletes();
        });
        Schema::create('expenses', function (Blueprint $table) {
            $table->id(); $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('category'); $table->decimal('amount', 12, 2);
            $table->text('description')->nullable(); $table->date('expense_date');
            $table->timestamps(); $table->index(['company_id', 'expense_date']);
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
        foreach (['settings','audit_logs','expenses','employees','recipe_items','ingredients','payments','order_items','orders','menu_item_modifier','modifiers','menu_items','categories','dining_tables','branches','role_user','permission_role','permissions','roles','users','companies'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
