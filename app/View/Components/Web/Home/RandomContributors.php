<?php

namespace App\View\Components\Web\Home;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class RandomContributors extends Component
{
    public function __construct(protected int $limit = 6)
    {
    }

    public function render(): View
    {
        return view('components.web.home.random-contributors');
    }

    public function contributors(): Collection
    {
        return User::whereIsRegistered()->inRandomOrder()->limit($this->limit)->get();
    }
}
