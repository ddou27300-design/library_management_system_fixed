<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Student;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@library.com'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@library.com',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Create librarian user
        User::firstOrCreate(
            ['email' => 'librarian@library.com'],
            [
                'name'     => 'Librarian',
                'email'    => 'librarian@library.com',
                'password' => Hash::make('password'),
                'role'     => 'librarian',
            ]
        );

        // Seed categories
        $categories = [
            ['name' => 'Science & Technology', 'slug' => 'science-technology', 'description' => 'Books on science, engineering, and technology'],
            ['name' => 'Mathematics',          'slug' => 'mathematics',         'description' => 'Mathematics and statistics books'],
            ['name' => 'Literature',           'slug' => 'literature',          'description' => 'Fiction and literary works'],
            ['name' => 'History',              'slug' => 'history',             'description' => 'Historical books and biographies'],
            ['name' => 'Computer Science',     'slug' => 'computer-science',    'description' => 'Programming and computing books'],
            ['name' => 'Business',             'slug' => 'business',            'description' => 'Business, economics, and management'],
            ['name' => 'Philosophy',           'slug' => 'philosophy',          'description' => 'Philosophy and ethics'],
            ['name' => 'Reference',            'slug' => 'reference',           'description' => 'Dictionaries, encyclopedias, and references'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $cs   = Category::where('slug', 'computer-science')->first();
        $math = Category::where('slug', 'mathematics')->first();
        $lit  = Category::where('slug', 'literature')->first();
        $hist = Category::where('slug', 'history')->first();

        // Seed sample books
        $books = [
            ['title' => 'Clean Code',                    'author' => 'Robert C. Martin',   'isbn' => '9780132350884', 'category_id' => $cs->id,   'published_year' => 2008, 'publisher' => 'Prentice Hall',    'total_copies' => 5, 'available_copies' => 5],
            ['title' => 'The Pragmatic Programmer',      'author' => 'David Thomas',        'isbn' => '9780135957059', 'category_id' => $cs->id,   'published_year' => 2019, 'publisher' => 'Addison-Wesley',   'total_copies' => 3, 'available_copies' => 3],
            ['title' => 'Introduction to Algorithms',   'author' => 'Thomas H. Cormen',    'isbn' => '9780262033848', 'category_id' => $math->id, 'published_year' => 2009, 'publisher' => 'MIT Press',        'total_copies' => 4, 'available_copies' => 4],
            ['title' => 'Design Patterns',               'author' => 'Gang of Four',        'isbn' => '9780201633610', 'category_id' => $cs->id,   'published_year' => 1994, 'publisher' => 'Addison-Wesley',   'total_copies' => 2, 'available_copies' => 2],
            ['title' => 'To Kill a Mockingbird',         'author' => 'Harper Lee',          'isbn' => '9780061935466', 'category_id' => $lit->id,  'published_year' => 1960, 'publisher' => 'HarperCollins',    'total_copies' => 6, 'available_copies' => 6],
            ['title' => 'Sapiens',                       'author' => 'Yuval Noah Harari',   'isbn' => '9780062316097', 'category_id' => $hist->id, 'published_year' => 2015, 'publisher' => 'Harper',           'total_copies' => 4, 'available_copies' => 4],
            ['title' => 'Laravel: Up & Running',         'author' => 'Matt Stauffer',       'isbn' => '9781492041214', 'category_id' => $cs->id,   'published_year' => 2021, 'publisher' => "O'Reilly Media",   'total_copies' => 3, 'available_copies' => 3],
            ['title' => 'Calculus: Early Transcendentals','author'=> 'James Stewart',       'isbn' => '9781285741550', 'category_id' => $math->id, 'published_year' => 2015, 'publisher' => 'Cengage Learning', 'total_copies' => 5, 'available_copies' => 5],
        ];

        foreach ($books as $book) {
            Book::firstOrCreate(['isbn' => $book['isbn']], $book);
        }

        // Seed sample students
        $students = [
            ['student_id' => 'STU-001', 'name' => 'Sophea Meas',     'email' => 'sophea@student.edu',   'phone' => '012345678', 'class' => 'Year 3', 'major' => 'Computer Science'],
            ['student_id' => 'STU-002', 'name' => 'Dara Keo',        'email' => 'dara@student.edu',     'phone' => '012345679', 'class' => 'Year 2', 'major' => 'Mathematics'],
            ['student_id' => 'STU-003', 'name' => 'Sreymom Chan',    'email' => 'sreymom@student.edu',  'phone' => '012345680', 'class' => 'Year 4', 'major' => 'Literature'],
            ['student_id' => 'STU-004', 'name' => 'Vireak Phan',     'email' => 'vireak@student.edu',   'phone' => '012345681', 'class' => 'Year 1', 'major' => 'Business'],
            ['student_id' => 'STU-005', 'name' => 'Bopha Sok',       'email' => 'bopha@student.edu',    'phone' => '012345682', 'class' => 'Year 3', 'major' => 'History'],
        ];

        foreach ($students as $student) {
            Student::firstOrCreate(['student_id' => $student['student_id']], $student);
        }
    }
}