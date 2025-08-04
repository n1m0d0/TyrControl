<?php

namespace App\Livewire\Forms;

use App\Enums\BoxSessionStatusEnum;
use App\Models\BoxSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BoxSessionForm extends Form
{
    public ?BoxSession $boxSession = null;

    public $box;
    public $user;
    public $opening_amount;
    public $opened_at;
    public $opening_notes;
    public $closing_amount;
    public $expected_amount;
    public $difference;
    public $closed_at;
    public $closing_notes;
    public $status;

    public function rules()
    {
        return [
            'box' => 'required|exists:boxes,id',
            'opening_amount' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'opening_notes' => 'nullable|string|max:255',
            'closing_amount' => [
                $this->boxSession ? 'required' : 'nullable',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'closing_notes' => 'nullable|string|max:255',
        ];
    }

    public function setBoxSession(BoxSession $boxSession)
    {
        $this->boxSession = $boxSession;
        $this->box = $boxSession->box_id;
        $this->user = $boxSession->user_id;
        $this->opening_amount = $boxSession->opening_amount;
        $this->opened_at = $boxSession->opened_at;
        $this->opening_notes = $boxSession->opening_notes;
        $this->closing_amount = $boxSession->closing_amount;
        $this->expected_amount = $boxSession->expected_amount;
        $this->difference = $boxSession->difference;
        $this->closed_at = $boxSession->closed_at;
        $this->closing_notes = $boxSession->closing_notes;
        $this->status = $boxSession->status;
    }

    public function opening()
    {
        $this->validate();
        $this->user = Auth::id();
        $this->opened_at = now();
        $this->status = BoxSessionStatusEnum::OPEN->value;
        $data = $this->collectData();
        BoxSession::create($data);
        $this->resetForm();
    }

    public function closing()
    {
        $this->validate();
        $this->closed_at = now();
        $this->status = BoxSessionStatusEnum::CLOSED->value;
        $data = $this->collectData();
        $this->boxSession->update($data);
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'box',
            'user',
            'opening_amount',
            'opened_at',
            'opening_notes',
            'closing_amount',
            'expected_amount',
            'difference',
            'closed_at',
            'closing_notes',
            'status',
            'boxSession'
        ]);
    }

    private function collectData()
    {
        return [
            'box_id' => $this->box,
            'user_id' => $this->user,
            'opening_amount' => $this->opening_amount,
            'opened_at' => $this->opened_at,
            'opening_notes' => $this->opening_notes,
            'closing_amount' => $this->closing_amount,
            'expected_amount' => $this->expected_amount,
            'difference' => $this->difference,
            'closed_at' => $this->closed_at,
            'closing_notes' => $this->closing_notes,
            'status' => $this->status,
        ];
    }
}
