<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TranslateController extends Controller
{
    /**
     * All translatable UI strings (English source).
     * Keys match data-i18n attributes in blade templates.
     */
    private array $strings = [
        // Sidebar navigation
        'dashboard'           => 'Dashboard',
        'books'               => 'Books',
        'categories'          => 'Categories',
        'students'            => 'Students',
        'issue_book'          => 'Issue Book',
        'borrow_records'      => 'Borrow Records',
        'reports'             => 'Reports',
        'overdue_books'       => 'Overdue Books',
        'fines'               => 'Fines',
        'popular_books'       => 'Popular Books',
        'add_staff'           => 'Add Staff',
        'staff'               => 'Staff List',
        
        // Sidebar section labels
        'main_menu'           => 'Main Menu',
        'catalog'             => 'Catalog',
        'members'             => 'Members',
        'transactions'        => 'Transactions',
        'analytics'           => 'Analytics',
        'administration'      => 'Administration',
        
        // Topbar
        'logout'              => 'Logout',
        'add_staff_btn'       => 'Add Staff',
        
        // Dashboard & Overview Cards
        'welcome_back'        => 'Welcome back',
        'library_today'       => "Here's what's happening in your library today.",
        'total_books'         => 'Total Books',
        'available_now'       => 'available now',
        'active_students'     => 'Active Students',
        'registered_members'  => 'Registered members',
        'active_borrows'      => 'Active Borrows',
        'currently_out'       => 'Currently checked out',
        'overdue'             => 'Overdue',
        'needs_attention'     => 'Needs attention',
        'register_student'    => 'Register Student',
        
        // Common buttons / Actions
        'add_new'             => 'Add New',
        'edit'                => 'Edit',
        'delete'              => 'Delete',
        'view'                => 'View',
        'save'                => 'Save',
        'cancel'              => 'Cancel',
        'search'              => 'Search',
        'filter'              => 'Filter',
        'reset'               => 'Reset',
        'back'                => 'Back',
        'submit'              => 'Submit',
        'confirm'             => 'Confirm',
        'return'              => 'Return',
        'actions'             => 'Actions',
        
        // Books Management
        'book_management'     => 'Book Management',
        'all_books'           => 'All Books',
        'add_book'            => 'Add New Book',
        'book_title'          => 'Title',
        'book_author'         => 'Author',
        'book_isbn'           => 'ISBN',
        'book_category'       => 'Category',
        'book_copies'         => 'Copies',
        'book_available'      => 'Available',
        'book_status'         => 'Status',
        'book_actions'        => 'Actions',
        
        // Students Management
        'student_management'  => 'Student Management',
        'all_students'        => 'All Students',
        'add_student'         => 'Add New Student',
        'student_id'          => 'STUDENT ID',
        'class_major'         => 'CLASS / MAJOR',
        'contact'             => 'CONTACT',
        'borrowing'           => 'BORROWING',
        'none'                => 'None',
        'no_students'         => 'No students found.',
        'register_one'        => 'Register one.',
        'search_placeholder'  => 'Search name, ID, email...',
        'all_status'          => 'All Status',
        'all_majors'          => 'All Majors',
        
        // Borrow Management
        'borrow_management'   => 'Borrow Records',
        'issue_book_title'    => 'Issue Book',
        'return_book'         => 'Return Book',
        
        // Status Translations
        'status_available'    => 'Available',
        'status_unavailable'  => 'Unavailable',
        'status_borrowed'     => 'Borrowed',
        'status_returned'     => 'Returned',
        'status_overdue'      => 'Overdue',
        'status_active'       => 'Active',
        'status_inactive'     => 'Inactive',
        'status_suspended'    => 'Suspended',
        'paid_returned'       => 'Paid / Returned',
        'unpaid'              => 'Unpaid',
        
        // Reports page
        'overview'            => 'Overview',
        'monthly_activity'    => 'Monthly Borrow Activity',
        'borrows_by_category' => 'Borrows by Category',
        'monthly_summary'     => 'Monthly Summary Table',
        'total_borrows'       => 'TOTAL BORROWS',
        'all_time'            => 'All time',
        'active_borrows_lbl'  => 'ACTIVE BORROWS',
        'currently_out2'      => 'Currently out',
        'overdue_lbl'         => 'OVERDUE',
        'needs_attention2'    => 'Needs attention',
        'total_fines'         => 'TOTAL FINES',
        'collected'           => 'Collected',
        'total_books_lbl'     => 'TOTAL BOOKS',
        'in_catalog'          => 'In catalog',
        'students_lbl'        => 'STUDENTS',
        'registered'          => 'Registered',
        'month'               => 'Month',
        'no_data'             => 'No data',
        
        // Overdue page
        'overdue_books_title' => 'Overdue Books',
        'back_to_reports'     => 'Back to Reports',
        'borrow_code'         => 'Borrow Code',
        'student'             => 'Student',
        'book'                => 'Book',
        'borrow_date'         => 'Borrow Date',
        'due_date'            => 'Due Date',
        'days_overdue'        => 'Days Overdue',
        'est_fine'            => 'Est. Fine',
        'all_good'            => 'All Good!',
        'no_overdue'          => 'No overdue books at the moment.',
        
        // Fines page
        'fines_title'         => 'Fines Report',
        'fine_records'        => 'Fine Records',
        'return_date'         => 'Return Date',
        'fine_amount'         => 'Fine Amount',
        'status'              => 'Status',
        'from'                => 'From',
        'to'                  => 'To',
        
        // Popular books
        'popular_title'       => 'Most Borrowed Books',
        'books_ranked'        => 'books ranked',
        'rank'                => 'Rank',
        'author'              => 'Author',
        'category'            => 'Category',
        'copies'              => 'Copies',
        'times_borrowed'      => 'Times Borrowed',
        'popularity'          => 'Popularity',
        'available'           => 'available',
        'times'               => 'times',
        
        // Staff page
        'staff_management'    => 'Staff Management',
        'staff_accounts'      => 'Staff Accounts',
        'name'                => 'Name',
        'email'               => 'Email',
        'role'                => 'Role',
        'joined'              => 'Joined',
        'you'                 => 'You',
    ];

    /**
     * Return English strings (no API call needed).
     */
    public function english()
    {
        session()->put('locale', 'en');
        return response()->json([
            'locale'       => 'en',
            'translations' => $this->strings,
        ]);
    }

    /**
     * Translate all strings to Khmer using Free Gemini API.
     */
    public function khmer()
    {
        session()->put('locale', 'kh');

        // Cache translations for 24 hours to avoid repeated API calls
        $translations = Cache::remember('translations_kh', 86400, function () {
            return $this->translateWithGemini($this->strings);
        });

        return response()->json([
            'locale'       => 'kh',
            'translations' => $translations,
        ]);
    }

    /**
     * Clear translation cache (admin utility).
     */
    public function clearCache()
    {
        Cache::forget('translations_kh');
        return response()->json(['message' => 'Translation cache cleared.']);
    }

    private function translateWithGemini(array $strings): array
    {
        $apiKey = config('services.gemini.key') ?: env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            // Fallback to existing kh lang file if no API key
            return trans()->setLocale('kh') ? __('menu', [], 'kh') : $strings;
        }

        // Build JSON payload
        $jsonInput = json_encode($strings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $prompt = <<<PROMPT
You are a professional Khmer (Cambodian) translator specializing in library management software UI.

Translate ALL the following English UI strings to natural, correct Khmer (ភាសាខ្មែរ).
Keep translations concise and appropriate for a library management system interface.
Do NOT translate proper nouns like ISBN, ID numbers, or technical terms like "API".
For short UI labels (buttons, menu items), keep translations short.

Return ONLY a valid JSON object with the same keys but Khmer values.
Do not add any explanation, markdown formatting (like ```json), or extra conversational text — only the raw JSON object.

English strings to translate:
$jsonInput
PROMPT;

        try {
            // Google Gemini 2.5 Flash Endpoint
            $url = "[https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=](https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=)" . $apiKey;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $body = $response->json();
                $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? '';

                // Strip markdown fences if Gemini inadvertently includes them
                $text = preg_replace('/^```(?:json)?\s*/m', '', $text);
                $text = preg_replace('/\s*```$/m', '', $text);
                $text = trim($text);

                $translated = json_decode($text, true);

                if (is_array($translated) && count($translated) > 0) {
                    // Merge: keep original English label for any missing keys
                    return array_merge($strings, $translated);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Gemini translation failed: ' . $e->getMessage());
        }

        // Fallback: return existing kh translations from local lang file if API fails
        $fallback = [];
        foreach ($strings as $key => $value) {
            $khmer = __('menu.' . $key, [], 'kh');
            $fallback[$key] = ($khmer !== 'menu.' . $key) ? $khmer : $value;
        }
        return $fallback;
    }
}