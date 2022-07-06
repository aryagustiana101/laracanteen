<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    public $limit = 10;
    public $sequence = 'desc';
    public $search;

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }

    public function render()
    {
        $users = '';
        if ($this->search ?? false) {
            $users = User::with('administrator', 'student', 'teacher', 'seller', 'teller')->filter(['keyword' => $this->search])->orderBy('id', $this->sequence)->paginate($this->limit);
        } else {
            $users = User::with('administrator', 'student', 'teacher', 'seller', 'teller')->orderBy('id', $this->sequence)->paginate($this->limit);
        }
        return view('livewire.user-index', [
            'users' => $users
        ]);
    }
}
