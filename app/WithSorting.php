<?php

namespace App;

trait WithSorting
{
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    /**
     * Sort by a specific field
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // Toggle direction if clicking the same field
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Default to ascending for new field
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        // Reset to first page when sorting
        $this->resetPage();
    }

    /**
     * Apply sorting to the query
     */
    public function applySorting($query)
    {
        if ($this->sortField && $this->sortDirection) {
            return $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query;
    }

    /**
     * Reset sorting to default
     */
    public function resetSorting()
    {
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }
}
