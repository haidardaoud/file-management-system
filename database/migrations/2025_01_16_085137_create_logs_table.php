<?php

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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المستخدم الذي قام بالعملية
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('cascade'); // الملف الذي تمت عليه العملية
            $table->unsignedBigInteger('group_member_id')->nullable();  // إضافة عمود لربط السجل بعضو في المجموعة
            $table->foreign('group_member_id')->references('id')->on('group_members')->onDelete('cascade');
            $table->string('action'); // نوع العملية (create, update, delete, check-in, check-out, etc.)
            $table->text('details')->nullable(); // تفاصيل إضافية عن العملية
            $table->timestamps(); // لتسجيل وقت العملية
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
        Schema::table('logs', function (Blueprint $table) {
            $table->dropForeign(['group_member_id']);
            $table->dropColumn('group_member_id');
        });
    }
};
