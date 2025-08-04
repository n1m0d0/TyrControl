<?php

namespace App\Livewire;

use App\Enums\BoxSessionStatusEnum;
use App\Enums\RoleEnum;
use App\Livewire\Forms\BoxSessionForm;
use App\Models\Box;
use App\Models\BoxSession;
use App\Models\Branch;
use App\Models\Company;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentBoxSession extends Component
{
    use WireToast;
    use WithPagination;

    public $activity = 'opening';
    public $search = '';

    public BoxSessionForm $form;

    public $user_id;
    public $role;

    public $company;
    public $company_id;
    public $companies;

    public $branch;
    public $branch_id;
    public $branches;
    public $searchBranches;

    public $box_id;
    public $boxes;
    public $searchBoxes;

    public $statuses;
    public $selectedStatus;

    public function mount()
    {
        $this->user_id = Auth::id();
        $this->companies = Company::select('id', 'name')->get();
        $this->branches = collect();
        $this->searchBranches = collect();
        $this->boxes = collect();
        $this->searchBoxes = collect();
        $this->statuses = BoxSessionStatusEnum::cases();
        $this->role = Auth::user()->roles->first()->name;
    }

    public function render()
    {
        $boxSessions = BoxSession::with('box.branch.company', 'user')
            ->when($this->selectedStatus, fn($query) => $query->where('status', $this->selectedStatus))
            ->when($this->box_id, fn($query) => $query->where('box_id', $this->box_id))
            ->when(
                $this->search,
                fn($query) =>
                $query->whereHas(
                    'user',
                    fn($q) =>
                    $q->where(function ($subQuery) {
                        $subQuery->where('name', 'like', '%' . $this->search . '%');
                    })
                )
            )
            ->when($this->role != RoleEnum::ADMINISTRATOR->value, fn($query) => $query->where('user_id', $this->user_id))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-box-session', compact('boxSessions'));
    }

    public function save()
    {
        if ($this->activity == "opening") {
            $this->form->opening();

            toast()
                ->success('El registro se guardó correctamente.')
                ->push();

            Flux::modal('opening-form')->close();
        }

        if ($this->activity == "closing") {
            $this->form->closing();

            toast()
                ->info('Los cambios se guardaron con éxito.')
                ->push();

            Flux::modal('closing-form')->close();
        }
    }

    public function showForm($id = null)
    {
        $this->company = null;
        $this->branch = null;
        $this->branches = collect();
        $this->boxes = collect();
        $this->form->resetForm();

        if ($id) {
            $boxSession = BoxSession::findOrFail($id);
            $this->form->setBoxSession($boxSession);
            $this->activity = "closing";
            Flux::modal('closing-form')->show();
        } else {
            $user = BoxSession::where('user_id', $this->user_id)->where('status', BoxSessionStatusEnum::OPEN->value)->first();

            if ($user) {
                toast()
                    ->danger('Ya cuenta con una caja abierta.')
                    ->push();
            } else {
                $this->activity = "opening";
                Flux::modal('opening-form')->show();
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedCompany()
    {
        if ($this->company) {
            $this->branches = Branch::select('id', 'name')->where('company_id', $this->company)->get();
        } else {
            $this->branches = collect();
            $this->branch = null;
            $this->boxes = collect();
            $this->form->box = null;
        }
    }

    public function updatedBranch()
    {
        if ($this->branch) {
            $this->boxes = Box::select('id', 'name')
                ->where('branch_id', $this->branch)
                ->whereDoesntHave('boxSessions', function ($query) {
                    $query->where('status', 'open');
                })
                ->get();
        } else {
            $this->boxes = collect();
            $this->form->box = null;
        }
    }

    public function updatedCompanyId()
    {
        if ($this->company_id) {
            $this->searchBranches = Branch::select('id', 'name')->where('company_id', $this->company_id)->get();
        } else {
            $this->searchBranches = collect();
            $this->branch_id = null;
            $this->searchBoxes = collect();
            $this->box_id = null;
        }

        $this->resetPage();
    }

    public function updatedBranchId()
    {
        if ($this->branch_id) {
            $this->searchBoxes = Box::select('id', 'name')->where('branch_id', $this->branch_id)->get();
        } else {
            $this->searchBoxes = collect();
            $this->box_id = null;
        }

        $this->resetPage();
    }
}
