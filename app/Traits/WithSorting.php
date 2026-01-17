<?php

namespace App\Traits;

trait WithSorting
{
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    /**
     * Sort by the given field
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // Toggle direction if clicking the same field
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Set new field and default to ascending
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        // Reset to first page when sorting changes
        $this->resetPage();
    }

    /**
     * Apply sorting to a query
     */
    protected function applySorting($query)
    {
        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    /**
     * Get the sort icon for a field
     */
    public function getSortIcon($field)
    {
        if ($this->sortField !== $field) {
            return 'sort'; // neutral
        }

        return $this->sortDirection === 'asc' ? 'sort-asc' : 'sort-desc';
    }

    /**
     * Check if a field is currently being sorted
     */
    public function isSortedBy($field)
    {
        return $this->sortField === $field;
    }
}
