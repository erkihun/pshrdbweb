<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->index('type', 'posts_type_index');
            $table->index('is_published', 'posts_is_published_index');
            $table->index('published_at', 'posts_published_at_index');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->index('is_active', 'services_is_active_index');
            $table->index('sort_order', 'services_sort_order_index');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->index('is_published', 'documents_is_published_index');
            $table->index('published_at', 'documents_published_at_index');
            $table->index('document_category_id', 'documents_category_index');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->index('reference_code', 'tickets_reference_code_index');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_type_index');
            $table->dropIndex('posts_is_published_index');
            $table->dropIndex('posts_published_at_index');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('services_is_active_index');
            $table->dropIndex('services_sort_order_index');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex('documents_is_published_index');
            $table->dropIndex('documents_published_at_index');
            $table->dropIndex('documents_category_index');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('tickets_reference_code_index');
        });
    }
};
