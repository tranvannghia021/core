<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('database_core')->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('internal_id');
            $table->string('platform')->default(config('social.app.name'));
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->text('avatar')->nullable();
            $table->enum('gender',['male','female','other'])->default('other');
            $table->boolean('status')->default(false);
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->text('address')->nullable();
            $table->text('refresh_token')->nullable();
            $table->text('access_token')->nullable();
            $table->dateTime('expire_token');
            $table->boolean('is_disconnect')->default(false);
            $table->jsonb('settings')->nullable();
           if(count(\App\Models\User::$customsFill) > 0){
               foreach (\App\Models\User::$customsFill  as $value){
                    $table->{$value['type']}($value['column'])->{@$value['define'] ?? 'nullable'}();
               }
           }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('database_core')->dropIfExists('users');
    }
}
