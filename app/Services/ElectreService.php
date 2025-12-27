<?php

namespace App\Services;

use App\Models\Criteria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ElectreService
{
    public function calculate(string $modelClass, string $category): ?array
    {
        $items = $modelClass::with('scores')->get();
        $criteria = Criteria::where('category', $category)->orderBy('id')->get();

        if ($items->isEmpty() || $criteria->isEmpty()) {
            return null;
        }

        // Fungsi utama Electre
        $decisionMatrix = $this->buildDecisionMatrix($items, $criteria);
        $normalizedMatrix = $this->normalizeDecisionMatrix($decisionMatrix, $items, $criteria);
        $weightedMatrix = $this->createWeightedMatrix($normalizedMatrix, $items, $criteria);
        list($concordanceSets, $discordanceSets) = $this->determineConcordanceAndDiscordanceSets($weightedMatrix, $items, $criteria);
        $concordanceMatrix = $this->calculateConcordanceMatrix($concordanceSets, $items, $criteria);
        $discordanceMatrix = $this->calculateDiscordanceMatrix($discordanceSets, $weightedMatrix, $items, $criteria);
        $concordanceThreshold = $this->calculateThreshold($concordanceMatrix);
        $discordanceThreshold = $this->calculateThreshold($discordanceMatrix);
        $concordanceDominance = $this->determineDominanceMatrix($concordanceMatrix, $concordanceThreshold, $items);
        $discordanceDominance = $this->determineDominanceMatrix($discordanceMatrix, $discordanceThreshold, $items, false);
        $aggregateDominanceMatrix = $this->calculateAggregateDominance($concordanceDominance, $discordanceDominance, $items);
        $ranking = $this->getRanking($aggregateDominanceMatrix, $items);

        return [
            'items' => $items,
            'criteria' => $criteria,
            'decisionMatrix' => $decisionMatrix,
            'normalizedMatrix' => $normalizedMatrix,
            'weightedMatrix' => $weightedMatrix,
            'concordanceMatrix' => $concordanceMatrix,
            'discordanceMatrix' => $discordanceMatrix,
            'concordanceThreshold' => $concordanceThreshold,
            'discordanceThreshold' => $discordanceThreshold,
            'concordanceDominance' => $concordanceDominance,
            'discordanceDominance' => $discordanceDominance,
            'aggregateDominanceMatrix' => $aggregateDominanceMatrix,
            'ranking' => $ranking,
            'concordanceSets' => $concordanceSets,
            'discordanceSets' => $discordanceSets
        ];
    }

    private function buildDecisionMatrix(Collection $items, Collection $criteria): array
    {
        $matrix = [];
        foreach ($items as $item) {
            $scores = $item->scores->keyBy('criteria_id');
            foreach ($criteria as $criterion) {
                $matrix[$item->id][$criterion->id] = $scores->get($criterion->id)->value ?? 0;
            }
        }
        return $matrix;
    }

    private function normalizeDecisionMatrix(array $matrix, Collection $items, Collection $criteria): array
    {
        $normalizedMatrix = [];
        foreach ($criteria as $criterion) {
            $sumOfSquares = 0;
            foreach ($items as $item) {
                $sumOfSquares += pow($matrix[$item->id][$criterion->id], 2);
            }
            $divisor = sqrt($sumOfSquares);

            foreach ($items as $item) {
                $normalizedMatrix[$item->id][$criterion->id] = $divisor == 0 ? 0 : $matrix[$item->id][$criterion->id] / $divisor;
            }
        }
        return $normalizedMatrix;
    }

    private function createWeightedMatrix(array $normalizedMatrix, Collection $items, Collection $criteria): array
    {
        $weightedMatrix = [];
        $criteriaWeights = $criteria->pluck('weight', 'id');

        foreach ($items as $item) {
            foreach ($criteria as $criterion) {
                $weight = $criteriaWeights[$criterion->id];
                $weightedMatrix[$item->id][$criterion->id] = $normalizedMatrix[$item->id][$criterion->id] * $weight;
            }
        }
        return $weightedMatrix;
    }

    private function determineConcordanceAndDiscordanceSets(array $weightedMatrix, Collection $items, Collection $criteria): array
    {
        $concordanceSets = [];
        $discordanceSets = [];

        foreach ($items as $k) {
            foreach ($items as $l) {
                if ($k->id === $l->id) continue;

                $concordanceSets[$k->id][$l->id] = [];
                $discordanceSets[$k->id][$l->id] = [];

                foreach ($criteria as $criterion) {
                    $valK = $weightedMatrix[$k->id][$criterion->id];
                    $valL = $weightedMatrix[$l->id][$criterion->id];

                    if ($criterion->type === 'benefit' ? ($valK >= $valL) : ($valK <= $valL)) {
                        $concordanceSets[$k->id][$l->id][] = $criterion->id;
                    } else {
                        $discordanceSets[$k->id][$l->id][] = $criterion->id;
                    }
                }
            }
        }
        return [$concordanceSets, $discordanceSets];
    }

    private function calculateConcordanceMatrix(array $concordanceSets, Collection $items, Collection $criteria): array
    {
        $concordanceMatrix = [];
        $criteriaWeights = $criteria->pluck('weight', 'id');

        foreach ($items as $k) {
            foreach ($items as $l) {
                if ($k->id === $l->id) continue;

                $weightSum = 0;
                foreach ($concordanceSets[$k->id][$l->id] as $criterionId) {
                    $weightSum += $criteriaWeights[$criterionId];
                }
                $concordanceMatrix[$k->id][$l->id] = $weightSum;
            }
        }
        return $concordanceMatrix;
    }

    private function calculateDiscordanceMatrix(array $discordanceSets, array $weightedMatrix, Collection $items, Collection $criteria): array
    {
        $discordanceMatrix = [];

        foreach ($items as $k) {
            foreach ($items as $l) {
                if ($k->id === $l->id) continue;

                $maxNumerator = 0;
                if (!empty($discordanceSets[$k->id][$l->id])) {
                    foreach ($discordanceSets[$k->id][$l->id] as $criterionId) {
                        $diff = abs($weightedMatrix[$k->id][$criterionId] - $weightedMatrix[$l->id][$criterionId]);
                        if ($diff > $maxNumerator) {
                            $maxNumerator = $diff;
                        }
                    }
                }

                $maxDenominator = 0;
                foreach ($criteria as $criterion) {
                    $diff = abs($weightedMatrix[$k->id][$criterion->id] - $weightedMatrix[$l->id][$criterion->id]);
                    if ($diff > $maxDenominator) {
                        $maxDenominator = $diff;
                    }
                }

                $discordanceMatrix[$k->id][$l->id] = $maxDenominator == 0 ? 0 : $maxNumerator / $maxDenominator;
            }
        }
        return $discordanceMatrix;
    }

    private function calculateThreshold(array $matrix): float
    {
        $values = array_merge(...array_values($matrix));
        $count = count($values);
        return $count > 0 ? array_sum($values) / $count : 0;
    }

    private function determineDominanceMatrix(array $matrix, float $threshold, Collection $items, bool $greaterThanOrEqual = true): array
    {
        $dominance = [];
        foreach ($items as $k) {
            foreach ($items as $l) {
                if ($k->id === $l->id) continue;
                if ($greaterThanOrEqual) {
                    $dominance[$k->id][$l->id] = ($matrix[$k->id][$l->id] >= $threshold) ? 1 : 0;
                } else {
                    $dominance[$k->id][$l->id] = ($matrix[$k->id][$l->id] <= $threshold) ? 1 : 0;
                }
            }
        }
        return $dominance;
    }

    private function calculateAggregateDominance(array $concordanceDominance, array $discordanceDominance, Collection $items): array
    {
        $aggregate = [];
        foreach ($items as $k) {
            foreach ($items as $l) {
                if ($k->id === $l->id) continue;
                $aggregate[$k->id][$l->id] = $concordanceDominance[$k->id][$l->id] * $discordanceDominance[$k->id][$l->id];
            }
        }
        return $aggregate;
    }

    private function getRanking(array $aggregateDominance, Collection $items): array
    {
        $rankingScores = [];
        foreach ($items as $k) {
            $wins = 0;
            $losses = 0;
            foreach ($items as $l) {
                if ($k->id === $l->id) continue;
                if (isset($aggregateDominance[$k->id][$l->id]) && $aggregateDominance[$k->id][$l->id] == 1) {
                    $wins++;
                }
                if (isset($aggregateDominance[$l->id][$k->id]) && $aggregateDominance[$l->id][$k->id] == 1) {
                    $losses++;
                }
            }
            $rankingScores[$k->id] = $wins - $losses;
        }

        $itemNames = $items->pluck('name', 'id');
        $ranking = [];
        foreach ($rankingScores as $itemId => $score) {
            $ranking[$itemNames[$itemId]] = $score;
        }
        arsort($ranking);
        return $ranking;
    }
}