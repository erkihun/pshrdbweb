<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function indexNames(string $table): array
    {
        $results = DB::select("SHOW INDEX FROM `{$table}`");

        return array_values(array_unique(array_map(fn ($index) => $index->Key_name, $results)));
    }

    private function usesMysql(): bool
    {
        return DB::connection()->getDriverName() === 'mysql';
    }

    public function up(): void
    {
        if (! $this->usesMysql()) {
            return;
        }

        $postsIndexes = $this->indexNames('posts');
        $servicesIndexes = $this->indexNames('services');
        $charterIndexes = $this->indexNames('charter_services');
        $orgStatsIndexes = $this->indexNames('org_stats');

        Schema::table('posts', function (Blueprint $table) use ($postsIndexes) {
            if (! in_array('posts_slug_index', $postsIndexes, true)) {
                $table->index('slug');
            }
            if (! in_array('posts_is_published_index', $postsIndexes, true)) {
                $table->index('is_published');
            }
            if (! in_array('posts_published_at_index', $postsIndexes, true)) {
                $table->index('published_at');
            }
        });

        Schema::table('services', function (Blueprint $table) use ($servicesIndexes) {
            if (! in_array('services_slug_index', $servicesIndexes, true)) {
                $table->index('slug');
            }
        });

        Schema::table('charter_services', function (Blueprint $table) use ($charterIndexes) {
            if (! in_array('charter_services_slug_index', $charterIndexes, true)) {
                $table->index('slug');
            }
            if (! in_array('charter_services_department_id_index', $charterIndexes, true)) {
                $table->index('department_id');
            }
        });

        Schema::table('org_stats', function (Blueprint $table) use ($orgStatsIndexes) {
            if (! in_array('org_stats_organization_id_index', $orgStatsIndexes, true)) {
                $table->index('organization_id');
            }
        });
    }

    public function down(): void
    {
        if (! $this->usesMysql()) {
            return;
        }

        $postsIndexes = $this->indexNames('posts');
        $servicesIndexes = $this->indexNames('services');
        $charterIndexes = $this->indexNames('charter_services');
        $orgStatsIndexes = $this->indexNames('org_stats');

        Schema::table('posts', function (Blueprint $table) use ($postsIndexes) {
            if (in_array('posts_slug_index', $postsIndexes, true)) {
                $table->dropIndex(['slug']);
            }
            if (in_array('posts_is_published_index', $postsIndexes, true)) {
                $table->dropIndex(['is_published']);
            }
            if (in_array('posts_published_at_index', $postsIndexes, true)) {
                $table->dropIndex(['published_at']);
            }
        });

        Schema::table('services', function (Blueprint $table) use ($servicesIndexes) {
            if (in_array('services_slug_index', $servicesIndexes, true)) {
                $table->dropIndex(['slug']);
            }
        });

        Schema::table('charter_services', function (Blueprint $table) use ($charterIndexes) {
            if (in_array('charter_services_slug_index', $charterIndexes, true)) {
                $table->dropIndex(['slug']);
            }
            if (in_array('charter_services_department_id_index', $charterIndexes, true)) {
                $table->dropIndex(['department_id']);
            }
        });

        Schema::table('org_stats', function (Blueprint $table) use ($orgStatsIndexes) {
            if (in_array('org_stats_organization_id_index', $orgStatsIndexes, true)) {
                $table->dropIndex(['organization_id']);
            }
        });
    }
};
