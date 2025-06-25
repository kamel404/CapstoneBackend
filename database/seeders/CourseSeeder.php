<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Faculty;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the JSON file path
        $json = File::get(base_path('storage/app/data/courses.json'));
        $courses = json_decode($json, true);

        foreach ($courses as $item) {
            $courseName = $item['Course'];

            // Extract course code and title
            if (preg_match('/^([A-Z]+\d+)\s*-\s*(.+)$/', $courseName, $matches)) {
                $code = $matches[1];
                $name = $matches[2];
            } else {
                // fallback if format is unexpected
                $code = Str::random(6);
                $name = $courseName;
            }
            $faculty = Faculty::where('abbreviation', $item['Faculty'])->first();

            if ($faculty) {
                // Check if course already exists with same code and faculty
                $existing = Course::where('code', $code)
                    ->where('faculty_id', $faculty->id)
                    ->exists();

                if (!$existing) {
                    Course::create([
                        'title' => $name,
                        'credits' => 0,
                        'year' => null,
                        'semester' => null,
                        'code' => $code,
                        'faculty_id' => $faculty->id,
                        'major_id' => optional($faculty->majors()->first())->id ?? 1,
                    ]);
                }
            } else {
                // optionally log or throw if faculty is missing
                logger()->warning("Faculty not found: " . $item['Faculty']);
            }
        }
    }
}
