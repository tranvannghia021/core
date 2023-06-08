<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Devtvn\Social\Models\Core;

class CreateCoreTable extends Migration
{
    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('social.db_connection'));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create(config('social.models.table.name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('internal_id')->nullable();
            $table->string('platform')->default(config('social.app.name'));
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->text('avatar')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->boolean('status')->default(false);
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->text('address')->nullable();
            $table->text('refresh_token')->nullable();
            $table->text('access_token')->nullable();
            $table->dateTime('expire_token')->nullable();
            $table->boolean('is_disconnect')->default(false);
            $table->string('domain')->nullable();
            $table->string('raw_domain')->nullable();
            $table->jsonb('settings')->nullable();
            if (count(@app(Core::class)->customsFill) > 0) {
                foreach (app(Core::class)->customsFill as $value) {
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
        $this->schema->dropIfExists(config('social.models.table.name'));
    }
}
