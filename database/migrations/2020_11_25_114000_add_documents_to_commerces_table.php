<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentsToCommercesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commerces', function (Blueprint $table) {
            $table->string('url_contract')->after('contact_legal_representative')->nullable();
            $table->string('url_interior_image')->after('contact_legal_representative')->nullable();
            $table->string('url_establishment_image')->after('contact_legal_representative')->nullable();
            $table->string('url_cc_legal_representative')->after('contact_legal_representative')->nullable();
            $table->string('url_commerce_room')->after('contact_legal_representative')->nullable();
            $table->string('url_rut')->after('contact_legal_representative')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commerces', function (Blueprint $table) {
            $table->dropColumn('url_rut');
            $table->dropColumn('url_commerce_room');
            $table->dropColumn('url_cc_legal_representative');
            $table->dropColumn('url_establishment_image');
            $table->dropColumn('url_interior_image');
            $table->dropColumn('url_contract');
        });
    }
}