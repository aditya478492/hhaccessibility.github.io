<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


/*
This migration converts various autoincrement primary ids to guids.

This change will help merge information gathered from seed data 
into our deployments.  This is how new information collected from 
our import tools will end up in our public deployments.
*/

function idToGuid($id) {
	$result = ''.$id; // decimal representation.
	while (strlen($result) < 12) {
		$result = '0'.$result;
	}
	return '00000000-0000-0000-0000-'.$result;
}


class Test
{
	public static function convertData(string $tableName)
	{
		echo 'setting new_id for: '.$tableName."\r\n";
		$queryTable = DB::table($tableName);
		$queryTable->update(['new_id' => DB::raw("concat('00000000-0000-0000-0000-', LPAD(id, 12, '0'))")]);
	}
	
    public function convertIdToGuid()
    {
        return function (Blueprint $table) {
			$table->uuid('new_id');
		};
    }
	
	public function undoChange()
	{
        return function (Blueprint $table) {
			$table->dropColumn(['new_id']);
		};
	}
}



class SwitchToGuids extends Migration
{
    public function up()
    {
		$object = new Test;
		$function = $object->convertIdToGuid();
		Schema::table('location', $function);
		Test::convertData('location');
		Schema::table('user', $function);
		Test::convertData('user');
		Schema::table('location_location_tag', $function);
		Test::convertData('location_location_tag');
    }

    public function down()
    {
		$object = new Test;
		$function = $object->undoChange();
		Schema::table('location', $function);
		Schema::table('user', $function);
		Schema::table('location_location_tag', $function);
    }
}
