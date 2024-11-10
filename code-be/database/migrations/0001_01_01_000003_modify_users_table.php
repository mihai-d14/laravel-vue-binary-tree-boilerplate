<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('_lft')->nullable();
            $table->unsignedInteger('_rgt')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('tree_type')->default('bst'); // 'bst' or 'avl'
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['_lft', '_rgt']);
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['_lft', '_rgt', 'parent_id', 'tree_type']);
        });
    }
};