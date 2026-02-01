<?php

namespace App\Livewire\Admin;

use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
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
    public $pertanyaan;
    public $tipe_input = 'select';
    public $wajib_diisi = true;
    public $urutan = 0;
    public $aktif = true;
    public $deskripsi;
    public $context = 'umum'; // Survey Umum context

    // Option fields
    public $option_id;
    public $label;
    public $value;
    public $option_urutan = 0;
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
        // PENTING: Filter by context = 'umum' untuk Survey Umum
        $query = SurveyQuestion::umum()->with('options');
        $questions = $this->applySorting($query)->paginate(10);

        $stats = [
            'total_questions' => SurveyQuestion::umum()->count(),
            'active_questions' => SurveyQuestion::umum()->where('aktif', true)->count(),
            'total_options' => \App\Models\SurveyQuestionOption::whereHas('question', function($q) {
                $q->umum();
            })->count(),
            'total_responses' => \App\Models\SurveyAnswer::whereHas('question', function($q) {
                $q->umum();
            })->distinct('pendaftar_id')->count('pendaftar_id'),
        ];

        // Get options for the question being managed
        $questionOptions = null;
        if ($this->managingOptionsForQuestion) {
            $questionOptions = \App\Models\SurveyQuestionOption::where('survey_question_id', $this->managingOptionsForQuestion)
                ->orderBy('urutan')
                ->get();
        }

        // Get survey responses - Filter by context umum
        $allQuestions = SurveyQuestion::umum()->aktif()->ordered()->with('options')->get();
        $responses = [];

        if ($this->mainTab === 'responses') {
            $responsesQuery = \App\Models\Pendaftar::with([
                'surveyAnswers.question'
            ])->whereHas('surveyAnswers', function($q) {
                $q->whereHas('question', function($q2) {
                    $q2->umum();
                });
            });

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
        $this->pertanyaan = $question->pertanyaan;
        $this->tipe_input = $question->tipe_input;
        $this->wajib_diisi = $question->wajib_diisi;
        $this->urutan = $question->urutan;
        $this->aktif = $question->aktif;
        $this->deskripsi = $question->deskripsi;
        $this->options = $question->options->pluck('label', 'id')->toArray();

        $this->isEditQuestion = true;
        $this->showQuestionModal = true;
    }

    public function storeQuestion()
    {
        $this->validate([
            'pertanyaan' => 'required|string|max:500',
            'tipe_input' => 'required|in:select,radio,checkbox,text,textarea',
            'wajib_diisi' => 'boolean',
            'urutan' => 'integer|min:0',
            'aktif' => 'boolean',
        ]);

        SurveyQuestion::create([
            'pertanyaan' => $this->pertanyaan,
            'tipe_input' => $this->tipe_input,
            'wajib_diisi' => $this->wajib_diisi,
            'urutan' => $this->urutan,
            'aktif' => $this->aktif,
            'deskripsi' => $this->deskripsi,
            'context' => 'umum', // PENTING: Set context untuk Survey Umum
        ]);

        session()->flash('message', 'Pertanyaan survey berhasil ditambahkan.');
        $this->closeQuestionModal();

        // Force re-render untuk refresh list pertanyaan
        $this->dispatch('questionUpdated');
    }

    public function updateQuestion()
    {
        $this->validate([
            'pertanyaan' => 'required|string|max:500',
            'tipe_input' => 'required|in:select,radio,checkbox,text,textarea',
            'wajib_diisi' => 'boolean',
            'urutan' => 'integer|min:0',
            'aktif' => 'boolean',
        ]);

        if ($this->question_id) {
            $question = SurveyQuestion::findOrFail($this->question_id);
            $question->update([
                'pertanyaan' => $this->pertanyaan,
                'tipe_input' => $this->tipe_input,
                'wajib_diisi' => $this->wajib_diisi,
                'urutan' => $this->urutan,
                'aktif' => $this->aktif,
                'deskripsi' => $this->deskripsi,
            ]);
        }

        session()->flash('message', 'Pertanyaan survey berhasil diperbarui.');
        $this->closeQuestionModal();

        // Force re-render untuk refresh list pertanyaan
        $this->dispatch('questionUpdated');
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

            // Force re-render untuk refresh list pertanyaan
            $this->dispatch('questionUpdated');
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
        $this->pertanyaan = '';
        $this->tipe_input = 'select';
        $this->wajib_diisi = true;
        $this->urutan = 0;
        $this->aktif = true;
        $this->deskripsi = '';
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
        $option = SurveyQuestionOption::findOrFail($id);

        $this->option_id = $option->id;
        $this->label = $option->label;
        $this->value = $option->value;
        $this->option_urutan = $option->urutan;
        $this->selected_question_id = $option->survey_question_id;

        $this->isEditOption = true;
        $this->showOptionModal = true;
    }

    public function storeOption()
    {
        $this->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'option_urutan' => 'integer|min:0',
            'selected_question_id' => 'required|exists:survey_questions,id',
        ]);

        SurveyQuestionOption::create([
            'survey_question_id' => $this->selected_question_id,
            'label' => $this->label,
            'value' => $this->value,
            'urutan' => $this->option_urutan,
        ]);

        session()->flash('message', 'Opsi berhasil ditambahkan.');
        $this->closeOptionModal();

        // Force re-render untuk refresh list opsi
        $this->dispatch('optionUpdated');
    }

    public function updateOption()
    {
        $this->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'option_urutan' => 'integer|min:0',
        ]);

        if ($this->option_id) {
            $option = SurveyQuestionOption::findOrFail($this->option_id);
            $option->update([
                'label' => $this->label,
                'value' => $this->value,
                'urutan' => $this->option_urutan,
            ]);
        }

        session()->flash('message', 'Opsi berhasil diperbarui.');
        $this->closeOptionModal();

        // Force re-render untuk refresh list opsi
        $this->dispatch('optionUpdated');
    }

    public function deleteOption($id)
    {
        if (Auth::user()->role !== 'admin') {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus data.');
            return;
        }

        $option = SurveyQuestionOption::find($id);
        if ($option) {
            $option->delete();
            session()->flash('message', 'Opsi berhasil dihapus.');

            // Force re-render untuk refresh list opsi
            $this->dispatch('optionUpdated');
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
        $this->label = '';
        $this->value = '';
        $this->option_urutan = 0;
        $this->selected_question_id = null;
    }

    /**
     * Get nama kabupaten from code
     */
    public function getNamaKabupaten($code)
    {
        if (!$code) return '-';
        $city = \App\Models\Regency::where('id', $code)->first();
        return $city ? $city->name : '-';
    }
}
