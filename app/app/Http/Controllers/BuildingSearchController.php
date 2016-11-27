<?php namespace App\Http\Controllers;

use App\BuildingTag;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class BuildingSearchController extends Controller {

    public function by_tag($building_tag_id)
    {
		$building_tag = BuildingTag::find($building_tag_id);
		$buildings = $building_tag->buildings()->orderBy('name')->get();
		
		return view('pages.buildings', ['buildings' => $buildings, 'building_tag' => $building_tag]);
    }
	
	public function index()
	{
		return view('pages.building_search');
	}

}