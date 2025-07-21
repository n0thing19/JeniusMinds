<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Subject;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        $subjectDisplayData = [
            'Mathematics' => ['icon_class' => 'fas fa-calculator', 'color_class' => 'brand-yellow-card', 'image_path' => 'assets/math.png'],
            'English'     => ['icon_class' => 'fas fa-book-open', 'color_class' => 'brand-pink-card', 'image_path' => 'assets/english.png'],
            'Chemistry'   => ['icon_class' => 'fas fa-flask', 'color_class' => 'brand-blue-card', 'image_path' => 'assets/chemistry.png'],
            'Computers'   => ['icon_class' => 'fas fa-desktop', 'color_class' => 'brand-purple-card', 'image_path' => 'assets/computer.png'],
            'Biology'     => ['icon_class' => 'fas fa-dna', 'color_class' => 'brand-green-card', 'image_path' => 'assets/biology.png'],
            'Economy'     => ['icon_class' => 'fas fa-chart-line', 'color_class' => 'brand-gray-card', 'image_path' => 'assets/economy.png'],
            'Geography'   => ['icon_class' => 'fas fa-globe-asia', 'color_class' => 'brand-teal-card', 'image_path' => 'assets/geography.png'],
            'Physics'     => ['icon_class' => 'fas fa-atom', 'color_class' => 'brand-yellow-card', 'image_path' => 'assets/physics.png'],
            'Music'       => ['icon_class' => 'fas fa-music', 'color_class' => 'brand-indigo-card', 'image_path' => 'assets/music.png'],
            'Sports'      => ['icon_class' => 'fas fa-futbol', 'color_class' => 'brand-red-card', 'image_path' => 'assets/sport.png'],
            'Mandarin'    => ['icon_class' => 'fas fa-language', 'color_class' => 'brand-gold-card', 'image_path' => 'assets/mandarin.png'],
        ];

        $topicDisplayData = [
            // Mathematics
            'Algebra' => 'fas fa-square-root-alt', 'Arithmetic' => 'fas fa-sort-numeric-up', 'Trigonometry' => 'fas fa-wave-square', 'Geometry' => 'fas fa-draw-polygon', 'Calculus' => 'fas fa-infinity', 'Statistics' => 'fas fa-chart-pie',
            // English
            'Vocabulary' => 'fas fa-spell-check', 'Grammar' => 'fas fa-pen-fancy', 'Reading' => 'fas fa-readme', 'Speaking' => 'fas fa-microphone-alt', 'Writing' => 'fas fa-pencil-alt', 'Listening' => 'fas fa-headphones',
            // Chemistry
            'Elements' => 'fas fa-atom', 'Reactions' => 'fas fa-burn', 'Acids & Bases' => 'fas fa-vial', 'Organic' => 'fas fa-seedling', 'Stoichiometry' => 'fas fa-balance-scale', 'Thermochem' => 'fas fa-thermometer-half',
            // Computers
            'Programming' => 'fas fa-code', 'Hardware' => 'fas fa-server', 'Networking' => 'fas fa-network-wired', 'AI' => 'fas fa-robot', 'Databases' => 'fas fa-database', 'Cybersecurity' => 'fas fa-shield-alt',
            // Biology
            'Cells' => 'fas fa-microscope', 'Genetics' => 'fas fa-dna', 'Anatomy' => 'fas fa-user-md', 'Botany' => 'fas fa-leaf', 'Ecology' => 'fas fa-globe-africa', 'Zoology' => 'fas fa-paw',
            // Economy
            'Micro' => 'fas fa-search-dollar', 'Macro' => 'fas fa-globe-americas', 'Finance' => 'fas fa-wallet', 'Investing' => 'fas fa-piggy-bank', 'Trade' => 'fas fa-exchange-alt', 'Markets' => 'fas fa-store',
            // Geography
            'Maps' => 'fas fa-map-marked-alt', 'Countries' => 'fas fa-flag', 'Physical' => 'fas fa-mountain', 'Human' => 'fas fa-users', 'Climate' => 'fas fa-cloud-sun-rain', 'Oceans' => 'fas fa-water',
            // Physics
            'Mechanics' => 'fas fa-cogs', 'Electricity' => 'fas fa-bolt', 'Optics' => 'fas fa-eye', 'Waves' => 'fas fa-wave-square', 'Relativity' => 'fas fa-rocket', 'Quantum' => 'fas fa-project-diagram',
            // Music
            'Theory' => 'fas fa-book', 'Instruments' => 'fas fa-guitar', 'History' => 'fas fa-history', 'Composition' => 'fas fa-pen-alt', 'Production' => 'fas fa-sliders-h', 'Vocal' => 'fas fa-microphone',
            // Sports
            'Soccer' => 'fas fa-futbol', 'Basketball' => 'fas fa-basketball-ball', 'Tennis' => 'fas fa-table-tennis', 'Athletics' => 'fas fa-running', 'Swimming' => 'fas fa-swimmer', 'Strategy' => 'fas fa-chess-board',
            // Mandarin
            'Characters' => 'fas fa-pen-alt', 'Pinyin' => 'fas fa-assistive-listening-systems', 'Grammar' => 'fas fa-stream', 'Conversation' => 'fas fa-comments', 'Writing' => 'fas fa-edit', 'Culture' => 'fas fa-landmark'
        ];

        $subjects = Subject::with(['topics' => function ($query) {
            $query->withCount('questions')->take(6);
        }])->get();

        $subjects->each(function ($subject) use ($subjectDisplayData, $topicDisplayData) {
            $displayData = $subjectDisplayData[$subject->subject_name] ?? ['icon_class' => 'fas fa-question-circle', 'color_class' => 'brand-gray-card', 'image_path' => 'assets/default.png'];
            $subject->icon_class = $displayData['icon_class'];
            $subject->color_class = $displayData['color_class'];
            $subject->image_path = $displayData['image_path'];
            $subject->topics->each(function ($topic) use ($topicDisplayData) {
                $topic->icon_class = $topicDisplayData[$topic->topic_name] ?? 'fas fa-puzzle-piece'; 
            });
        });

        return view('homepage.index', ['subjects' => $subjects]);
    }
}
