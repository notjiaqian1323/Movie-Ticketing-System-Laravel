<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AccountApiController extends Controller
{
    /**
     * API: Get usernames for a list of account IDs.
     */
    public function getUsernamesByIds(Request $request)
    {
        $accountIds = explode(',', $request->query('ids', ''));

        $usernames = Account::select('id', 'username')
            ->whereIn('id', $accountIds)
            ->get()
            ->keyBy('id');

        return response()->json($usernames);
    }
}