<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $action; // Public o'zgaruvchi

    /**
     * Create a new component instance.
     *
     * @param string $action
     * @return void
     */
    public function __construct($action = '#')
    {
        // O'zgaruvchini konstruktor orqali uzatish
        $this->action = $action;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
