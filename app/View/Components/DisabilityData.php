<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DisabilityData extends Component
{
    public $personName;
    public $questions;

    public function __construct($personName, $questions)
    {
        $this->personName = $personName;
        $this->questions = $questions;
    }

    public function render()
    {
        return view('components.disability-data');
    }
}

?>