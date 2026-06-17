<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->longText('id_image_front')->nullable()->after('status');
            $table->longText('id_image_back')->nullable()->after('id_image_front');
        });
    }

    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['id_image_front', 'id_image_back']);
        });

    }
};
