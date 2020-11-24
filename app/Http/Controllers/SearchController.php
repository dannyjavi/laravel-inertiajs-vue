<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke(Request $request)
	{
		$list = [];

		if ($request->has('q')) {
			$list = $this->autocomplete($request->q);
		}

		if (empty($request->q)) {
			$list = [];
		}
		return Inertia::render("Agenda/Books", ["search" => $list]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function autocomplete($word)
	{
		$users = [];

		$res = User::select("name","id")
			->where("name", "LIKE", "%" . $word . "%")
			->get();

		if (count($res) > 0) {
			$users[] = $res;
		}
		return $users;
	}
}
