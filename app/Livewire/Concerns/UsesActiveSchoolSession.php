<?php

namespace App\Livewire\Concerns;

use App\Models\Session;

trait UsesActiveSchoolSession
{
    public bool $mutationsEnabled = false;

    public function toggleMutations(): void
    {
        $this->mutationsEnabled = !$this->mutationsEnabled;
    }

    protected function canMutate(): bool
    {
        if ($this->mutationsEnabled) {
            return true;
        }

        session()->flash('error', 'Enable editing before creating, updating, or deleting records.');

        return false;
    }

    protected function activeSchoolId(): ?int
    {
        return auth()->user()?->school_id;
    }

    protected function activeSession(): ?Session
    {
        return Session::query()
            ->where('is_active', true)
            ->where('school_id', $this->activeSchoolId())
            ->orderByDesc('id')
            ->first();
    }

    protected function scopeSchool($query): mixed
    {
        return $query->where('school_id', $this->activeSchoolId());
    }

    protected function scopeSchoolSession($query): mixed
    {
        return $this->scopeSchool($query)
            ->where('session_id', $this->activeSession()?->id);
    }
}