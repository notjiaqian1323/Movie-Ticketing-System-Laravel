<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $q = Review::with([
            'movie:id,title',
            'account:id,username,email',
        ]);

        // read filters
        $user = trim((string) $request->query('user', ''));
        $movie = trim((string) $request->query('movie', ''));
        $rating = $request->query('rating');               // "1".."5" or ""
        $from = $request->query('from');                 // yyyy-mm-dd
        $to = $request->query('to');                   // yyyy-mm-dd
        $sort = $request->query('sort', 'new');          // new|old|high|low
        $includeAnon = $request->boolean('include_anon');       // checkbox                       // yyyy-mm-dd

        // user filter
        if ($user !== '') {
            $q->whereHas('account', function ($a) use ($user) {
                $a->where('username', 'like', "%{$user}%")
                    ->orWhere('email', 'like', "%{$user}%");
            });
        }

        // movie title filter
        if ($movie !== '') {
            $q->whereHas('movie', function ($m) use ($movie) {
                $m->where('title', 'like', "%{$movie}%");
            });
        }

        // rating filter
        if ($rating !== null && $rating !== '') {
            $q->where('rating', (int) $rating);
        }

        // date range (use your column name)
        $dateCol = 'review_datetime';
        if ($from)
            $q->whereDate($dateCol, '>=', $from);
        if ($to)
            $q->whereDate($dateCol, '<=', $to);

        //  apply anonymous toggle globally (affects ALL filters)
        if (!$includeAnon) {
            $q->where('is_anonymous', false);
        }

        // sort
        switch ($sort) {
            case 'old':
                $q->orderBy($dateCol, 'asc');
                break;
            case 'high':
                $q->orderBy('rating', 'desc')->orderBy($dateCol, 'desc');
                break;
            case 'low':
                $q->orderBy('rating', 'asc')->orderBy($dateCol, 'desc');
                break;
            default:
                $q->orderBy($dateCol, 'desc');
                break;
        }

        $reviews = $q->paginate(10)->withQueryString();

        return view('admin.reviews.panel', [
            'reviews' => $reviews,
            'filters' => compact('user', 'movie', 'rating', 'from', 'to', 'sort', 'includeAnon'),
        ]);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
