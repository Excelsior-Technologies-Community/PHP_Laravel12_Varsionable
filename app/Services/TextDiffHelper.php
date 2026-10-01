<?php

namespace App\Services;

class TextDiffHelper
{
    /**
     * Compute word-level diff between two strings.
     * Returns HTML with <ins> for additions (green) and <del> for deletions (red).
     */
    public static function renderWordDiff(?string $oldText, ?string $newText): string
    {
        $oldText = (string) ($oldText ?? '');
        $newText = (string) ($newText ?? '');

        if ($oldText === $newText) {
            return htmlspecialchars($newText);
        }

        $oldWords = preg_split('/(\s+)/u', $oldText, -1, PREG_SPLIT_DELIM_CAPTURE);
        $newWords = preg_split('/(\s+)/u', $newText, -1, PREG_SPLIT_DELIM_CAPTURE);

        $diff = static::computeLcsDiff($oldWords, $newWords);
        $html = '';

        foreach ($diff as $item) {
            if ($item[1] === 0) {
                $html .= htmlspecialchars($item[0]);
            } elseif ($item[1] === -1) {
                $html .= '<del style="background-color: #f8d7da; color: #721c24; text-decoration: line-through; padding: 2px 4px; border-radius: 4px; margin: 0 2px;">' . htmlspecialchars($item[0]) . '</del>';
            } elseif ($item[1] === 1) {
                $html .= '<ins style="background-color: #d4edda; color: #155724; text-decoration: none; font-weight: bold; padding: 2px 4px; border-radius: 4px; margin: 0 2px;">' . htmlspecialchars($item[0]) . '</ins>';
            }
        }

        return $html;
    }

    /**
     * Compute Longest Common Subsequence (LCS) diff array.
     */
    private static function computeLcsDiff(array $a, array $b): array
    {
        $n = count($a);
        $m = count($b);

        // Limit LCS matrix size to avoid excessive memory on huge strings
        if ($n > 1000 || $m > 1000) {
            return [
                [$oldText ?? '', -1],
                [$newText ?? '', 1],
            ];
        }

        $lcs = array_fill(0, $n + 1, array_fill(0, $m + 1, 0));

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $m; $j++) {
                if ($a[$i] === $b[$j]) {
                    $lcs[$i + 1][$j + 1] = $lcs[$i][$j] + 1;
                } else {
                    $lcs[$i + 1][$j + 1] = max($lcs[$i + 1][$j], $lcs[$i][$j + 1]);
                }
            }
        }

        $result = [];
        $i = $n;
        $j = $m;

        while ($i > 0 || $j > 0) {
            if ($i > 0 && $j > 0 && $a[$i - 1] === $b[$j - 1]) {
                array_unshift($result, [$a[$i - 1], 0]);
                $i--;
                $j--;
            } elseif ($j > 0 && ($i === 0 || $lcs[$i][$j - 1] >= $lcs[$i - 1][$j])) {
                array_unshift($result, [$b[$j - 1], 1]);
                $j--;
            } elseif ($i > 0 && ($j === 0 || $lcs[$i][$j - 1] < $lcs[$i - 1][$j])) {
                array_unshift($result, [$a[$i - 1], -1]);
                $i--;
            }
        }

        return $result;
    }
}
