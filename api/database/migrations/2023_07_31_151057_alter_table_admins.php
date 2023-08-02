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
        Schema::table('admins', function (Blueprint $table) {
            $table->set('roles', [Role::MASTER, Role::MANAGER, Role::ACCOUNTANT, Role::CAMP])->nullable(true)->after('role');
        });
        $records = DB::table('admins')->get();
        foreach ($records as $record) {
            DB::table('admins')->where('user_id', $record->user_id)->update(['roles' => $record->role]);
        }
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role', 32)->nullable(true)->after('user_id');
        });
        $records = DB::table('admins')->get();
        foreach ($records as $record) {
            DB::table('admins')->where('user_id', $record->user_id)->update(['role' => explode(',', $record->roles)[0]]);
        }
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
};
