<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuizController extends Controller
{
    private const SKIN_TYPES = ['Seca', 'Oleosa', 'Mista', 'Normal', 'Sensível'];
    private const CONCERNS = ['Manchas', 'Acne', 'Hidratação', 'Envelhecimento', 'Oleosidade'];
    private const GOALS = ['Limpeza', 'Hidratação', 'Proteção', 'Rotina completa'];

    public function index()
    {
        return view('quiz.index', [
            'skinTypes' => self::SKIN_TYPES,
            'concerns' => self::CONCERNS,
            'goals' => self::GOALS,
        ]);
    }

    public function result(Request $request)
    {
        $validated = $request->validate([
            'skin_type' => ['required', Rule::in(self::SKIN_TYPES)],
            'concern' => ['required', Rule::in(self::CONCERNS)],
            'goal' => ['required', Rule::in(self::GOALS)],
        ]);

        $slugs = $this->recommendedSlugs($validated['skin_type'], $validated['concern'], $validated['goal']);

        $products = Product::whereIn('slug', $slugs)->get()->sortBy(fn ($p) => array_search($p->slug, $slugs));

        return view('quiz.result', [
            'products' => $products,
            'answers' => $validated,
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function recommendedSlugs(string $skinType, string $concern, string $goal): array
    {
        $slugs = ['gel-de-limpeza', 'tonico-facial'];

        if (in_array($concern, ['Manchas', 'Envelhecimento'], true) || $goal === 'Rotina completa') {
            $slugs[] = 'serum-facial';
        }

        if ($skinType === 'Seca' || $concern === 'Hidratação' || $goal === 'Hidratação') {
            $slugs[] = 'creme-hidratante';
        }

        $slugs[] = 'protetor-solar';

        return array_values(array_unique($slugs));
    }
}
