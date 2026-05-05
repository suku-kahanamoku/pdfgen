<?php

// ============================================================
// EMIT_MARKER – emituje TOCMARKER div uvnitř komponenty
// Volá se jako první child hlavního wrapper divu.
// Aktivní jen pokud je $GLOBALS['display_marker'] = true.
// ============================================================
function EMIT_MARKER(): void
{
    if (!empty($GLOBALS['display_marker']) && isset($GLOBALS['_marker'])) {
        $id = $GLOBALS['_marker'];
        unset($GLOBALS['_marker']);
        echo '<div style="font-size:8px;color:#000;line-height:1;margin:0;padding:0;">TOCMARKER_' . $id . '</div>';
    }
}

// ============================================================
// BUILD_PAGE_DEFINITION – sestaví TOC definici a uloží do $GLOBALS['page_definition']
// ============================================================
function BUILD_PAGE_DEFINITION(array $data, array $tocPageMap): void
{
    $def = [];

    if (!empty($data['intro'])) {
        $def['intro'] = [
            'title' => 'Úvod',
            'items' => [
                1 => ['title' => 'O finanční analýze a společnosti', 'page' => $tocPageMap[1]  ?? 0],
                2 => ['title' => 'Očekávání a hodnoty',              'page' => $tocPageMap[2]  ?? 0],
                3 => ['title' => 'Účastníci finančního plánu',       'page' => $tocPageMap[3]  ?? 0],
            ],
        ];
    }
    if (!empty($data['balance'])) {
        $def['balance'] = [
            'title' => 'Rozvaha',
            'items' => [
                4 => ['title' => 'Výdaje',  'page' => $tocPageMap[4] ?? 0],
                5 => ['title' => 'Příjmy',  'page' => $tocPageMap[5] ?? 0],
                6 => ['title' => 'Bilance', 'page' => $tocPageMap[6] ?? 0],
            ],
        ];
    }
    if (!empty($data['property'])) {
        $def['property'] = [
            'title' => 'Přehled vašeho majetku',
            'items' => [
                7 => ['title' => 'Přehled',        'page' => $tocPageMap[7]  ?? 0],
                8 => ['title' => 'Portfolio',       'page' => $tocPageMap[8]  ?? 0],
                9 => ['title' => 'Statistiky',      'page' => $tocPageMap[9]  ?? 0],
                10 => ['title' => 'Bonita',          'page' => $tocPageMap[10] ?? 0],
                11 => ['title' => 'Ochrana majetku', 'page' => $tocPageMap[11] ?? 0],
            ],
        ];
    }
    if (!empty($data['goal'])) {
        $def['goal'] = [
            'title' => 'Sny a finanční cíle',
            'items' => [
                12 => ['title' => 'Vaše sny',      'page' => $tocPageMap[12] ?? 0],
                13 => ['title' => 'Plán a řešení', 'page' => $tocPageMap[13] ?? 0],
            ],
        ];
    }
    if (!empty($data['health'])) {
        $def['health'] = [
            'title' => 'Pojištění zdraví',
            'items' => [
                14 => ['title' => 'Pojištění a pojistné částky', 'page' => $tocPageMap[14] ?? 0],
            ],
        ];
    }
    if (!empty($data['action_plan'])) {
        $def['action_plan'] = [
            'title' => 'Vyhodnocení a akční plán',
            'items' => [
                15 => ['title' => 'Vyhodnocení', 'page' => $tocPageMap[15] ?? 0],
            ],
        ];
    }

    $GLOBALS['page_definition'] = $def;
}
