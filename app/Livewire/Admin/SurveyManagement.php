<?php

namespace App\Livewire\Admin;

use App\Models\SurveyQuestion;
use App\Models\SurveyOption;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;

class SurveyManagement extends Component
{
    use WithPagination, WithSorting;

    // Question fields
    public $question_id;
    public $question_text;
    public $question_type = 'dropdown';
    public $is_required = true;
    public $order = 0;
    public $is_active = true;
    public $description;

    // Option fields
    public $option_id;
    public $option_text;
    public $option_order = 0;
    public $selected_question_id;

    // UI state
    public $showQuestionModal = false;
    public $showOptionModal = false;
    public $isEditQuestion = false;
    public $isEditOption = false;

    #[Url(keep: true)]
    public $mainTab = 'responses'; // Main tab: responses or settings

    public $activeTab = 'questions';
    public $managingOptionsForQuestion = null;

    // Filters for responses
    public $filterQuestion = null;

    // Options management for current question
    public $options = [];
    public $newOption = '';

    protected $listeners = [];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'panitia_pendaftaran', 'panitia'])) {
            return redirect()->to('/login/admin');
        }
    }

    public function setMainTab($tab)
    {
        $this->mainTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $query = SurveyQuestion::with('options');
        $questions = $this->applySorting($query)->paginate(10);

        $stats = [
            'total_questions' => SurveyQuestion::count(),
            'active_questions' => SurveyQuestion::where('is_active', true)->count(),
            'total_options' => SurveyOption::count(),
            'total_responses' => \App\Models\SurveyAnswer::distinct('pendaftar_id')->count('pendaftar_id'),
        ];

        // Get options for the question being managed
        $questionOptions = null;
        if ($this->managingOptionsForQuestion) {
            $questionOptions = SurveyOption::where('survey_question_id', $this->managingOptionsForQuestion)
                ->orderBy('order')
                ->get();
        }

        // Get survey responses
        $allQuestions = SurveyQuestion::active()->ordered()->with('options')->get();
        $responses = [];

        if ($this->mainTab === 'responses') {
            $responsesQuery = \App\Models\Pendaftar::with([
                'surveyAnswers.question',
                'surveyAnswers.option'
            ])->whereHas('surveyAnswers');

            $responses = $responsesQuery->paginate(15, ['*'], 'responsesPage');
        }

        return view('livewire.admin.survey-management', compact('questions', 'stats', 'questionOptions', 'allQuestions', 'responses'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Manajemen Survey',
                'pageDescription' => 'Kelola pertanyaan dan opsi survey pendaftaran'
            ]);
    }

    // ===== QUESTION CRUD METHODS =====
    public function createQuestion()
    {
        $this->resetQuestionFields();
        $this->isEditQuestion = false;
        $this->showQuestionModal = true;
    }

    public function editQuestion($id)
    {
        $this->resetQuestionFields();
        $question = SurveyQuestion::with('options')->findOrFail($id);

        $this->question_id = $question->id;
        $this->question_text = $question->question_text;
        $this->question_type = $question->question_type;
        $this->is_required = $question->is_required;
        $this->order = $question->order;
        $this->is_active = $question->is_active;
        $this->description = $question->description;
        $this->options = $question->options->pluck('option_text', 'id')->toArray();

        $this->isEditQuestion = true;
        $this->showQuestionModal = true;
    }

    public function storeQuestion()
    {
        $this->validate([
            'question_text' => 'required|string|max:500',
            'question_type' => 'required|in:dropdown,radio,checkbox,text',
            'is_required' => 'boolean',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        SurveyQuestion::create([
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'is_required' => $this->is_required,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'description' => $this->description,
        ]);

        $this->closeQuestionModal();
        session()->flash('message', 'Pertanyaan survey berhasil ditambahkan.');
    }

    public function updateQuestion()
    {
        $this->validate([
            'question_text' => 'required|string|max:500',
            'question_type' => 'required|in:dropdown,radio,checkbox,text',
            'is_required' => 'boolean',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($this->question_id) {
            $question = SurveyQuestion::findOrFail($this->question_id);
            $question->update([
                'question_text' => $this->question_text,
                'question_type' => $this->question_type,
                'is_required' => $this->is_required,
                'order' => $this->order,
                'is_active' => $this->is_active,
                'description' => $this->description,
            ]);
        }

        $this->closeQuestionModal();
        session()->flash('message', 'Pertanyaan survey berhasil diperbarui.');
    }

    public function deleteQuestion($id)
    {
        if (Auth::user()->role !== 'admin') {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus data.');
            return;
        }

        $question = SurveyQuestion::find($id);
        if ($question) {
            // Options will be deleted automatically due to cascade
            $question->delete();
            session()->flash('message', 'Pertanyaan survey berhasil dihapus.');
        }
    }

    public function closeQuestionModal()
    {
        $this->showQuestionModal = false;
        $this->resetQuestionFields();
        $this->resetValidation();
    }

    private function resetQuestionFields()
    {
        $this->question_id = null;
        $this->question_text = '';
        $this->question_type = 'dropdown';
        $this->is_required = true;
        $this->order = 0;
        $this->is_active = true;
        $this->description = '';
        $this->options = [];
        $this->newOption = '';
    }

    // ===== OPTION CRUD METHODS =====
    public function manageOptions($questionId)
    {
        $this->managingOptionsForQuestion = $questionId;
        $this->activeTab = 'options';
    }

    public function backToQuestions()
    {
        $this->managingOptionsForQuestion = null;
        $this->activeTab = 'questions';
    }

    public function createOption()
    {
        $this->resetOptionFields();
        $this->selected_question_id = $this->managingOptionsForQuestion;
        $this->isEditOption = false;
        $this->showOptionModal = true;
    }

    public function editOption($id)
    {
        $this->resetOptionFields();
        $option = SurveyOption::findOrFail($id);

        $this->option_id = $option->id;
        $this->option_text = $option->option_text;
        $this->option_order = $option->order;
        $this->selected_question_id = $option->survey_question_id;

        $this->isEditOption = true;
        $this->showOptionModal = true;
    }

    public function storeOption()
    {
        $this->validate([
            'option_text' => 'required|string|max:255',
            'option_order' => 'integer|min:0',
            'selected_question_id' => 'required|exists:survey_questions,id',
        ]);

        SurveyOption::create([
            'survey_question_id' => $this->selected_question_id,
            'option_text' => $this->option_text,
            'order' => $this->option_order,
        ]);

        $this->closeOptionModal();
        session()->flash('message', 'Opsi berhasil ditambahkan.');
        $this->dispatch('$refresh');
    }

    public function updateOption()
    {
        $this->validate([
            'option_text' => 'required|string|max:255',
            'option_order' => 'integer|min:0',
        ]);

        if ($this->option_id) {
            $option = SurveyOption::findOrFail($this->option_id);
            $option->update([
                'option_text' => $this->option_text,
                'order' => $this->option_order,
            ]);
        }

        $this->closeOptionModal();
        session()->flash('message', 'Opsi berhasil diperbarui.');
        $this->dispatch('$refresh');
    }

    public function deleteOption($id)
    {
        if (Auth::user()->role !== 'admin') {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus data.');
            return;
        }

        $option = SurveyOption::find($id);
        if ($option) {
            $option->delete();
            session()->flash('message', 'Opsi berhasil dihapus.');
        }
    }

    public function closeOptionModal()
    {
        $this->showOptionModal = false;
        $this->resetOptionFields();
        $this->resetValidation();
    }

    private function resetOptionFields()
    {
        $this->option_id = null;
        $this->option_text = '';
        $this->option_order = 0;
        $this->selected_question_id = null;
    }
}
