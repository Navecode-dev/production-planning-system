<?php

if (!function_exists('renderMatrix')) {
    /**
     * Renders a matrix table for the ELECTRE results view.
     *
     * @param string $title
     * @param array $matrix
     * @param \Illuminate\Support\Collection $products
     * @param \Illuminate\Support\Collection $criteria
     * @param string $format
     * @param string $itemLabel
     * @return void
     */
    function renderMatrix($title, $matrix, $items, $criteria, $itemLabel = 'Item', $format = '%.4f') {
        echo "<h4>$title</h4>";
        echo '<div class="table-responsive"><table class="table table-bordered matrix-table"><thead><tr><th class="header-cell">'.$itemLabel.'</th>';
        foreach ($criteria as $c) {
            echo '<th class="header-cell">'.$c->name.'</th>';
        }
        echo '</tr></thead><tbody>';
        foreach ($items as $item) {
            echo '<tr><td class="item-name">'.$item->name.'</td>';
            foreach ($criteria as $c) {
                $value = $matrix[$item->id][$c->id] ?? 'N/A';
                echo '<td>'.(is_numeric($value) ? sprintf($format, $value) : $value).'</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table></div><hr>';
    }
}

if (!function_exists('renderComparisonMatrix')) {
    /**
     * Renders a comparison matrix table for the ELECTRE results view.
     *
     * @param string $title
     * @param array $matrix
     * @param \Illuminate\Support\Collection $products
     * @param string $format
     * @param float|null $threshold
     * @return void
     */
    function renderComparisonMatrix($title, $matrix, $items, $format = '%.4f', $threshold = null) {
        echo "<h4>$title</h4>";
        if ($threshold !== null) {
            echo "<p>Threshold: <strong>".sprintf('%.4f', $threshold)."</strong></p>";
        }
        echo '<div class="table-responsive"><table class="table table-bordered matrix-table"><thead><tr><th class="header-cell">↓ vs →</th>';
        foreach ($items as $item) {
            echo '<th class="header-cell">'.$item->name.'</th>';
        }
        echo '</tr></thead><tbody>';
        foreach ($items as $item_row) {
            echo '<tr><td class="item-name">'.$item_row->name.'</td>';
            foreach ($items as $item_col) {
                if ($item_row->id === $item_col->id) {
                    echo '<td>-</td>';
                    continue;
                }
                $value = $matrix[$item_row->id][$item_col->id] ?? 'N/A';
                echo '<td>'.(is_numeric($value) ? sprintf($format, $value) : $value).'</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table></div><hr>';
    }
}