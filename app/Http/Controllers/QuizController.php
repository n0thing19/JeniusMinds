<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function editor(){
        return view('quiz.editor');
    }
    public function addbutton(){
        return view('quiz.addbutton');
    }
    public function addcheckbox(){
        return view('quiz.addcheckbox');
    }
    public function addtypeanswer(){
        return view('quiz.addtypeanswer');
    }
    public function addreorder(){
        return view('quiz.addreorder');
    }
}
