<?php

use App\Enums\Currency;
use App\Enums\StoreActivityType;
use App\Models\Merchant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Merchant::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('activity', StoreActivityType::toArray())->default(StoreActivityType::Retal);
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('address')->nullable();
            $table->string('commercial_register');
            $table->enum('currency', Currency::toArray())->default(Currency::EGP);
            $table->enum('store_size', ['small', 'medium', 'large'])->default('small');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
