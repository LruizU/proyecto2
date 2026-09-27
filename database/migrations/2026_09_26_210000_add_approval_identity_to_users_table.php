<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('approval_identity', 20)
                ->nullable()
                ->after('role')
                ->comment('Identidad para aprobar incidencias: jefe o rector');
            $table->index('approval_identity');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['approval_identity']);
            $table->dropColumn('approval_identity');
        });
    }
};
