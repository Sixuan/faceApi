<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 4/17/16
 * Time: 4:16 PM
 */

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;

class GroupController extends Controller
{

    public function index(Request $request) {
        return response()->json(['name' => 'Abigail', 'state' => 'CA']);
    }
}