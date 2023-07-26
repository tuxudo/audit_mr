<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class AuditMr extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('audit_mr', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->nullable();
            $table->text('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->bigInteger('timestamp')->nullable();
            $table->string('action')->nullable();
            $table->string('role')->nullable();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('audit_mr');
    }
}
