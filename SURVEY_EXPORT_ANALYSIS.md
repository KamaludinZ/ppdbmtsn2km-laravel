# Tes Psikologi Export Survey Answers Analysis

## Summary
The export system shows survey answers as IDs or raw values instead of human-readable labels. The code includes logic to handle this but it is NOT being used in export.

---

## 1. How Survey Data is Stored

### Survey Answer Storage Format
Database Column: jawaban (TEXT/JSONB type)

Storage by Question Type:
- text: String value
- textarea: String value  
- radio: Single value (ID or string)
- select: Single value (ID or string)
- checkbox: JSON array of IDs like [10, 12, 15]

The codebase supports TWO data approaches:
- New Approach (Feb 2026+): Store OPTION ID (integer) for data integrity
- Old Approach (before Feb 2026): Store OPTION VALUE (string)

---

## 2. Survey Question Options Structure

SurveyQuestionOption has:
- survey_question_id: Foreign key to SurveyQuestion
- label: Display text shown to users (e.g., "Pendek (150-160cm)")
- value: Alternative stored in jawaban for old data (e.g., "pendek")
- urutan: Sort order

---

## 3. Export System Issue

### TesPsikologiExport.php Lines 142-158

Current Problem:
- Export directly uses $answer->jawaban without converting to labels
- No option lookup is performed
- If data stored option ID (10), export shows "10" instead of "Pendek"

```php
foreach ($this->surveyQuestions as $question) {
    $answer = $surveyAnswers->firstWhere('survey_question_id', $question->id);
    
    if ($answer) {
        $jawaban = $answer->jawaban;  // Raw data - just IDs!
        if (is_array($jawaban)) {
            $baseData[] = implode(', ', $jawaban);  // Still just IDs
        } else {
            $baseData[] = $jawaban;  // Just raw ID or value
        }
    } else {
        $baseData[] = '-';
    }
}
```

---

## 4. Solution: SurveyAnswer Helper Methods

### Method: getJawabanFormatted() - Lines 81-165

Purpose: Convert raw answer values to human-readable labels

How it works:
1. For text/textarea - return as-is
2. For radio/select/checkbox - convert IDs to labels

Conversion priority with FOUR fallback methods:
1. Match by OPTION ID (numeric value like "10")
2. Match by OPTION VALUE (string like "sedang") 
3. Case-insensitive VALUE match
4. Case-insensitive LABEL match

Output:
- Returns label if match found: "Sedang (160-170cm)"
- Returns value with warning if option missing: "10 ⚠️"

---

## 5. Where Labels ARE Being Converted

### Admin TesPsikologi Component - Lines 1360-1484

In the "Jawaban Survey" tab (not export), the admin panel properly converts answers.
This view CORRECTLY shows labels because it performs option lookup!

The same proper conversion code exists in Panitia/TesPsikologi.php lines 847-941.

---

## 6. Root Cause Summary

| Layer | Current | Should Be |
|---|---|---|
| Database | Stores option ID or value | Both formats ✓ |
| SurveyAnswer Model | Has getJawabanFormatted() | Exists but unused in export ✗ |
| Admin Components | Show labels correctly | Working ✓ |
| Export | Uses raw jawaban value | Should use formatted ✗ |

### Key Issue: Missing Query Relationship

TesPsikologiExport.php Line 41-48:

```php
$query = TesPsikologiPendaftar::with([
    'pendaftar.jalurPendaftaran',
    'pendaftar.user',
    'pendaftar.surveyAnswers.question',  // Loads question
    // MISSING: 'pendaftar.surveyAnswers.question.options'
    'opsi',
    'bank',
    'verifiedBy'
]);
```

Missing the .options relationship chain needed for conversion!

---

## 7. Complete Data Flow Example

QUESTION ID 5: "Berapa tinggi badanmu?" (type: radio)
- Option ID 10: Label "Pendek", Value "pendek"
- Option ID 11: Label "Sedang", Value "sedang"  
- Option ID 12: Label "Tinggi", Value "tinggi"

PENDAFTAR'S ANSWER:
- jawaban: 10 (stored as option ID)

CURRENT EXPORT: Shows "10" ✗
SHOULD SHOW: Shows "Pendek" ✓

---

## 8. What Needs to Change

### In TesPsikologiExport.php

Problem: Lines 142-158 (map method)

Fix Required:
1. Load .options relationship in query (line 44)
2. Use getJawabanFormatted() method (line 148)
3. Ensure question has options loaded

Change needed in query() method:
```php
// Line 44 - ADD .options
'pendaftar.surveyAnswers.question.options',
```

Change needed in map() method:
```php
// Lines 146-154 - REPLACE with helper method
$baseData[] = $answer->getJawabanFormatted();
```

---

## 9. Files Analyzed

1. app/Exports/TesPsikologiExport.php (203 lines)
   - Export class that processes survey data
   - Has method() for query and map() for data formatting
   - Issue: Not using label conversion

2. app/Models/SurveyAnswer.php (166 lines)
   - Has getJawabanFormatted() helper (lines 81-165)
   - Includes 4 fallback matching methods
   - Includes getJawabanArray() helper for parsing

3. app/Models/SurveyQuestion.php (83 lines)
   - Defines question structure
   - Has scopes for filtering by context (umum/tes_psikologi)
   - Relationships to options and answers

4. app/Models/SurveyQuestionOption.php (38 lines)
   - Stores label, value, and order
   - Belongs to SurveyQuestion

5. app/Livewire/Admin/TesPsikologi.php (1524 lines)
   - Admin panel implementation
   - Lines 1360-1484: Correct label conversion for jawaban-survey tab
   - Shows how to properly convert IDs to labels

6. app/Livewire/Panitia/TesPsikologi.php (979 lines)
   - Panitia panel implementation  
   - Lines 847-941: Same correct conversion logic
   - Both panels work correctly, only export is broken

