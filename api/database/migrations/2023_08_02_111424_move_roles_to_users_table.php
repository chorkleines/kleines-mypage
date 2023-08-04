<?php

use App\Enums\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->set('roles', [Role::MASTER, Role::MANAGER, Role::ACCOUNTANT, Role::CAMP])->nullable(true)->after('status');
        });
        $records = DB::table('admins')->get();
        foreach ($records as $record) {
            DB::table('users')->where('id', $record->user_id)->update(['roles' => $record->roles]);
        }
        Schema::drop('admins');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->primary();
            $table->set('roles', [Role::MASTER, Role::MANAGER, Role::ACCOUNTANT, Role::CAMP])->nullable(true);
        });
        $records = DB::table('users')->get();
        foreach ($records as $record) {
            DB::table('admins')->insert(['user_id' => $record->id, 'roles' => $record->roles]);
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
};
